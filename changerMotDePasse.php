<?php
session_start();


//Vérification si un mdp temporaire est actif si oui on oblige l'utilisateur à le changer
if($_SESSION['mdp_tmp'] != "vide") 
{
    header("Location: changerMDP_temp.php");
    exit;
}

    
if (isset($_POST['mot-de-passe']) && isset($_POST['ancien-mot-de-passe'])) {
    $nouveau_mdp = $_POST['mot-de-passe'];
    $ancien_mdp = $_POST['ancien-mot-de-passe'];
    $confirme_mdp = $_POST['confirmation-mot-de-passe'];
    $pseudo_session = $_SESSION['pseudo'];

    $fichier = 'admin.csv';
    $lignes = [];

    if (($handle = fopen($fichier, 'r')) !== false) {
        while (($data = fgetcsv($handle, 1000, ";", '"', "\\")) !== false) {
            if ($data[3] == $pseudo_session) {
                if ($ancien_mdp != $data[4]) {
                    $ancien_mdp_faux = "L'ancien mot de passe est incorrect.";
                } elseif ($confirme_mdp != $nouveau_mdp) {
                    $confirme_mdp_faux = 'La confirmation du mot de passe est incorrecte.';
                } else {
                    if ($nouveau_mdp != $data[4]) {
                        $data[4] = $nouveau_mdp;              
                        $mot_de_passe_change = 'Mot de passe changé avec succès !';
                    } else {
                        $mdp_deja_utilise = "Le nouveau mot de passe est identique à l'ancien.";
                    }
                }
            }
            $lignes[] = $data;
        }
        fclose($handle);
    }

    // Réécriture du fichier CSV
    if (($handle = fopen($fichier, 'w')) !== false) {
        foreach ($lignes as $ligne) {
            fputcsv($handle, $ligne, ";", '"', "\\");
        }
        fclose($handle);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Changer votre mot de passe rapidement sur cette page en cas d'oublie. Attention ! Votre ancien mot de passe sera demandé.">
    <title>Changer Mot De Passe</title>

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
            <h2 class="titre1">Changement de mot de passe</h2>
        </div>

        <hr id="redline-mdp">


        <form action="./changerMotDePasse.php" method="post" class="portail">
            <input type="password" placeholder="Ancien Mot De Passe" class="contenu-portail" style="font-size:20px;" name="ancien-mot-de-passe" required>
            <hr>
            <input type="password" placeholder="Nouveau Mot De Passe" class="contenu-portail" style="font-size:20px;" name="mot-de-passe" required>
            <hr>
            <input type="password" placeholder="Confirmation Mot De Passe" class="contenu-portail" style="font-size:20px;" name="confirmation-mot-de-passe" required>
            <hr>
            <!-- ajout d'une balise de paragraphe si une des 4 erreurs possibles dans le php est détectée  -->
            <?php if (!empty($ancien_mdp_faux)): ?>
                    <p style="margin-top: 10px; text-align: center; color: red; font-size: 20px; font-family: helvetica;">
                        <?= $ancien_mdp_faux ?>
                    </p>
            <?php endif; ?>
            <?php if (!empty($confirme_mdp_faux)): ?>
                <p style="margin-top: 10px; text-align: center; color: orange; font-size: 20px; font-family: helvetica;">
                    <?= $confirme_mdp_faux ?>
                </p>
            <?php endif; ?>
            <?php if (!empty($mot_de_passe_change)): ?>
                <p style="margin-top: 10px; text-align: center; color: green; font-size: 20px; font-family: helvetica;">
                    <?= $mot_de_passe_change ?>
                </p>
            <?php endif; ?>
            <?php if (!empty($mdp_deja_utilise)): ?>
                <p style="margin-top: 10px; text-align: center; color: red; font-size: 20px; font-family: helvetica;">
                    <?= $mdp_deja_utilise ?>
                </p>
            <?php endif; ?>
            <div class="bouton"><input type="submit" value="Enregistrer" id="bouton-style-connexion"></div>
        </form>
    </div>

    <!-- Footer------------------------------------------------------------------------------------------------------ -->
    <?php require('footer.php'); ?>
</div>

</body>
</html>