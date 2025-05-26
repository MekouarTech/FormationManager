<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/autoload.php';

$db = new Database();

$coursDao = new CoursDALImpl($db->getConnection());
$coursBusiness = new CoursBusinessImpl($coursDao);

$domainesDao = new DomaineDALImpl($db->getConnection());
$domainesBusiness = new DomaineBusinessImpl($domainesDao);

$sujetDao = new SujetDALImpl($db->getConnection());
$sujetBusiness = new SujetBusinessImpl($sujetDao);

$formationDao = new FormationDALImpl($db->getConnection());
$formationBusiness = new FormationBusinessImpl($formationDao);

$search = $_GET['search'] ?? '';
$domaineId = $_GET['domaine'] ?? '';
$sujetId = $_GET['sujet'] ?? '';
$coursId = $_GET['cours'] ?? '';

// Load filters
$domaines = $domainesBusiness->getAll();
$sujets = $sujetBusiness->getAll();
$coursList = $coursBusiness->getAll();

// Load formations
$formationList = $formationBusiness->searchFormations($search, $domaineId, $sujetId, $coursId);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Catalogue des Formations - FormationPro</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="container header-container">
        <div class="logo">
            <a href="index.php">Formation<span>Pro</span></a>
        </div>
        <nav class="navbar">
            <ul>
                <li><a href="formations.php" class="active">Formations</a></li>
                <li><a href="calendrier.php">Calendrier</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if (isset($_SESSION['user'])): ?>
                    <li><a href="logout.php" class="nav-btn">Se déconnecter</a></li>
                    <li><a href="listinscriptions.php" class="nav-link-inscriptions">Mes Inscriptions</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="nav-btn">Se connecter</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<main class="container">

    <section class="formations-page">
        <h1>Catalogue des Formations</h1>

        <!-- Filter Form -->
        <form method="GET" action="formations.php" class="filter-form">
            <input type="text" name="search" placeholder="Rechercher une formation..." value="<?= htmlspecialchars($search) ?>">

            <select name="domaine">
                <option value="">Tous les domaines</option>
                <?php foreach ($domaines as $d): ?>
                    <option value="<?= $d->id ?>" <?= $domaineId == $d->id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($d->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="sujet">
                <option value="">Tous les sujets</option>
                <?php foreach ($sujets as $s): ?>
                    <option value="<?= $s->id ?>" <?= $sujetId == $s->id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="cours">
                <option value="">Tous les cours</option>
                <?php foreach ($coursList as $cours): ?>
                    <option value="<?= $cours->id ?>" <?= $coursId == $cours->id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cours->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn">Filtrer</button>
        </form>

        <!-- Formations List -->
        <div class="formations-grid">
            <?php if (empty($formationList)): ?>
                <p>Aucune formation trouvée pour les critères sélectionnés.</p>
            <?php else: ?>
                <?php foreach ($formationList as $formation): ?>
                    <?php $cours = $coursBusiness->getById($formation->coursId); ?>
                    <article class="formation-card">
                        <?php if ($cours->logo): ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($cours->logo) ?>" alt="Logo du cours" />
                        <?php else: ?>
                            <img src="placeholder.jpg" alt="Aucun logo disponible" />
                        <?php endif; ?>
                        <h3><a href="formationDetails.php?id=<?= $formation->id ?>">
                            <?= htmlspecialchars($cours->name) ?>
                        </a></h3>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

</main>

<footer>
    <p>&copy; 2025 FormationPro. Tous droits réservés.</p>
</footer>

</body>
</html>
