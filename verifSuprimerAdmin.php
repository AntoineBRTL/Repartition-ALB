<?php

session_start();
    
if(!isset($_SESSION['connecte']) || $_SESSION['connecte'] != TRUE) 
{
    header("Location: connexion.php");
    exit;
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Verifier bien que vous avez choisi la bonne personne... Cela pourrait être une erreur. Cette page est une double vérification de suppression du profil. En cas d'étourderie...">
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
            <h2 class="titre1">Suppression d'un utilisateur</h2>
        </div>

        <hr id="redline-mdp">
        <?php

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $Nom = $_POST['Nom'];
            $Prenom = $_POST['Prenom'];


            echo "<p class ='texte_aff'>Voulez vous vraiment supprimer cet utilisateur ?</p> <br>";
        
        
            echo "<table class='tableauAdminRecap' border='1' cellpadding='8' cellspacing='0'>";   

            // Création du tableau du profil à supprimer --------------------------------
            echo "<tr class='donnees_tab_et'>";
            echo "<th class='donnees_tab_et'>"."Nom"."</th>";
            echo "<th class='donnees_tab_et'>"."Prenom"."</th>";
            echo "</tr>";

            echo "<tr class='donnees_tab'>";
            echo "<td class='donnees_tab'>".$Nom."</td>";
            echo "<td class='donnees_tab'>".$Prenom."</td>";
            echo "</tr>";
        
            echo "</table>";
            // ---------------------------------------------------------------------------

            echo "<br> <p class ='texte_aff'>CETTE SUPPRESSION EST DEFINITIVE !</p>"; // Préviens l'utilisateur de la fatalité de l'action (en cas de mauvaise manipulation)

            echo "<form action='./supprimerAdmin.php' method='post'>";

            echo "<input type='hidden' name='Nom' value='".$Nom."'>";
            echo "<input type='hidden' name='Prenom' value='".$Prenom."'>";

        }
        ?>

        <link rel="stylesheet" href="accueilStyle.css">




        <div class = "bouton-position-supprimer">
                                
            <input class="bouton-style-supprimer" type="submit" value="Supprimer l'admin">
        </div>
        </form>
        <div class = "bouton-position-supprimer">

            <a class="bouton-style" href="gestionAdmin.php">Retour</a>
        
        </div>
    </div>

    <!-- Footer------------------------------------------------------------------------------------------------------ -->
    <?php require('footer.php'); ?>
</div>

</body>
</html>