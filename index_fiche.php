<?php 

require_once('autoload.php');
use Fiche\Fiche;

$oFiche = new Fiche();

$oAbonneFiche = $oFiche->getAbonneFiche();

$oFiche->updateAbonne();

$oAbonneEmprunt = $oFiche->getAbonneEmprunt();

$aLivreByGenre = $oFiche->getLivreByGenre();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Document</title>
    <style>
        table, tr, td, th{
            border: 1px solid black;
        }

        .container-form{
            padding-bottom: 20px;
        }

        .pagination{
            display: flex;
        }
        li{
            padding-left:10px;
            list-style-type: none;
        }
    </style>
</head>
<body>
    <h1>Rechercher la fiche d'un abonnée</h1>
    
    <form action="index_fiche.php?getAbonneFiche" method="GET" class="form-example">
        <div class="form-example">
            <label for="nom">ID: </label>
            <input type="number" name="id">
        </div>

        
        <div class="form-example">
            <input type="submit" value="Rechercher">
        </div>
    </form>
    <br>
    

    <h1>Enregistrer un nouveau abonne</h1>
    
    <form action="index_fiche.php?getAbonneFiche" method="POST" class="form-example">
        <div class="form-example">

            <label for="nom">ID : </label>
            <input type="number" name="id">
            
            <label for="nom">Nom : </label>
            <input type="text" name="nom">
            
            <label for="prenom">Prénom : </label>
            <input type="text" name="prenom">
    
            <label for="date_naissance">Date naissance : </label>
            <input type="date" name="date_naissance">
          
            <label for="adresse">Adresse : </label>
            <input type="text" name="adresse">

            <label for="code_postal">Code Postal : </label>
            <input type="number" name="code_postal">

            <label for="ville">Ville : </label>
            <input type="text" name="ville">

            <label for="date_inscription">Date inscription : </label>
            <input type="date" name="date_inscription">

            <label for="date_fin_abo">Date fin abo : </label>
            <input type="date" name="date_fin_abo">
            
            <div><input type="submit" value="Filtrer"></div>
            
        </div>
    </form>

    <br>
    <br>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Date de naissance</th>
                <th>Adresse</th>
                <th>Code Postal</th>
                <th>Ville</th>
                <th>Date inscription</th>
                <th>Date fin abo</th>
            </tr>
        </thead>
        <tbody>
        <?php 

        if(isset($_GET['id']) && !is_null($_GET['id'])){
            foreach ($oAbonneFiche as $abonne) {?>
                <tr>
                    <td><?php echo $abonne['id']; ?></td>
                    <td><?php echo $abonne['nom']; ?></td>
                    <td><?php echo $abonne['prenom']; ?></td>
                    <td><?php echo $abonne['date_naissance']; ?></td>
                    <td><?php echo $abonne['adresse']; ?></td>
                    <td><?php echo $abonne['code_postal']; ?></td>
                    <td><?php echo $abonne['ville']; ?></td>
                    <td><?php echo $abonne['date_inscription']; ?></td>
                    <td><?php echo $abonne['date_fin_abo']; ?></td>
                </tr> 
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>

    <?php if(!is_null($oAbonneEmprunt)){ ?>
        <h1>Liste des livres empruntés</h1>
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Date emprunt</th>
                </tr>
            </thead>
            <tbody>
        <?php 

        if(isset($_GET['id']) && !is_null($_GET['id'])){
            
            foreach ($oAbonneEmprunt as $emprunt) {?>
                <tr>
                    <td><?php echo $emprunt['titre']; ?></td>
                    <td><?php echo $emprunt['date_emprunt']; ?></td>
                </tr> 
                <?php } ?>
            <?php } ?>
        </tbody>
        </table>
    <?php } ?>

    <br><br>

    <?php if(!is_null($oAbonneEmprunt)){ ?>
        <h1>Genre le plus emprunté</h1>
       

       <?php if(isset($_GET['id']) && !is_null($_GET['id'])){ ?>
            <ul>
            <?php foreach($aLivreByGenre as $livre){ ?>
                <li><?php echo $livre['titre'] ?></li>
            <?php } ?>
            </ul>
        <?php } ?>
            
       
    <?php } ?>
    
    


    <script
    src="https://code.jquery.com/jquery-3.6.4.min.js"
    integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8="
    crossorigin="anonymous"></script>
</body>
</html>