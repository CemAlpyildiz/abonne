<?php 

require_once('autoload.php');
use Livre\Livre;

$oLivre = new Livre();
$aData = $oLivre->getBooks();
$aBooks = $aData['result'];
$sHtmlPagination = $aData['pagination'];

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
            justify-content: space-around;
            list-style-type: none;
        }
    </style>
</head>
<body>
    <h1>Recherche de livre</h1>
    <div class="container-form">
        <form action="index_livre.php?getBooks" method="POST" class="form-example">
            <div class="form-example">
                <label for="titre_livre">Filtre titre : </label>
                <input type="text" name="titre" id="titre_livre">
            </div>

            <div class="form-example">
                <label for="nom_auteur">Filtre auteur : </label>
                <input type="text" name="auteur.nom" id="auteur.nom">
            </div>

            <div class="form-example">
                <label for="nom_editeur">Filtre editeur: </label>
                <input type="text" name="editeur.nom" id="editeur.nom">
            </div>

            <div class="form-example">
                <label for="disponible">Filtre disponible : </label>
                <input type="checkbox" name="disponible" value="disponible" id="disponible">
            </div>
            
            <div class="form-example">
                <input type="submit" value="Filtrer">
            </div>
        </form>
    </div>    

    <div><?php echo $sHtmlPagination; ?></div>

    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Nom auteur</th>
                <th>Nom éditeur</th>
                <th>Dispo</th>
                <th>Date du dernier emprunt</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($aBooks as $book) {?>
            <tr>
                <td><?php echo $book['livre_titre']; ?></td>
                <td><?php echo $book['auteur_nom']; ?></td>
                <td><?php echo $book['editeur_nom']; ?></td>
                <td><?php echo !is_null($book['date_retour']) ? 'Oui' : 'Non' ?></td>
                <td><?php echo $book['date_emprunt'] ?></td>
            </tr> 
            <?php } ?>
        </tbody>
    </table>


    <script
    src="https://code.jquery.com/jquery-3.6.4.min.js"
    integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8="
    crossorigin="anonymous"></script>
</body>
</html>