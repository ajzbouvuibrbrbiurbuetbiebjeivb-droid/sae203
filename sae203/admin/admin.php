<?php
// ============================================================
// DONNÉES D'EXEMPLE — Remplacez par vos requêtes PDO/SQL
// ============================================================

// Exemple de connexion PDO (décommenter et adapter) :
// $pdo = new PDO('mysql:host=localhost;dbname=ma_base;charset=utf8', 'user', 'password');

// --- Statistiques KPI ---
// SQL : SELECT COUNT(*) FROM Animal
$stats_animaux = 7;
// SQL : SELECT COUNT(*) FROM Animal WHERE statut = 'En danger'
$stats_endangered = 2;
// SQL : SELECT COUNT(*) FROM Animal WHERE statut = 'Vulnérable'
$stats_vulnerable = 2;
// SQL : SELECT COUNT(DISTINCT habitat_id) FROM Habitat
$stats_habitats = 5;

// --- Données du graphique (7 derniers jours) ---
// SQL : SELECT DATE(created_at), COUNT(*) FROM Animal GROUP BY DATE(created_at) ORDER BY created_at DESC LIMIT 7
$chart_labels = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
$chart_data   = [3, 5, 2, 8, 6, 4, 7];

// --- Tableau des dernières entrées ---
// SQL : SELECT a.*, h.sol, h.profondeur, h.temperature FROM Animal a LEFT JOIN Habitat h ON a.habitat_id = h.id ORDER BY a.id DESC LIMIT 10
$derniers_animaux = [
    ['id' => 7, 'nom_commun' => 'Hippocampe Moucheté', 'nom_sci' => 'Hippocampus guttulatus', 'regime' => 'Carnivore',    'statut' => 'Vulnérable',           'statut_class' => 'vulnerable'],
    ['id' => 6, 'nom_commun' => 'Grand Dauphin',        'nom_sci' => 'Tursiops truncatus',      'regime' => 'Carnivore',    'statut' => 'Préoccupation mineure', 'statut_class' => 'secure'],
    ['id' => 5, 'nom_commun' => 'Poisson-clown',        'nom_sci' => 'Amphiprioninae',           'regime' => 'Omnivore',     'statut' => 'Préoccupation mineure', 'statut_class' => 'secure'],
    ['id' => 4, 'nom_commun' => 'Pieuvre Géante',        'nom_sci' => 'Enteroctopus dofleini',   'regime' => 'Carnivore',    'statut' => 'Préoccupation mineure', 'statut_class' => 'secure'],
    ['id' => 3, 'nom_commun' => 'Grand Requin Blanc',    'nom_sci' => 'Carcharodon carcharias',  'regime' => 'Carnivore',    'statut' => 'Vulnérable',           'statut_class' => 'vulnerable'],
    ['id' => 2, 'nom_commun' => 'Tortue Verte',          'nom_sci' => 'Chelonia mydas',          'regime' => 'Herbivore',    'statut' => 'En danger',            'statut_class' => 'endangered'],
    ['id' => 1, 'nom_commun' => 'Baleine Bleue',         'nom_sci' => 'Balaenoptera musculus',   'regime' => 'Planctivore',  'statut' => 'En danger',            'statut_class' => 'endangered'],
];

