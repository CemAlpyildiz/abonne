<?php

namespace Fiche;

use Pdo\Connexion;
use Pdo;


class Fiche {

    private function connexion(): Pdo
    {
        $db = new Connexion('localhost', 'abonne', 'root', '');
        return $db->connexion();
    }
    
    public function getAbonneFiche()
    {
        if(isset($_GET['id']) && !is_null($_GET['id'])){
            $oDb = $this->connexion();

            $sSqlQuery = "SELECT * FROM abonne WHERE id = :id";
            $oSqlStatement = $oDb->prepare($sSqlQuery);
            $oSqlStatement->execute([
                'id' => $_GET['id'],
            ]);
            
            $aRows = $oSqlStatement->fetchAll();
    
            return $aRows;
        }
        else{
            return null;
        }
        
    }

    public function getAbonneEmprunt()
    {
        
        if(isset($_GET['id']) && !is_null($_GET['id']))
        {
            $oDb = $this->connexion();

            $sSqlQuery = "SELECT livre.titre, date_emprunt
                          FROM livre 
                          JOIN emprunt ON emprunt.id_livre = livre.id
                          JOIN abonne ON abonne.id = emprunt.id_abonne
                          WHERE abonne.id = :id 
                          ORDER BY date_emprunt DESC";

            $oSqlStatement = $oDb->prepare($sSqlQuery);
            $oSqlStatement->execute([
                'id' => $_GET['id'],
            ]);
            
            $aRows = $oSqlStatement->fetchAll();
    
            return $aRows;
        }
        else{
            return null;
        }
    }

    public function getLivreByGenre(){

        $oDb = $this->connexion();

        if(isset($_GET['id']) && !is_null($_GET['id']))
        {

            $sSqlQuery = "SELECT genre, count(genre) as nb_genre
                        FROM livre 
                        JOIN emprunt ON emprunt.id_livre = livre.id
                        JOIN abonne ON abonne.id = emprunt.id_abonne
                        WHERE abonne.id = :id
                        GROUP BY genre
                        ORDER BY date_emprunt DESC
                        LIMIT 1";
            $oSqlStatement = $oDb->prepare($sSqlQuery);
            $oSqlStatement->execute([
                'id' => $_GET['id'],
            ]);
            
            $aRows = $oSqlStatement->fetchAll();

            $genre = $aRows[0]['genre'];

            $sSqlQuery = "SELECT titre FROM livre WHERE genre = :genre LIMIT 5";
            $oSqlStatement = $oDb->prepare($sSqlQuery);
            $oSqlStatement->execute([
                'genre' => $genre,
            ]);
            $aRows = $oSqlStatement->fetchAll();

            return $aRows;
        }
        else{
            return null;
        }
    }



    public function updateAbonne()
    {
        if (!empty($_POST)) 
        {

            $oDb = $this->connexion();

            //Si un id existe on va modifier un abonne SINON on l'ajoute
            if($_POST['id'])
            {
                $sSqlQuery = "UPDATE abonne SET ";
            
                foreach($_POST as $key => $value){
                    if($value != ""){
                        $sSqlQuery.= $key ."=". "'". $value."'". ',';
                    }
                    
                }
                //supprimer la dernière virgule
                $lastCommaIndex = strrpos($sSqlQuery, ','); // Trouve l'index de la dernière virgule
                if ($lastCommaIndex !== false) { // Vérifie si une virgule a été trouvée
                    $sSqlQuery = substr($sSqlQuery, 0, $lastCommaIndex) . substr($sSqlQuery, $lastCommaIndex + 1); // Supprime la dernière virgule à l'index trouvé
                }
    
                $sSqlQuery.= " WHERE id = " . $_POST['id'];
            }
            else{
                $sSqlQuery = "INSERT INTO abonne (";
                foreach($_POST as $key => $value){
                    if($value != ""){
                        $sSqlQuery.= $key . ',';
                    }
                }

                $sSqlQuery.= ")";

                $lastCommaIndex = strrpos($sSqlQuery, ','); // Trouve l'index de la dernière virgule
                if ($lastCommaIndex !== false) { // Vérifie si une virgule a été trouvée
                    $sSqlQuery = substr($sSqlQuery, 0, $lastCommaIndex) . substr($sSqlQuery, $lastCommaIndex + 1); // Supprime la dernière virgule à l'index trouvé
                }
                $sSqlQuery.= " VALUES (";

                foreach($_POST as $key => $value){
                    if($value != ""){
                        $sSqlQuery.= "'".$value . "'" . ',';
                    }
                }

                $lastCommaIndex = strrpos($sSqlQuery, ','); // Trouve l'index de la dernière virgule
                if ($lastCommaIndex !== false) { // Vérifie si une virgule a été trouvée
                    $sSqlQuery = substr($sSqlQuery, 0, $lastCommaIndex) . substr($sSqlQuery, $lastCommaIndex + 1); // Supprime la dernière virgule à l'index trouvé
                }

                $sSqlQuery.= ")";
            }

            

            $oSqlStatement = $oDb->prepare($sSqlQuery);
            $oSqlStatement->execute();

        }
      
    }
}