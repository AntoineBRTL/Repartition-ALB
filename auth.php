<?php
    session_start();

    if (($handle = fopen("admin.csv", "r")) !== false) {
        while (($data = fgetcsv($handle, 1000, ";", '"', "\\")) !== false) {
            // $data est un tableau : [0] => Nom, [1] => Prénom, [2] => Email, etc.
            $pseudo = $data[3];          // Exemple : "Antoine"
            $motdepasse = $data[4];     // Exemple : "1234"
            $mail = $data[2];          // Exemple : "mrarxwin7@gmail.com"
            $mdp_tmp = $data[6];

            if(($pseudo == $_POST["pseudo"] || $mail == $_POST["pseudo"]) && ($motdepasse == $_POST["mot-de-passe"] || $mdp_tmp == $_POST["mot-de-passe"])) // Vérification dans le fichier admin.csv du mot de passe et du mail/pseudo rentré 
            {
                $_SESSION['connecte'] = TRUE;
                $_SESSION['nom'] = $data[0];
                $_SESSION['prenom'] = $data[1];
                $_SESSION['pseudo'] = $data[3];
                $_SESSION['photo-profil'] = $data[5];
                $_SESSION['mdp_tmp'] = $data[6];
                $_SESSION['grade'] = $data[7];
            }
        }
        fclose($handle);
    }

    if(!isset($_SESSION['connecte']) || $_SESSION['connecte'] != TRUE)  // En cas de non-correspondance des éléments du fichier csv
    {
        header("Location: connexion.php?error=wrong_login");
        exit;
    }
    
    header("Location: accueil.php");
    exit;
?>