// --- Gestion du formulaire d'ajout ---
$form_success = false;
$form_error   = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter') {
    $nom_commun = trim(filter_input(INPUT_POST, 'nom_commun', FILTER_SANITIZE_SPECIAL_CHARS));
    $nom_sci    = trim(filter_input(INPUT_POST, 'nom_sci',    FILTER_SANITIZE_SPECIAL_CHARS));
    $regime     = trim(filter_input(INPUT_POST, 'regime',     FILTER_SANITIZE_SPECIAL_CHARS));
    $statut     = trim(filter_input(INPUT_POST, 'statut',     FILTER_SANITIZE_SPECIAL_CHARS));

    if ($nom_commun && $nom_sci && $regime && $statut) {
        // SQL : INSERT INTO Animal (nom_commun, nom_scientifique, regime, statut) VALUES (?, ?, ?, ?)
        // $stmt = $pdo->prepare("INSERT INTO Animal (nom_commun, nom_scientifique, regime, statut) VALUES (?, ?, ?, ?)");
        // $stmt->execute([$nom_commun, $nom_sci, $regime, $statut]);
        $form_success = true;
    } else {
        $form_error = 'Veuillez remplir tous les champs obligatoires.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Abysses & Merveilles</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
tailwind.config = {
    theme: {
        extend: {
            fontFamily: { sans: ['DM Sans', 'sans-serif'], mono: ['DM Mono', 'monospace'] },
            colors: {
                ocean: {
                    50:  '#f0f7ff',
                    100: '#e0effe',
                    200: '#bae0fd',
                    300: '#7cc8fb',
                    400: '#36aaf5',
                    500: '#0d90e0',
                    600: '#0171be',
                    700: '#025a9a',
                    800: '#064d7f',
                    900: '#0b4169',
                    950: '#072947',
                },
            }
        }
    }
}
</script>
<style>
    * { font-family: 'DM Sans', sans-serif; }
    .sidebar-link { @apply flex items-center gap-3 px-4 py-2.5 rounded-lg text-slate-400 text-sm font-medium transition-all duration-200 hover:bg-white/10 hover:text-white; }
    .sidebar-link.active { @apply bg-white/15 text-white; }
    .stat-card { transition: transform .2s ease, box-shadow .2s ease; }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,.08); }
    .badge-endangered { @apply inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200; }
    .badge-vulnerable { @apply inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200; }
    .badge-secure     { @apply inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200; }
    .input-field { @apply w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-ocean-400 focus:border-transparent transition; }
    #modal-overlay { backdrop-filter: blur(4px); }
    .menu-item-tooltip { pointer-events: none; }
    @keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
    .fade-up { animation: fadeUp .4s ease forwards; }
    .fade-up-1 { animation-delay: .05s; opacity:0; }
    .fade-up-2 { animation-delay: .10s; opacity:0; }
    .fade-up-3 { animation-delay: .15s; opacity:0; }
    .fade-up-4 { animation-delay: .20s; opacity:0; }
    .fade-up-5 { animation-delay: .25s; opacity:0; }
</style>
</head>
<body class="bg-slate-50 text-slate-800">

