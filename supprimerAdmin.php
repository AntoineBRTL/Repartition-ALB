<?php

session_start();
    
if(!isset($_SESSION['connecte']) || $_SESSION['connecte'] != TRUE) 
{
    header("Location: connexion.php");
    exit;
}

// Vous devez etre admin pour acceder a cette page
if ($_SESSION['grade'] !== 'admin') {
    header("Location: connexion.php");
    exit;
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Félicitation (ou pas...) ! L'utilisateur que vous avez choisi de supprimer, l'a bien été.">
    <title>Suppression d'un utilisateur</title>

    <link rel="icon" href="./Images/logoSki.ico" type="image/icon">
    <link rel="stylesheet" href="accueilStyle.css">

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <?php include("webappLinks.php"); ?>
</head>
<body>
<!-- Header------------------------------------------------------------------------------------------------------ -->
<?php require('header.php'); ?>

<!-- Content------------------------------------------------------------------------------------------------------ -->
<div class="page-dimension">
    <div class="contenu-principal">
        <div class="accueil">  
            <h2 class="titre1">Gestion des utilisateurs</h2>
        </div>

        <hr id="redline-mdp">

        <?php

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $Nom = $_POST['Nom'];
            $Prenom = $_POST['Prenom'];
        }

        $fichier = 'admin.csv';
        $temporaire = 'temporaire_admin.csv'; // création d'un fichier temporaire admin pour éviter les conflits dans le fichier original
        $tmp = fopen($temporaire,'w');

        if (($handle = fopen($fichier, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ";", '"', "\\")) !== false) {
                
                if (($data[0] !== $Nom) && ($data[1] !== $Prenom)) {
                    fputcsv($tmp, $data, ";", '"', "\\");
                }   
            }
        }

        fclose($handle);
        fclose($tmp);

        if (rename($temporaire,$fichier)){  // confirmation de la suppression pour informer l'utilisateur
            echo "<p class ='texte_aff' style='font-size: x-large;'>L'utilisateur a bien été supprimé !</p>";
            ?><script src="./confetti.js"></script><?php

        }
        else {
            echo "<p class ='texte_aff'> Erreur </p>"; // En cas d'erreur dans la modification du fichier csv
            unlink($temporaire);
        }

        if (($_SESSION["nom"] == $Nom) && ($_SESSION["prenom"] == $Prenom)){
            $_SESSION['connecte'] = FALSE;

        }

        ?>

        <div class = "bouton-position-supprimer-2">
            <a class="bouton-style" href="gestionAdmin.php">Retour</a>
        </div>   
        <div class = "bouton-position-supprimer-2">
            <a class="bouton-style" href="./accueil.php">Accueil</a>
        </div>
    </div>


    <!-- Footer------------------------------------------------------------------------------------------------------ -->
    <?php require('footer.php'); ?>
</div>

</body>
</html>