<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/autoload.php';

$db = new Database();
$formationDao = new FormationDALImpl($db->getConnection());
$formationBusiness = new FormationBusinessImpl($formationDao);

$coursDao = new CoursDALImpl($db->getConnection());
$coursBusiness = new CoursBusinessImpl($coursDao);

$villeDao = new VillesDALImpl($db->getConnection());
$villeBusiness = new VillesBusinessImpl($villeDao);

$formateurDao = new FormateurDALImpl($db->getConnection());
$formateurBusiness = new FormateurBusinessImpl($formateurDao);

// Get formation ID
$formationId = $_GET['id'] ?? null;
if (!$formationId) {
    die("ID de formation manquant.");
}

// Get formation details
$formation = $formationBusiness->getById($formationId);
if (!$formation) {
    die("Formation non trouvée.");
}

$cours = $coursBusiness->getById($formation->coursId);
$ville = $villeBusiness->getById($formation->villeId);
$formateur = $formateurBusiness->getById($formation->formateurId);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail Formation - FormationPro</title>
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

    <section class="formation-details">
        <h1 class="details-heading">📘 Détails de la Formation</h1>

        <div class="formation-container">
            <div class="formation-info">
                <div class="info-block">
                    <h2>Cours</h2>
                    <p><?= htmlspecialchars($cours->name) ?></p>
                </div>
                <div class="info-block">
                    <h2>Prix</h2>
                    <p><?= htmlspecialchars($formation->price) ?> €</p>
                </div>
                <div class="info-block">
                    <h2>Mode</h2>
                    <p><?= htmlspecialchars($formation->mode) ?></p>
                </div>
                <div class="info-block">
                    <h2>Formateur</h2>
                    <p><?= htmlspecialchars($formateur ? $formateur->name : 'Non défini') ?></p>
                </div>
                <div class="info-block">
                    <h2>Ville</h2>
                    <p><?= htmlspecialchars($ville ? $ville->name : 'Non défini') ?></p>
                </div>
            </div>

            <div class="formation-image">
                <h2>Logo du Cours</h2>
                <?php if ($cours && $cours->logo): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($cours->logo) ?>" alt="Logo du cours">
                <?php else: ?>
                    <div class="image-placeholder">Aucun logo</div>
                <?php endif; ?>
            </div>
        </div>

        <!--div class="voir-plus inscription">
            <a href="inscription.php?dateId=<?= $formation->id ?>">Inscription</a>
        </div-->

        <div class="action-links">
            <a href="formations.php" class="return-link">← Retour aux formations</a>
        </div>
    </section>


</main>

<footer>
    <p>&copy; 2025 FormationPro. Tous droits réservés.</p>
</footer>

</body>
</html>