<!-- ===================== LAYOUT ===================== -->
<div class="flex min-h-screen">

    <!-- ===== SIDEBAR ===== -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-ocean-950 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0">
        <!-- Logo -->
        <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
            <div class="w-8 h-8 rounded-lg bg-ocean-500 flex items-center justify-center flex-shrink-0">
                <i data-lucide="fish" class="w-4 h-4 text-white"></i>
            </div>
            <span class="font-bold text-white text-sm leading-tight">Abysses &<br><span class="text-ocean-300">Merveilles</span></span>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider px-4 mb-2">Principal</p>
            <a href="#dashboard" onclick="showSection('dashboard')" class="sidebar-link active" id="nav-dashboard">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
            </a>
            <a href="#contenu" onclick="showSection('contenu')" class="sidebar-link" id="nav-contenu">
                <i data-lucide="database" class="w-4 h-4"></i> Contenu
            </a>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider px-4 mb-2 mt-4">Analyse</p>
            <a href="#statistiques" onclick="showSection('statistiques')" class="sidebar-link" id="nav-statistiques">
                <i data-lucide="bar-chart-2" class="w-4 h-4"></i> Statistiques
            </a>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider px-4 mb-2 mt-4">Système</p>
            <a href="#parametres" onclick="showSection('parametres')" class="sidebar-link" id="nav-parametres">
                <i data-lucide="settings-2" class="w-4 h-4"></i> Paramètres
            </a>
        </nav>

        <!-- User footer -->
        <div class="px-3 py-4 border-t border-white/10">
            <div class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 cursor-pointer transition">
                <div class="w-8 h-8 rounded-full bg-ocean-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">AD</div>
                <div class="min-w-0">
                    <p class="text-white text-sm font-medium truncate">Administrateur</p>
                    <p class="text-slate-400 text-xs truncate">admin@abysses.fr</p>
                </div>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-500 ml-auto flex-shrink-0"></i>
            </div>
        </div>
    </aside>

    <!-- Overlay mobile -->
    <div id="sidebar-overlay" onclick="closeSidebar()" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden"></div>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">

        <!-- TOP NAV -->
        <header class="sticky top-0 z-20 bg-white border-b border-slate-200 px-4 lg:px-6 py-3 flex items-center gap-4">
            <!-- Burger (mobile) -->
            <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-500">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>

            <!-- Search -->
            <div class="flex-1 max-w-md relative">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                <input type="text" placeholder="Rechercher une espèce..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-ocean-400 focus:border-transparent transition">
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2 ml-auto">
                <a href="../listing.php" class="hidden sm:flex items-center gap-2 text-sm text-slate-500 hover:text-ocean-600 px-3 py-2 rounded-lg hover:bg-slate-100 transition">
                    <i data-lucide="external-link" class="w-4 h-4"></i>
                    <span>Voir le site</span>
                </a>
                <button class="relative p-2 rounded-lg hover:bg-slate-100 text-slate-500 transition">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <div class="w-8 h-8 rounded-full bg-ocean-600 flex items-center justify-center text-white text-xs font-bold cursor-pointer">AD</div>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="flex-1 px-4 lg:px-6 py-6 space-y-6">

            <?php if ($form_success): ?>
            <div class="fade-up bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
                <i data-lucide="check-circle-2" class="w-4 h-4 flex-shrink-0"></i>
                Animal ajouté avec succès ! (Requête SQL INSERT à brancher sur PDO)
            </div>
            <?php elseif ($form_error): ?>
            <div class="fade-up bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
                <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
                <?= htmlspecialchars($form_error) ?>
            </div>
            <?php endif; ?>

            <!-- ===== SECTION : DASHBOARD ===== -->
            <section id="section-dashboard">

                <!-- Page title -->
                <div class="fade-up fade-up-1 flex items-center justify-between mb-2">
                    <div>
                        <h1 class="text-xl font-bold text-slate-900">Tableau de bord</h1>
                        <p class="text-sm text-slate-500 mt-0.5">Vue d'ensemble de la base de données marine</p>
                    </div>
                    <button onclick="openModal()" class="flex items-center gap-2 bg-ocean-600 hover:bg-ocean-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition shadow-sm">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span class="hidden sm:inline">Ajouter une espèce</span>
                    </button>
                </div>

                <!-- KPI CARDS -->
                <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 fade-up fade-up-2">

                    <!-- Card 1 -->
                    <div class="stat-card bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Espèces totales</p>
                                <p class="text-3xl font-bold text-slate-900 mt-1"><?= $stats_animaux ?></p>
                            </div>
                            <div class="w-10 h-10 rounded-lg bg-ocean-50 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="fish" class="w-5 h-5 text-ocean-600"></i>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 mt-3 text-xs text-emerald-600 font-medium">
                            <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                            <span>+2 ce mois-ci</span>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="stat-card bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">En danger</p>
                                <p class="text-3xl font-bold text-red-600 mt-1"><?= $stats_endangered ?></p>
                            </div>
                            <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500"></i>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 mt-3 text-xs text-red-500 font-medium">
                            <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                            <span>+1 vs mois dernier</span>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="stat-card bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Vulnérables</p>
                                <p class="text-3xl font-bold text-amber-500 mt-1"><?= $stats_vulnerable ?></p>
                            </div>
                            <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="shield-alert" class="w-5 h-5 text-amber-500"></i>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 mt-3 text-xs text-slate-400 font-medium">
                            <i data-lucide="minus" class="w-3.5 h-3.5"></i>
                            <span>Stable ce mois</span>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="stat-card bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Habitats référencés</p>
                                <p class="text-3xl font-bold text-slate-900 mt-1"><?= $stats_habitats ?></p>
                            </div>
                            <div class="w-10 h-10 rounded-lg bg-teal-50 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="map-pin" class="w-5 h-5 text-teal-600"></i>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 mt-3 text-xs text-emerald-600 font-medium">
                            <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                            <span>+12% vs mois dernier</span>
                        </div>
                    </div>
                </div>

                <!-- CHART + MINI INFO -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 fade-up fade-up-3">

                    <!-- Line Chart -->
                    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h2 class="text-sm font-bold text-slate-800">Activité — 7 derniers jours</h2>
                                <p class="text-xs text-slate-400 mt-0.5">Consultations de fiches espèces</p>
                            </div>
                            <span class="text-xs bg-ocean-50 text-ocean-700 font-semibold px-2.5 py-1 rounded-full border border-ocean-200">Cette semaine</span>
                        </div>
                        <canvas id="activityChart" height="110"></canvas>
                    </div>

                    <!-- Quick stats -->
                    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm flex flex-col gap-4">
                        <h2 class="text-sm font-bold text-slate-800">Répartition régimes</h2>
                        <div class="space-y-3">
                            <?php
                            $regimes = ['Carnivore' => 4, 'Herbivore' => 1, 'Omnivore' => 1, 'Planctivore' => 1];
                            $total_r = array_sum($regimes);
                            $colors  = ['Carnivore' => 'bg-red-400', 'Herbivore' => 'bg-emerald-400', 'Omnivore' => 'bg-violet-400', 'Planctivore' => 'bg-sky-400'];
                            foreach ($regimes as $r => $n):
                                $pct = round($n / $total_r * 100);
                            ?>
                            <div>
                                <div class="flex items-center justify-between text-xs mb-1">
                                    <span class="font-medium text-slate-600"><?= $r ?></span>
                                    <span class="text-slate-400 font-mono"><?= $pct ?>%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5">
                                    <div class="<?= $colors[$r] ?> h-1.5 rounded-full transition-all duration-700" style="width:<?= $pct ?>%"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-auto pt-4 border-t border-slate-100">
                            <p class="text-xs text-slate-400">Dernière mise à jour</p>
                            <p class="text-sm font-semibold text-slate-700 mt-0.5 font-mono"><?= date('d/m/Y H:i') ?></p>
                        </div>
                    </div>
                </div>

                <!-- DATA TABLE -->
                <div class="fade-up fade-up-4 bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                        <div>
                            <h2 class="text-sm font-bold text-slate-800">Dernières espèces enregistrées</h2>
                            <p class="text-xs text-slate-400 mt-0.5"><?= count($derniers_animaux) ?> entrées affichées</p>
                        </div>
                        <a href="../listing.php" class="text-xs text-ocean-600 font-semibold hover:underline flex items-center gap-1">
                            Voir tout <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                    <th class="px-5 py-3 text-left">ID</th>
                                    <th class="px-5 py-3 text-left">Nom commun</th>
                                    <th class="px-5 py-3 text-left hidden md:table-cell">Nom scientifique</th>
                                    <th class="px-5 py-3 text-left hidden lg:table-cell">Régime</th>
                                    <th class="px-5 py-3 text-left">Statut</th>
                                    <th class="px-5 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php foreach ($derniers_animaux as $animal): ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-5 py-3.5 font-mono text-xs text-slate-400">#<?= $animal['id'] ?></td>
                                    <td class="px-5 py-3.5 font-medium text-slate-800"><?= htmlspecialchars($animal['nom_commun']) ?></td>
                                    <td class="px-5 py-3.5 text-slate-500 italic hidden md:table-cell"><?= htmlspecialchars($animal['nom_sci']) ?></td>
                                    <td class="px-5 py-3.5 text-slate-500 hidden lg:table-cell"><?= htmlspecialchars($animal['regime']) ?></td>
                                    <td class="px-5 py-3.5">
                                        <?php
                                        $badge_map = [
                                            'endangered' => '<span class="badge-endangered">En danger</span>',
                                            'vulnerable' => '<span class="badge-vulnerable">Vulnérable</span>',
                                            'secure'     => '<span class="badge-secure">Stable</span>',
                                        ];
                                        echo $badge_map[$animal['statut_class']] ?? '';
                                        ?>
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <!-- SQL : UPDATE Animal SET ... WHERE id = <?= $animal['id'] ?> -->
                                            <button title="Modifier" class="p-1.5 rounded-lg hover:bg-ocean-50 text-slate-400 hover:text-ocean-600 transition">
                                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                            </button>
                                            <!-- SQL : DELETE FROM Animal WHERE id = <?= $animal['id'] ?> -->
                                            <button title="Supprimer" class="p-1.5 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-500 transition">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </section><!-- /dashboard -->

            <!-- ===== SECTION : CONTENU ===== -->
            <section id="section-contenu" class="hidden">
                <div class="fade-up bg-white border border-slate-200 rounded-xl p-8 shadow-sm text-center">
                    <div class="w-14 h-14 bg-ocean-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="database" class="w-7 h-7 text-ocean-500"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800 mb-1">Gestion du contenu</h2>
                    <p class="text-slate-400 text-sm max-w-sm mx-auto">Section à développer — branchez ici votre CRUD complet avec requêtes PDO.</p>
                    <button onclick="openModal()" class="mt-5 inline-flex items-center gap-2 bg-ocean-600 hover:bg-ocean-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                        <i data-lucide="plus" class="w-4 h-4"></i> Ajouter une espèce
                    </button>
                </div>
            </section>

            <!-- ===== SECTION : STATISTIQUES ===== -->
            <section id="section-statistiques" class="hidden">
                <div class="fade-up bg-white border border-slate-200 rounded-xl p-8 shadow-sm text-center">
                    <div class="w-14 h-14 bg-violet-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="bar-chart-2" class="w-7 h-7 text-violet-500"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800 mb-1">Statistiques avancées</h2>
                    <p class="text-slate-400 text-sm max-w-sm mx-auto">Ajoutez ici des graphiques Chart.js supplémentaires alimentés par vos requêtes SQL.</p>
                </div>
            </section>

            <!-- ===== SECTION : PARAMÈTRES ===== -->
            <section id="section-parametres" class="hidden">
                <div class="fade-up fade-up-1">
                    <h1 class="text-xl font-bold text-slate-900 mb-1">Paramètres</h1>
                    <p class="text-sm text-slate-500 mb-5">Configuration générale de l'application</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="fade-up fade-up-2 bg-white border border-slate-200 rounded-xl p-6 shadow-sm space-y-4">
                        <h3 class="font-semibold text-slate-700 text-sm flex items-center gap-2">
                            <i data-lucide="database" class="w-4 h-4 text-ocean-500"></i> Base de données
                        </h3>
                        <div>
                            <label class="text-xs font-medium text-slate-500 block mb-1">Hôte MySQL</label>
                            <input type="text" value="localhost" class="input-field">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-slate-500 block mb-1">Nom de la base</label>
                            <input type="text" placeholder="ma_base_marine" class="input-field">
                        </div>
                        <button class="w-full bg-ocean-600 hover:bg-ocean-700 text-white py-2 rounded-lg text-sm font-semibold transition">Sauvegarder</button>
                    </div>
                    <div class="fade-up fade-up-3 bg-white border border-slate-200 rounded-xl p-6 shadow-sm space-y-4">
                        <h3 class="font-semibold text-slate-700 text-sm flex items-center gap-2">
                            <i data-lucide="user" class="w-4 h-4 text-ocean-500"></i> Compte administrateur
                        </h3>
                        <div>
                            <label class="text-xs font-medium text-slate-500 block mb-1">Email</label>
                            <input type="email" value="admin@abysses.fr" class="input-field">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-slate-500 block mb-1">Nouveau mot de passe</label>
                            <input type="password" placeholder="••••••••" class="input-field">
                        </div>
                        <button class="w-full bg-slate-700 hover:bg-slate-800 text-white py-2 rounded-lg text-sm font-semibold transition">Modifier</button>
                    </div>
                </div>
            </section>

        </main>
    </div><!-- /main -->
