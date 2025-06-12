<?php
session_start();
    
if(!isset($_SESSION['connecte']) || $_SESSION['connecte'] != TRUE) 
{
    header("Location: connexion.php");
    exit;
}

//Vérification si un mdp temporaire est actif si oui on oblige l'utilisateur à le changer
if($_SESSION['mdp_tmp'] != "vide") 
{
    header("Location: changerMDP_temp.php");
    exit;
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Supprimez et surveillez les administrateurs du site en un clic.">
    <title>Gestion des utilisateurs</title>

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
        echo "<div class='tableau-admin-container'>";
        echo "<table class='tableauAdmin' border='1' cellpadding='8' cellspacing='0'>";   // Création du tableau 


        // Affichage des en-têtes ----------------------------------------------------------------
        echo "<tr class='donnees_tab'>";
            
        echo "<th class='donnees_tab_et'>"."Nom"."</th>";
        echo "<th class='donnees_tab_et'>"."Prenom"."</th>";
        echo "<th class='donnees_tab_et'>"."Mail"."</th>";
        echo "<th class='donnees_tab_et'>"."Pseudo"."</th>";
        echo "<th class='donnees_tab_et'>"."Profil"."</th>";
        echo "<th class='donnees_tab_et'>"."Grade"."</th>";
        echo "<th class='tab_bouton'>"." "."</th>";

        echo "</tr>";
        //-----------------------------------------------------------------------------------------


        $fichier = 'admin.csv';

        if (($handle = fopen($fichier, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ";", '"', "\\")) !== false) {
                
                
                
                
                // Affichage des lignes 
                echo "<tr class='donnees_tab'>";
            
                echo "<form action='./verifSuprimerAdmin.php' method='post'>";
            
                echo "<td class='donnees_tab' >".$data[0]."</td>";
                echo "<td class='donnees_tab' >".$data[1]."</td>";
                echo "<td class='donnees_tab' >".$data[2]."</td>";
                echo "<td class='donnees_tab' >".$data[3]."</td>";
                echo "<td class='donnees_tab' ><img id='image-profil' src='images_profil/".$data[5]."'></td>";
                echo "<td class='donnees_tab' >".$data[7]."</td>";

                echo "<input type='hidden' name='Nom' value='".$data[0]."'>";
                echo "<input type='hidden' name='Prenom' value='".$data[1]."'>";


                echo "<td class='tab_bouton'>";
        ?>

                <div class = "bouton-position-supprimer">
                                
                <input class="bouton-style-supprimer" type="submit" value="Supprimer">
                            
                </div>

        <?php
            echo "</td>";

            echo "</form>";
            echo "</tr>";
        }
            
        echo "</table>";
        echo "</div>";

        fclose($handle);
        }
        ?>

        <link rel="stylesheet" href="accueilStyle.css">

    </div>


    <!-- Footer------------------------------------------------------------------------------------------------------ -->
    <?php require('footer.php'); ?>
</div>


</body>
</html>


























