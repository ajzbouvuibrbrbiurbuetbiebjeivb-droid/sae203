<?php include 'header.php'; ?>

<div class="container">
    <h2>Rechercher un animal</h2>
    
    <div class="search-container">
        <form action="reponse_recherche.php" method="GET" class="search-form">
            <label for="texte">Nom de l'animal :</label>
            <input type="text" id="texte" name="texte" placeholder="Ex: Baleine, Requin..." required>
            <button type="submit">Lancer la recherche</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>