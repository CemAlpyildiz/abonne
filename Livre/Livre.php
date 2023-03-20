<?php

namespace Livre;

use Pdo\Connexion;
use Pdo;


class Livre {

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
        $sPagination.=  "<a href='./?p=" . ($iCurrentPage - 1) . "' class='page-link'>Précédente</a>";
        $sPagination.=  "</li>";
        for ($page = 1; $page <= $pages; $page++) {
            $sPagination.=  "<li class='page-item" . (($iCurrentPage == $page) ? ' active' : '') . "'>";
            $sPagination.=  "<a href='./?p=$page' class='page-link'>$page</a>";
            $sPagination.=  "</li>";
        }
        $sPagination.=  "<li class='page-item" . (($iCurrentPage == $pages) ? ' disabled' : '') . "'>";
        $sPagination.=  "<a href='./?p=" . ($iCurrentPage + 1) . "' class='page-link'>Suivante</a>";
        $sPagination.=  "</li>";
        $sPagination.=  "</ul>";
        $sPagination.=  "</nav>";

        return $sPagination;
    }
    

    public function getBooks()
    {
        
        //Pagination 
        if(isset($_GET['p']) && !empty($_GET['p'])){
            $iCurrentPage = (int) strip_tags($_GET['p']);
        }else{
            $iCurrentPage = 1;
        }
        $oDb = $this->connexion();

        foreach($_POST as $post_key => $post_value){
            str_replace('_', '.', $post_key);
        }

        if(isset($_GET['getBooks']) && (isset($_POST['titre_livre']) || isset($_POST['auteur_nom']) || isset($_POST['editeur_nom']) || isset($_POST['disponible']))){
           
            $sSqlQuery = "SELECT count(*) as nb_rows
            FROM livre 
            JOIN editeur ON editeur.id = livre.id_editeur
            JOIN auteur ON auteur.id = livre.id_auteur
            JOIN emprunt ON emprunt.id_livre = livre.id";

            if(isset($_GET['getBooks']) && ((isset($_POST['titre']) || isset($_POST['auteur_nom']) || isset($_POST['editeur_nom']) || isset($_POST['disponible'])))){
                $count = 1;
                foreach($_POST as $key => $value)
                {
                    $key = str_replace('_', '.', $key);
                    if($value != "")
                    {
                        if($count == 1){
                            if(isset($_POST['disponible']) && $key == "disponible"){
                                $sSqlQuery.= " WHERE " . "date_retour IS NOT NULL";
                            }else{
                                $sSqlQuery.= " WHERE LOWER(" . $key .") LIKE '%". strtolower($value) . "%'";
                            }
                        }else{
                            if(isset($_POST['disponible']) && $key == "disponible"){
                                $sSqlQuery.= " AND " . "date_retour IS NOT NULL";
                            }else{
                                $sSqlQuery.= " AND LOWER(" . $key .") LIKE '%". strtolower($value) . "%'";
                            }
                        }
                        $count++;
                    }
                }
            }
            $sSqlQuery = $sSqlQuery . ' GROUP BY livre.id';
        }
        else{
            $sSqlQuery = 'SELECT count(*) as nb_rows
            FROM livre 
            JOIN editeur ON editeur.id = livre.id_editeur
            JOIN auteur ON auteur.id = livre.id_auteur';
        }


echo "-----";
        var_dump($sSqlQuery);

        echo "-----";
        

        $oSqlStatement = $oDb->prepare($sSqlQuery);
        $oSqlStatement->execute();
        $aRows = $oSqlStatement->fetchAll();

        //ici problème de pagination lors d'un filtre
        print_r($aRows);

        if(isset($aRows[0]['nb_rows'])){
            echo "ici";
            $iNbRows = (int)$aRows[0]['nb_rows'];
        }else{
            echo "laaa";
            $iNbRows = 0;
        }

       
        $iPerPage = 20;
        $iPage = ceil($iNbRows / $iPerPage);
        $iStart = ($iCurrentPage * $iPerPage) - $iPerPage;

        var_dump($iCurrentPage);
        var_dump($iPage);

        $sPaginationBlock = $this->getPage($iCurrentPage, $iPage);

        
        $sSqlQuery = "SELECT livre.titre as livre_titre, auteur.nom as auteur_nom, editeur.nom as editeur_nom, date_retour, max(date_emprunt) as date_emprunt
            FROM livre 
            JOIN editeur ON editeur.id = livre.id_editeur
            JOIN auteur ON auteur.id = livre.id_auteur
            JOIN emprunt ON emprunt.id_livre = livre.id";

        //verifier qu'il y ait un filtre au minimum pour ajouter un WHERE
        if(isset($_GET['getBooks']) && (isset($_POST['titre']) || isset($_POST['auteur.nom']) || isset($_POST['editeur.nom']) || isset($_POST['disponible']))){
            $count = 1;
            foreach($_POST as $key => $value)
            {
                $key = str_replace('_', '.', $key);
                if($value != "")
                {
                    if($count == 1){
                        if(isset($_POST['disponible']) && $value == "disponible"){
                            $sSqlQuery.= " WHERE " . "date_retour IS NOT NULL";
                        }else{
                            $sSqlQuery.= " WHERE LOWER(" . $key .") LIKE '%". strtolower($value) . "%'";
                        }
                       
                    }else{
                        if(isset($_POST['disponible']) && $value == "disponible"){
                            $sSqlQuery.= " AND " . "date_retour IS NOT NULL";
                        }else{
                            $sSqlQuery.= " AND LOWER(" . $key .") LIKE '%". strtolower($value) . "%'";
                        }
                    }
                    $count++;
                }
                
            }
        }
        //Tester si le champ est rempli et ajouter le where


        $sSqlQuery = $sSqlQuery . ' GROUP BY livre.id
            LIMIT 20 OFFSET '.($iCurrentPage - 1) * 20;

            var_dump($sSqlQuery);
        
        $oSqlStatement = $oDb->prepare($sSqlQuery);


        $oSqlStatement->execute();

        $aRows = $oSqlStatement->fetchAll();

        return array(
            'result' => $aRows,
            'pagination' => $sPaginationBlock
        );

    }
}