</div><!-- /layout -->

<!-- ===================== MODAL AJOUT ===================== -->
<div id="modal-overlay" onclick="closeModal()" class="fixed inset-0 bg-black/40 z-50 hidden flex items-center justify-center p-4">
    <div onclick="event.stopPropagation()" class="bg-white rounded-2xl shadow-2xl w-full max-w-md border border-slate-200 overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-ocean-50 flex items-center justify-center">
                    <i data-lucide="plus-circle" class="w-4 h-4 text-ocean-600"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-sm">Ajouter une espèce</h3>
            </div>
            <button onclick="closeModal()" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400 transition">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Form -->
        <!-- SQL INSERT : voir la logique PHP en haut du fichier -->
        <form method="POST" action="admin.php" class="px-6 py-5 space-y-4">
            <input type="hidden" name="action" value="ajouter">

            <div>
                <label class="text-xs font-semibold text-slate-600 block mb-1.5">Nom commun <span class="text-red-400">*</span></label>
                <input type="text" name="nom_commun" placeholder="ex: Baleine Bleue" class="input-field" required>
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-600 block mb-1.5">Nom scientifique <span class="text-red-400">*</span></label>
                <input type="text" name="nom_sci" placeholder="ex: Balaenoptera musculus" class="input-field" required>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-semibold text-slate-600 block mb-1.5">Régime alimentaire <span class="text-red-400">*</span></label>
                    <select name="regime" class="input-field" required>
                        <option value="">— Choisir —</option>
                        <option>Carnivore</option>
                        <option>Herbivore</option>
                        <option>Omnivore</option>
                        <option>Planctivore</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-600 block mb-1.5">Statut IUCN <span class="text-red-400">*</span></label>
                    <select name="statut" class="input-field" required>
                        <option value="">— Choisir —</option>
                        <option>En danger</option>
                        <option>Vulnérable</option>
                        <option>Préoccupation mineure</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="closeModal()" class="flex-1 py-2.5 border border-slate-200 rounded-lg text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Annuler</button>
                <button type="submit" class="flex-1 py-2.5 bg-ocean-600 hover:bg-ocean-700 text-white rounded-lg text-sm font-semibold transition shadow-sm">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<!-- ===================== SCRIPTS ===================== -->
