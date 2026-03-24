<?php
    if ( (empty($_POST['prenom'])) || (empty($_POST['age'])) ) {
        header('Location: form2.php');
    }
    $prenom = $_POST['prenom'];
    $age = $_POST['age'];
    $age_verif = filter_var( $age , FILTER_VALIDATE_INT);
    if($age_verif==null) {
                        echo "champ age non valide";
                        exit;
                        }

     $prenom_nettoye =  filter_var( $prenom ,  FILTER_SANITIZE_SPECIAL_CHARS);
    
?>
 <html>
  <body>
  <h1> reponse </h1>
 <?php
    echo '<p>Votre prénom : '.$prenom_nettoye.'</p>'."\n";
    echo '<p>Votre âge : '.$age_verif.'</p>'."\n";
?>
 </body>
 </html>