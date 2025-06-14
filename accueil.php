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



// Trier fichiers par ordre decroissant de date
$repertoire = "sorties/";
$mois = [
    "janvier" => "January", "février" => "February", "mars" => "March",
    "avril" => "April", "mai" => "May", "juin" => "June",
    "juillet" => "July", "août" => "August", "septembre" => "September",
    "octobre" => "October", "novembre" => "November", "décembre" => "December"
];

$fichiers = [];

foreach (glob($repertoire . "*.csv") as $filepath) 
{
    $file = basename($filepath);
    $nom = pathinfo($file, PATHINFO_FILENAME);
    $nom_en = str_ireplace(array_keys($mois), array_values($mois), $nom);
    $timestamp = strtotime($nom_en);
    if ($timestamp) 
    {
        $fichiers[$file] = $timestamp;
    }
}

arsort($fichiers); // Tri décroissant

// recuperer info sur la derniere sortie (fichier le plus recent)
$dernierFichier = array_key_first($fichiers);
$chemin = $repertoire . $dernierFichier;

$lignes = file($chemin, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$nb_total = count($lignes) - 1;
$nb_encadrants = 0;

if (($handle = fopen($chemin, "r")) !== false) 
{
    $headers = fgetcsv($handle, 0, ";",'"',"\\");

    while (($data = fgetcsv($handle, 0, ";",'"',"\\")) !== false) {
        
        $niveau_index = array_search("Niveau", $headers);
        if (strtolower(trim($data[$niveau_index])) == "encadrant") {
            $nb_encadrants++;
        }
    }
    fclose($handle);
}

$nb_jeunes = $nb_total - $nb_encadrants;
$heure = date("H");
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="accueilStyle.css">


            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="description" content="Découvrez l'espace de gestion du l'association ALB avec son système de tri automatique des tableaux excels à l'aide d'un simple clic.">
            <link rel="icon" href="./Images/logoSki.ico" type="image/icon">
            <link rel="stylesheet" href="styleDropZone.css">
            
            
            <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
            <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
        <title>Accueil</title>

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        <?php include("webappLinks.php"); ?>

    </head>
    <body>

    <!-- Header------------------------------------------------------------------------------------------------------ -->
    <?php include('header.php'); ?>

    <!-- Content----------------------------------------------------------------------------------------------------- -->
    <div class="page-dimension">
        <div class="contenu-principal">
            <div class="accueil"> 
                <?php if($heure >= 6 && $heure < 18): ?>
                <h2 class="titre1"> Bonjour, <?php echo $_SESSION['prenom']?> !</h2>
                <?php else: ?>
                <h2 class="titre1"> Bonsoir, <?php echo $_SESSION['prenom']?> !</h2>
                <?php endif; ?>
            </div>

            <div class="contenu-accueil">
                <div class="depot">
                    <div class="aff_un_post">
                        
                        <h2 class="titre2"> Depot d'un nouveau fichier </h2>
                        
                    </div>

                    <!-- Affichage et gestion du drag-n-drop ------------------------------------------------------------------------------------------------------ -->

                    <div class="drag-n-drop"> 
                        <form action="./repartition.php" method="post" enctype="multipart/form-data">
                            <section id="drag-n-drop">
                                <input type="hidden" name="f_excel_text" id="input-hidden">
                                <input type="file" name="f_excel" id="input-file" accept=".csv" style="display: none;">
                                
                                <div id="drop-zone">
                                    <ion-icon name="download-outline" id="icon-file-drop"></ion-icon>
                                    <pre id="info-drop-zone">Veuillez déposer un fichier .csv</pre>
                                </div>
                        
                            </section>

                            <p class="info">Prenez le temps de vérifier que le fichier déposé soit celui de assoConnect.com</p>

                            <div class="boutons-drag-n-drop">
                                <div class="contenu-date-sortie">
                                    <!-- <span for="date-sortie">Date de la sortie</span> -->
                                    <input type="date" name="date-sortie" id="" required>
                                </div>
                                

                                <div class = "bouton-position-envoi">
                                    <button class="bouton-style-envoi" id="bouton-envoi" type="submit" disabled>
                                        <p>Envoyer</p>
                                        <ion-icon name="paper-plane-outline"></ion-icon>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <script src="./dragAndDrop.js"></script>

                </div>

                <div class="historique">
                    

                    <div class="info-derniere-sortie">
                        <h2> Dernière sortie : <span class="derniere-sortie"><?php echo(pathinfo(array_key_first($fichiers), PATHINFO_FILENAME)); ?></span></h2>
                        <?php
                            echo "<p>Nombre de participants : ".$nb_total." </p>";
                            echo "<p>Nombre d'encadrants : ".$nb_encadrants." </p>";
                            echo "<p>Nombre de jeunes : ".$nb_jeunes." </p>";
                        ?>
                    </div>

                    <h2 class="titre2"> Sorties précédentes </h2>
                    <?php
                    
                    if(sizeof($fichiers) == 1)
                    {
                        echo '<pre class="info">Créez une seconde sortie</pre>';
                    }
                    
                    ?>
                    <div class="scroll-historique">
                        <?php

                            echo "<ul>";
                            foreach ($fichiers as $file => $time) {
                                $nom = pathinfo($file, PATHINFO_FILENAME);
                                echo "<li class='tag-sortie no-select'>
                                        <ion-icon class='tag-sortie-suppr' name='remove-outline'></ion-icon>
                                        <div data-lien='" . $file . "' target='_blank'>" . $nom . "</div>
                                    </li>";
                            }
                            echo "</ul>";
                        ?>
                    </div>
                </div>
            </div>
        </div>                  
            
        <!-- Footer------------------------------------------------------------------------------------------------------ -->
        <?php include('footer.php');?>
    </div>      

    <script src="./app.js"></script>
    
    </body>
</html>
