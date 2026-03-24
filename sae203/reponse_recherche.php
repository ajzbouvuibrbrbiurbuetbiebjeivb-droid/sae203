<?php include('header.php'); ?>

<div class="container-fluid">
    <h2>Résultats de votre recherche</h2>
    
    <p style="font-size: 1.2rem; color: var(--wave-blue); background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        ... ici les animaux dont le nom contient l'expression '<strong><?php echo htmlspecialchars($_GET['texte']); ?></strong>' ...
    </p>
    
    <div class="table-wrapper">
        <table class="marine-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Aperçu</th>
                    <th>Nom Commun</th>
                    <th>Nom Scientifique</th>
                    <th>Régime</th>
                    <th>Temps De Vie (ans)</th>
                    <th>Statut</th>
                    <th>Habitat (Sol/Prof/Temp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td data-label="ID">99</td>
                    <td data-label="Aperçu"><img src="images/requin.jpg" class="thumb" alt="Animal Fictif 1"></td>
                    <td data-label="Nom Commun">Requin Fictif</td>
                    <td data-label="Nom Scientifique">Fictivus Carcharias</td>
                    <td data-label="Régime">Carnivore</td>
                    <td data-label="Temps De Vie (ans)">50</td>
                    <td data-label="Statut"><span class="status secure">Préoccupation mineure</span></td>
                    <td data-label="Habitat">Pélagique / 50m / 20°C</td>
                </tr>
                <tr>
                    <td data-label="ID">100</td>
                    <td data-label="Aperçu"><img src="images/pieuvre.jpg" class="thumb" alt="Animal Fictif 2"></td>
                    <td data-label="Nom Commun">Pieuvre Imaginaire</td>
                    <td data-label="Nom Scientifique">Octopus Imaginarius</td>
                    <td data-label="Régime">Carnivore</td>
                    <td data-label="Temps De Vie (ans)">3</td>
                    <td data-label="Statut"><span class="status vulnerable">Vulnérable</span></td>
                    <td data-label="Habitat">Rocheux / 80m / 12°C</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>