<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

$userEmail = $_SESSION['user']['email'] ?? null;

$inscriptionDao = new InscriptionDALImpl($conn);
$inscriptionBusiness = new InscriptionBusinessImpl($inscriptionDao);

$formationDateDao = new FormationDateDALImpl($conn);
$formationDateBusiness = new FormationDateBusinessImpl($formationDateDao);

$formationDao = new FormationDALImpl($conn);
$formationBusiness = new FormationBusinessImpl($formationDao);

$coursDao = new CoursDALImpl($conn);
$coursBusiness = new CoursBusinessImpl($coursDao);

$inscriptions = $inscriptionBusiness->getByEmail($userEmail);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Inscriptions - FormationPro</title>
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
                <li><a href="formations.php">Formations</a></li>
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
    <h1>Mes Inscriptions</h1>

    <?php if (empty($inscriptions)): ?>
        <p>Vous n'avez aucune inscription pour le moment.</p>
    <?php else: ?>
        <div class="articles-gallery">
            <?php foreach ($inscriptions as $inscription): ?>
                <?php
                $formationDate = is_object($inscription) && isset($inscription->formationDateId)
                    ? $formationDateBusiness->getById($inscription->formationDateId)
                    : null;

                $formation = is_object($formationDate) && isset($formationDate->formationId)
                    ? $formationBusiness->getById($formationDate->formationId)
                    : null;

                $cours = is_object($formation) && isset($formation->coursId)
                    ? $coursBusiness->getById($formation->coursId)
                    : null;
                ?>

                <article class="gallery-item">
                <?php if ($cours->logo): ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($cours->logo) ?>" alt="Logo du cours" />
                        <?php else: ?>
                            <img src="placeholder.jpg" alt="Aucun logo disponible" />
                        <?php endif; ?>

                    <h3>Cour : <?= htmlspecialchars($cours->name) ?></h3>
                    <p><strong>Nom :</strong> <?= htmlspecialchars($inscription->firstName) ?> <?= htmlspecialchars($inscription->lastName) ?></p>
                    <p><strong>Email :</strong> <?= htmlspecialchars($inscription->email) ?></p>
                    <p><strong>Téléphone :</strong> <?= htmlspecialchars($inscription->phone ?? 'Non fourni') ?></p>
                    <p><strong>Entreprise :</strong> <?= htmlspecialchars($inscription->company ?? 'Non fournie') ?></p>
                    <p><strong>Payé :</strong> <?= $inscription->paid ? 'Oui' : 'Non' ?></p>
                    <p><strong>Date de la formation :</strong> <?= isset($formationDate->date) ? htmlspecialchars($formationDate->date) : 'Indisponible' ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<footer>
    <p>&copy; 2025 FormationPro. Tous droits réservés.</p>
</footer>

</body>
</html>
