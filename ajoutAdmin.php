<?php
    session_start();

    if (!isset($_SESSION['connecte']) || $_SESSION['connecte'] !== true) {
        header("Location: connexion.php");
        exit;
    }

    // Vous devez etre admin pour acceder a cette page
    if ($_SESSION['grade'] !== 'admin') {
        header("Location: connexion.php");
        exit;
    }

//Vérification si un mdp temporaire est actif si oui on oblige l'utilisateur à le changer
if($_SESSION['mdp_tmp'] != "vide") 
{
    header("Location: changerMDP_temp.php");
    exit;
}

    $erreur_mail = "";
    $erreur_mdp = "";
    $nom = "";
    $prenom = "";
    $pseudo = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $mail = $_POST['mail'] ?? '';
        $pseudo = $_POST['pseudo'] ?? '';
        $mdp = $_POST['mot-de-passe'] ?? '';
        $confirme = $_POST['confirmation-mot-de-passe'] ?? '';

        $fichier = 'admin.csv';
        $lignes = [];

        if (($handle = fopen($fichier, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ";", '"', "\\")) !== false) {
                if ($mail == $data[2]) {
                    $erreur_mail = "L'adresse mail est déjà inscrite.";
                }
            }
            fclose($handle); 
        }       
        if ($mdp != $confirme) {
            $erreur_mdp = "La confirmation du mot de passe est incorrecte.";
        }
        if ((empty($erreur_mail)) && (empty($erreur_mdp))) {
            $_SESSION['form_inscription'] = $_POST;
            header('Location: enregistrerAdmin.php');
            exit;
        }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Ajouter facilement de nouveaux utilisateurs sur le site pour permettre au plus grand nombre d'organiser les sorties samedi neige.">
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
        <div class="accueil">  
            <h2 class="titre1">Inscription sur le portail</h2>
        </div>

        <hr id="redline-mdp">

            <form action="" method="POST" class="portail">
                <!-- ajout d'une balise de paragraphe si une erreur sur le mail est détectée  -->
                <?php if (!empty($erreur_mail)): ?>
                    <p style="margin-top: 10px; text-align: center; color: red; font-size: 20px; font-family: helvetica;">
                        <?= $erreur_mail ?>
                    </p>
                <?php endif; ?>
                <!-- ajout d'une balise de paragraphe si une erreur sur la confirmation du mot de passe et/ou du mot de passe est détectée  -->
                <?php if (!empty($erreur_mdp)): ?>
                    <p style="margin-top: 10px; text-align: center; color: orange; font-size: 20px; font-family: helvetica;">
                        <?= $erreur_mdp ?>
                    </p>
                <?php endif; ?>
                <input type="text" placeholder="Nom" class="contenu-portail" name="nom" value="<?= htmlspecialchars($nom) ?>" required>
                <hr>
                <input type="text" placeholder="Prenom" class="contenu-portail" name="prenom" value="<?= htmlspecialchars($prenom) ?>" required>
                <hr>
                <input type="email" placeholder="Adresse e-mail" class="contenu-portail" name="mail" required>
                <hr>
                <input type="text" placeholder="Identifiant" class="contenu-portail" name="pseudo" value="<?= htmlspecialchars($pseudo) ?>" required>
                <hr>
                <input type="password" placeholder="Mot de passe" class="contenu-portail" name="mot-de-passe" required>
                <hr>
                <input type="password" placeholder="Confirmation mot de passe" class="contenu-portail" name="confirmation-mot-de-passe" required>
                <hr>
                <select name="grade" class="selection-administration">
                    <option value="admin">administrateur</option>
                    <option value="sous-admin">sous-administrateur</option>
                </select>
                <hr>
                <div class="bouton"><input type="submit" value="Inscription" id="bouton-style-connexion"></div>
            </form>
    </div>

    <!-- Footer------------------------------------------------------------------------------------------------------ -->
    <?php require('footer.php'); ?>
</div>

</body>
</html>