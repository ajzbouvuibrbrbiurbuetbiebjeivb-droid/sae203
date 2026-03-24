<?php include('header.php'); ?>

<div class="container-fluid">
    <h2>Inventaire Détaillé des Espèces Marines</h2>
    <p>Ce tableau présente les données extraites de la table <strong>Animal</strong> croisées avec la table <strong>Habitat</strong>.</p>
    
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
                    <td data-label="ID">1</td>
                    <td data-label="Aperçu"><img src="images/baleine.jpg" class="thumb" alt="Baleine"></td>
                    <td data-label="Nom Commun">Baleine Bleue</td>
                    <td data-label="Nom Scientifique">Balaenoptera musculus</td>
                    <td data-label="Régime">Planctivore</td>
                    <td data-label="Temps De Vie (ans)">85</td>
                    <td data-label="Statut"><span class="status endangered">En danger</span></td>
                    <td data-label="Habitat">Pélagique / 100m / 4°C</td>
                </tr>
                <tr>
                    <td data-label="ID">2</td>
                    <td data-label="Aperçu"><img src="images/tortue.jpg" class="thumb" alt="Tortue"></td>
                    <td data-label="Nom Commun">Tortue Verte</td>
                    <td data-label="Nom Scientifique">Chelonia mydas</td>
                    <td data-label="Régime">Herbivore</td>
                    <td data-label="Temps De Vie (ans)">70</td>
                    <td data-label="Statut"><span class="status endangered">En danger</span></td>
                    <td data-label="Habitat">Sableux / 20m / 25°C</td>
                </tr>
                <tr>
                    <td data-label="ID">3</td>
                    <td data-label="Aperçu"><img src="images/requin.jpg" class="thumb" alt="Requin"></td>
                    <td data-label="Nom Commun">Grand Requin Blanc</td>
                    <td data-label="Nom Scientifique">Carcharodon carcharias</td>
                    <td data-label="Régime">Carnivore</td>
                    <td data-label="Temps De Vie (ans)">70</td>
                    <td data-label="Statut"><span class="status vulnerable">Vulnérable</span></td>
                    <td data-label="Habitat">Mixte / 30m / 15°C</td>
                </tr>
                <tr>
                    <td data-label="ID">4</td>
                    <td data-label="Aperçu"><img src="images/pieuvre.jpg" class="thumb" alt="Pieuvre"></td>
                    <td data-label="Nom Commun">Pieuvre Géante</td>
                    <td data-label="Nom Scientifique">Enteroctopus dofleini</td>
                    <td data-label="Régime">Carnivore</td>
                    <td data-label="Temps De Vie (ans)">5</td>
                    <td data-label="Statut"><span class="status secure">Préoccupation mineure</span></td>
                    <td data-label="Habitat">Rocheux / 100m / 10°C</td>
                </tr>
                <tr>
                    <td data-label="ID">5</td>
                    <td data-label="Aperçu"><img src="images/clown.jpg" class="thumb" alt="Clown"></td>
                    <td data-label="Nom Commun">Poisson-clown</td>
                    <td data-label="Nom Scientifique">Amphiprioninae</td>
                    <td data-label="Régime">Omnivore</td>
                    <td data-label="Temps De Vie (ans)">10</td>
                    <td data-label="Statut"><span class="status secure">Préoccupation mineure</span></td>
                    <td data-label="Habitat">Corallien / 10m / 26°C</td>
                </tr>
                <tr>
                    <td data-label="ID">6</td>
                    <td data-label="Aperçu"><img src="images/dauphin.jpg" class="thumb" alt="Dauphin"></td>
                    <td data-label="Nom Commun">Grand Dauphin</td>
                    <td data-label="Nom Scientifique">Tursiops truncatus</td>
                    <td data-label="Régime">Carnivore</td>
                    <td data-label="Temps De Vie (ans)">40</td>
                    <td data-label="Statut"><span class="status secure">Préoccupation mineure</span></td>
                    <td data-label="Habitat">Pélagique / 25m / 18°C</td>
                </tr>
                <tr>
                    <td data-label="ID">7</td>
                    <td data-label="Aperçu"><img src="images/hippo.jpg" class="thumb" alt="Hippocampe"></td>
                    <td data-label="Nom Commun">Hippocampe Moucheté</td>
                    <td data-label="Nom Scientifique">Hippocampus guttulatus</td>
                    <td data-label="Régime">Carnivore</td>
                    <td data-label="Temps De Vie (ans)">4</td>
                    <td data-label="Statut"><span class="status vulnerable">Vulnérable</span></td>
                    <td data-label="Habitat">Rocheux / 20m / 26°C</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>