<script>
// --- Icônes Lucide ---
lucide.createIcons();

// --- Sidebar mobile ---
function toggleSidebar() {
    const s = document.getElementById('sidebar');
    const o = document.getElementById('sidebar-overlay');
    s.classList.toggle('-translate-x-full');
    o.classList.toggle('hidden');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.add('-translate-x-full');
    document.getElementById('sidebar-overlay').classList.add('hidden');
}

// --- Navigation sections ---
const sections = ['dashboard', 'contenu', 'statistiques', 'parametres'];
function showSection(name) {
    sections.forEach(s => {
        document.getElementById('section-' + s).classList.toggle('hidden', s !== name);
        const navEl = document.getElementById('nav-' + s);
        navEl.classList.toggle('active', s === name);
    });
    closeSidebar();
    return false;
}

// --- Modal ---
function openModal() {
    const m = document.getElementById('modal-overlay');
    m.classList.remove('hidden');
    m.classList.add('flex');
}
function closeModal() {
    const m = document.getElementById('modal-overlay');
    m.classList.add('hidden');
    m.classList.remove('flex');
}
// Ouvrir la modal automatiquement si erreur de formulaire
<?php if ($form_error): ?>
openModal();
<?php endif; ?>

// --- Chart.js : Activité 7 jours ---
const ctx = document.getElementById('activityChart').getContext('2d');
const gradient = ctx.createLinearGradient(0, 0, 0, 200);
gradient.addColorStop(0, 'rgba(13, 144, 224, 0.18)');
gradient.addColorStop(1, 'rgba(13, 144, 224, 0)');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($chart_labels) ?>,
        datasets: [{
            label: 'Consultations',
            data: <?= json_encode($chart_data) ?>,
            borderColor: '#0d90e0',
            backgroundColor: gradient,
            borderWidth: 2.5,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#0171be',
            pointRadius: 4,
            pointHoverRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0b4169',
                titleFont: { family: 'DM Sans', size: 12 },
                bodyFont:  { family: 'DM Sans', size: 12 },
                padding: 10,
                cornerRadius: 8,
            }
        },
        scales: {
            x: { grid: { display: false }, ticks: { font: { family: 'DM Sans', size: 11 }, color: '#94a3b8' } },
            y: { grid: { color: '#f1f5f9' }, ticks: { font: { family: 'DM Sans', size: 11 }, color: '#94a3b8' }, beginAtZero: true }
        }
    }
});
</script>
</body>
</html>
