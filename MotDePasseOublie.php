<?php
session_start();


$confirmation_mail = $_SESSION['confirmation'] ?? ''; //Vérification si confirmation n'est nulle sinon lui affecte la chaine vide
$erreur_mail = $_SESSION['erreur'] ?? '';
unset($_SESSION['confirmation'],$_SESSION['erreur']); //Vide les variable de session 


//Verification si l'utilisateur est connecte si non retourne vers page connexion
if(isset($_SESSION['connecte']) && $_SESSION['connecte'] == TRUE) 
{
    header("Location: accueil.php");
    exit;
}

//Import des classes depuis PHPmailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;




if (isset($_POST['email'])) {
    
    //Recupération de l'adresse mail
    $email = $_POST['email'];
    
   //Fichiers disponibles
    $fichier = './admin.csv';
    $tempFile = './admin_temp.csv';

    //Ouverture du fichier
    $lignes = file($fichier);
    $fp = fopen($fichier, 'w');
    
    //Création des messages pour l'affichage
    $erreur_mail = "";
    $confirmation_mail = "";

    $est_envoye = FALSE; //Bouleen qui indique l'état d'envoi

    foreach ($lignes as $ligne) {
        $data = str_getcsv($ligne, ";", '"', "\\");

        if (isset($data[2]) && trim($data[2]) == $email) {
            
            
            //Création d'un nouveau mdp temporaire-----------------------------------------------------------------------------------------------------
            $nouveau_MDP = bin2hex(random_bytes(4)); 
            $data[6] = $nouveau_MDP; 
            
            //Envoi de ce mdp par mail à l'utilisateur-------------------------------------------------------------------------------------------------

            require 'PHPmailer/PHPMailer.php';
            require 'PHPmailer/SMTP.php';
            require 'PHPmailer/Exception.php';

            //Création d'un mail
            $mail = new PHPMailer(true);

            try {
                //Paramétrage du mail
                $mail -> isSMTP();
                $mail -> Host = 'smtp.gmail.com';
                $mail -> SMTPAuth = true;
                $mail -> Username = 'ne.pas.repondre.alb64@gmail.com'; //Adresse Gmail du compte google
                $mail -> Password = 'fsle kzrz osyv ojtv'; //MDP de l'application du compte google
                $mail -> SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail -> Port = 587;
                $mail -> CharSet = 'UTF-8';

                //Expéditeur + destinataire
                $mail -> setFrom('ne.pas.repondre.alb64@gmail.com', 'ALB');
                $mail -> addAddress($data[2], $data[3]);

                //Création du contenue du mail
                $mail -> isHTML(true);
                $mail -> Subject = 'Réinitialisation de votre mot de passe';
                $mail -> Body = 'Bonjour,<br><br>Voici votre nouveau code temporaire: <b>'.$nouveau_MDP .'</b><br><br>Ce code est à usage unique, veuillez modifier votre mot passe dès que vous vous connectez. <b>Ne le partagez avec personne.</b><br>';
                $mail -> Body .= 'Ce code sera considéré comme votre ancien mot de passe lors de son changement sur le site.<br>';
                $mail -> Body .= '<b>FAITES LE CHANGEMENT DE MOT DE PASSE IMMÉDIATEMENT APRÈS AVOIR REÇU CE MAIL !</b><br><br><br>';
                $mail -> Body .= "Merci,<br><b>L'équipe de l'Amicale Laïque de Billère</b><br>2 rue du Tourmalet<br>64140 BILLÈRE";
                $mail -> AltBody = 'Bonjour, Voici votre le nouveau code temporaire: '.$nouveau_MDP;

                $mail -> send(); //Envoi

                //Création d'un message pour valider l'envoi
                $confirmation_mail = 'Le message a bien été envoyé.';
                $_SESSION['confirmation'] = $confirmation_mail;
                $est_envoye = TRUE;

            } catch (Exception $e) { //Si un problème survient
                $erreur_mail = "Mail invalide. Veuillez entrer le mail associé au compte.";
                $_SESSION['erreur'] = $erreur_mail;
            } 
        }


        

        fputcsv($fp, $data, ";", '"', "\\");
    }

    //Création d'un message d'erreur si le mail est invalide
    if (!$est_envoye){
        $erreur_mail = "Mail invalide. Veuillez entrer le mail associé au compte.";
        $_SESSION['erreur'] = $erreur_mail;
    }


fclose(stream: $fp);
if ($est_envoye){
    header("Location: " . $_SERVER['PHP_SELF']); //Renvoie vers le php actuel
    exit;
}

}
?>




<!-- HTML----------------------------------------------------------------------------------------------------- -->



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Mince ! Vous ne trouvez plus votre mot de passe ? Pas de panique, cette page de réinitialisation est là pour vous. Entrez uniquement votre mail de compte et vous recevrez un code temporaire unique.">
    <link rel="icon" href="./Images/logoSki.ico" type="image/icon">
    <link rel="stylesheet" href="styleconnexion.css">
    <title>Réinitialisation Mot de Passe</title>

    <?php include("webappLinks.php"); ?>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>
<!-- Header----------------------------------------------------------------------------------------------------- -->
<h1 id="logoALB"><img src="../Images/logo.png" alt="logo de l'association"></h1>
<h3 id="titre">REINITIALISATION DU MOT DE PASSE</h3>

<!-- Content----------------------------------------------------------------------------------------------------- -->
<form action="" method="post" class="portail">
    <fieldset id="contour-form">
        <input type="text" placeholder="adresse e-mail de récupération" class="contenu-portail" style="font-size:20px;" name="email" required>
        <hr>
        <?php if (!empty($erreur_mail)): ?>
            <p style="margin-top: 10px; text-align: center; color: red; font-size: 20px; font-family: helvetica;">
                <?= $erreur_mail ?>
            </p>
        <?php endif; ?>
        <div class="bouton"><input type="submit" value="Réinitialiser" id="bouton-style-connexion"></div> 
    </fieldset>
</form>
<!-- Si le mail de récupération ne correspond pas au mail inscrit, création d'une balise paragraphe qui renvoie l'erreur -->
<?php if (!empty($confirmation_mail)): ?>
    <p style="margin-top: 10px; text-align: center; color: green; font-size: 17px; font-family: helvetica;">
        <?= $confirmation_mail ?>
    </p>
<?php endif; ?>
<div class="info" style="padding-top: 4%; font-size: x-large;"><a href="connexion.php">Retour <<<</a></div>

</body>
</html>