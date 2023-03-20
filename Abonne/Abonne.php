<?php

namespace Abonne;

use Pdo\Connexion;
use Pdo;


class Abonne {

    private function connexion(): Pdo
    {
        $db = new Connexion('localhost', 'abonne', 'root', '');
        return $db->connexion();
    }

    public function getPage($iCurrentPage, $pages) 
    {
        $sPagination = "";
        $sPagination.= "<nav>";
        $sPagination.=  "<ul class='pagination'>";
        $sPagination.=  "<li class='page-item" . (($iCurrentPage == 1) ? ' disabled' : '') . "'>";
        $sPagination.=  "<a href='./index_abonne.php?p=" . ($iCurrentPage - 1) . "' class='page-link'>Précédente</a>";
        $sPagination.=  "</li>";
        for ($page = 1; $page <= $pages; $page++) {
            $sPagination.=  "<li class='page-item" . (($iCurrentPage == $page) ? ' active' : '') . "'>";
            $sPagination.=  "<a href='./index_abonne.php?p=$page' class='page-link'>$page</a>";
            $sPagination.=  "</li>";
        }
        $sPagination.=  "<li class='page-item" . (($iCurrentPage == $pages) ? ' disabled' : '') . "'>";
        $sPagination.=  "<a href='./index_abonne.php?p=" . ($iCurrentPage + 1) . "' class='page-link'>Suivante</a>";
        $sPagination.=  "</li>";
        $sPagination.=  "</ul>";
        $sPagination.=  "</nav>";

        return $sPagination;
    }
    

    public function getAbonnes()
    {
        //Pagination 
        if(isset($_GET['p']) && !empty($_GET['p'])){
            $iCurrentPage = (int) strip_tags($_GET['p']);
        }else{
            $iCurrentPage = 1;
        }

        $oDb = $this->connexion();

        //Pagination

        if( (isset($_GET['nom']) && !empty($_GET['nom'])) || (isset($_GET['prenom']) && !empty($_GET['prenom'])) || (isset($_GET['ville']) && !empty($_GET['ville'])) || (isset($_GET['status']) && !empty($_GET['status'])))
        {

            $sSqlQuery = "SELECT nom, prenom, ville,date_naissance,date_fin_abo, count(*) as nb
            FROM abonne";

            $count = 1;
            foreach($_GET as $key => $value){
                
                if(isset($value) && !empty($value)){
                    if($count == 1){
                        $sSqlQuery.= " WHERE ";
                    }else{
                      
                        $sSqlQuery.= " AND ";
                        
                    }
                    if ($key == "status") {
                        $key = "(CASE 
                        WHEN date_fin_abo >= CURRENT_DATE() THEN CONCAT('1') 
                        ELSE '0' 
                    END)";
                    }
                    $sSqlQuery .= "LOWER(" . $key .") LIKE '%". strtolower($value) . "%'";
                    $count++;
                }
            }
        }
        else{
            $sSqlQuery = "SELECT count(*) as nb_rows FROM abonne";
            
        }

        $oSqlStatement = $oDb->prepare($sSqlQuery);
        $oSqlStatement->execute();
        $aRows = $oSqlStatement->fetchAll();    
           
        $iNbRows = (int)$aRows[0]['nb'];
        $iPerPage = 20;
        $iPage = ceil($iNbRows / $iPerPage);
       

        $sPaginationBlock = $this->getPage($iCurrentPage, $iPage);

        /*--- Get all abonne ---*/

        $sSqlQuery = "SELECT nom,prenom,ville,date_naissance,date_fin_abo FROM abonne";

        if(isset($_GET['nom']) || isset($_GET['prenom']) || isset($_GET['ville']) || isset($_GET['abonnement']))
        {
            $sSqlQuery = "SELECT nom, prenom, ville,date_naissance,date_fin_abo,
                CASE 
                    WHEN date_fin_abo >= CURRENT_DATE() THEN CONCAT('1') 
                    ELSE '0' 
                END AS status 
            FROM abonne";

            $count = 1;
            foreach($_GET as $key => $value)
            {
                //$key = str_replace('_', '.', $key);
                if($value != "")
                {
                    if($count == 1){
                      
                        $sSqlQuery.= " WHERE ";
                    }else{
                      
                        $sSqlQuery.= " AND ";
                        
                    }
                    if ($key == "status") {
                        $key = "(CASE 
                        WHEN date_fin_abo >= CURRENT_DATE() THEN CONCAT('1') 
                        ELSE '0' 
                    END)";
                    }
                    $sSqlQuery .= "LOWER(" . $key .") LIKE '%". strtolower($value) . "%'";
                    $count++;
                }
                
            }
        }

        $sSqlQuery = $sSqlQuery . ' LIMIT 20 OFFSET '.($iCurrentPage - 1) * 20;

        $oSqlStatement = $oDb->prepare($sSqlQuery);
        $oSqlStatement->execute();
        $aRows = $oSqlStatement->fetchAll();

        return array(
            'result' => $aRows,
            'pagination' => $sPaginationBlock
        ); 
    }
}