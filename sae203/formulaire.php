<html>
    <head>
        <title>Formulaire</title>
        <style type="text/css">
           
            span {
                background-color: red;
            }
        </style>
    </head>
    <body>
        <form action="traitement.php" method="post">
            
            <p>
                <label for="prenom">Prénom :</label>
                <input type="text" name="prenom" id="prenom" />
            </p>
            <p> 
                <label for="age">age :</label>
                <input type="number" name="age" id="age" />
                <?php
                    if (isset($_GET['erreurage'])) {
                        echo '<span>Erreur d age, veuillez saisir un nombre</span>';
                    }
                ?>
            <p>
                <input type="submit" value="Valider" />
            </p>
        </form>
    </body>
</html>