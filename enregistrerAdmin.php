<?php
    session_start();

    if(!isset($_SESSION['connecte']) || $_SESSION['connecte'] != TRUE) 
    {
        header("Location: connexion.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Félicitation ! Vous êtes officiellement administrateur des sorties samedi neige de l'ALB !">
    <title>Inscription</title>

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
        <?php

        $fichier = './admin.csv';
        $fp = fopen($fichier, 'a');

        $pdp = "profil.png";
        $mdp_tmp = "vide"; //Variable qui va stocker le mdp temporaire recu par mail

        if (!isset($_SESSION['form_inscription'])) {
            header("Location: ajoutAdmin.php");
            exit;
        }

        $form = $_SESSION['form_inscription'];

        $monEleve = array(
            array(
                $form["nom"],
                $form["prenom"],
                $form["mail"],
                $form["pseudo"],
                $form["mot-de-passe"],
                $pdp,
                $mdp_tmp,
                $form['grade']
            )
        );


        foreach ($monEleve as $fields) {

            fputcsv($fp, $fields, ";", '"', "\\");
        }

        fclose($fp);
        ?>

        <!-- S'affiche si l'inscription c'est bien déroulée : Permet d'informer l'utilisateur de son inscription -->
        <div class="contenu-centre">
            <h4 class="texte_aff" style="font-size: xx-large;"> Inscription terminée !</h1>
            
            <p class="texte_aff"><?php echo htmlspecialchars($form["prenom"]) . " " ?> peut maintenant <span id="surlignage">se connecter</span>.</p>
            <script src="./confetti.js"></script>
            
            <div class = "bouton-position">
            <p><a href="accueil.php"><input type="button" value="Retour" class="bouton-style"></a></p>
            </div>

        </div>
    </div>

    <!-- Footer------------------------------------------------------------------------------------------------------ -->
    <?php require('footer.php'); ?>
</div>

</body>
</html>