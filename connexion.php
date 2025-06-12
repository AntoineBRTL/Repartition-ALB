<?php
session_start();
    
if(isset($_SESSION['connecte']) && $_SESSION['connecte'] == TRUE) 
{
    header("Location: accueil.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Connectez vous sur le site de gestion des samedi neige de l'ALB grâce à votre compte ALB. Si vous n'avez pas de compte, demandez à votre administrateur.">
    <link rel="icon" href="./Images/logoSki.ico" type="image/icon">
    <link rel="stylesheet" href="styleconnexion.css">
    <title>Connexion</title>

    <?php include("webappLinks.php"); ?>

    <!-- ION-ICONs -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>
<!-- Header----------------------------------------------------------------------------------------------------- -->
<h1 id="logoALB">
    <img src="../Images/logo.png" alt="logo de l'association">
</h1>
<h3 id="titre">PORTAIL GESTION SAMEDIS NEIGE</h3>

<!-- Content------------------------------------------------------------------------------------------------------ -->
<form action="auth.php" method="post" class="portail">
    <input type="text" placeholder="Identifiant ou adresse e-mail" class="contenu-portail" style="font-size:20px;" name="pseudo" required>
    <hr>
    <input type="password" placeholder="Mot de passe" class="contenu-portail" style="font-size:20px;" name="mot-de-passe" required>
    <hr>

    <?php
    if(isset($_GET["error"])) // Si le mot de passe est incorrect, un message apparait pour signaler l'utilisateur
    {
        echo('<p id="erreur"><ion-icon name="close-outline"></ion-icon> Identifiant ou mot de passe incorrect <ion-icon name="close-outline"></ion-icon></p> ');
    }
    ?>

    <div class="bouton">
        <input type="submit" value="Connexion" id="bouton-style-connexion">
    </div> 
</form>

<div class="info">
    <a href="MotDePasseOublie.php">Mot de passe oublié ?</a>
</div>
<div class="vide"></div> <!-- Permet d'afficher le footer en bas d'une page pour n'immporte quel format et résolution -->

<!-- Footer------------------------------------------------------------------------------------------------------ -->
<footer class="footer">
    <p>&copy; <script>document.write(new Date().getFullYear())</script>, ALB Billère</p>
    <p id="footerLogo"><img id="logo" src="../Images/logoSki.png" alt="logo sortie samedi neige"></p>
</footer>

</body>
</html>