<?php 

require_once('autoload.php');
use Abonne\Abonne;

$oAbonne = new Abonne();
$aData = $oAbonne->getAbonnes();
$aAbonnes = $aData['result'];
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
        }
        li{
            padding-left:10px;
            list-style-type: none;
        }
    </style>
</head>
<body>
    <h1>Recherche d'abonnée</h1>
    <div class="container-form">
        
    </div>    

    <div><?php echo $sHtmlPagination; ?></div>

    <form action="index_abonne.php" method="GET" class="form-example">
            <div class="form-example">
                <label for="nom">Filtre nom : </label>
                <input type="text" name="nom">
            </div>

            <div class="form-example">
                <label for="prenom">Filtre prénom : </label>
                <input type="text" name="prenom">
            </div>

            <div class="form-example">
                <label for="ville">Filtre ville: </label>
                <input type="text" name="ville">
            </div>

            <div class="form-example">
                <label for="abonnement">Filtre abonnement : </label>
                <input type="checkbox" name="status" value="1">
            </div>
            
            <div class="form-example">
                <input type="submit" value="Filtrer">
            </div>
        </form>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Ville</th>
                <th>Date de naissance</th>
                <th>Date fin abo</th>
            </tr>
        </thead>
        <tbody>
        <?php 
            foreach ($aAbonnes as $abonne) {?>
            <tr>
                <td><?php echo $abonne['nom']; ?></td>
                <td><?php echo $abonne['prenom']; ?></td>
                <td><?php echo $abonne['ville']; ?></td>
                <td><?php echo $abonne['date_naissance']; ?></td>
                <td><?php echo $abonne['date_fin_abo']; ?></td>
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