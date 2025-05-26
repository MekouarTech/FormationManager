<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/autoload.php';

$db = new Database();

$formationDateBusiness = new FormationDateBusinessImpl(new FormationDateDALImpl($db->getConnection()));
$formationBusiness = new FormationBusinessImpl(new FormationDALImpl($db->getConnection()));
$coursBusiness = new CoursBusinessImpl(new CoursDALImpl($db->getConnection()));
$villeBusiness = new VillesBusinessImpl(new VillesDALImpl($db->getConnection()));
$formateurBusiness = new FormateurBusinessImpl(new FormateurDALImpl($db->getConnection()));

$formationDates = $formationDateBusiness->getAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Calendrier - FormationPro</title>
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
                <li><a href="calendrier.php" class="active">Calendrier</a></li>
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
    <section class="calendar-section">
        <h1 class="section-title">📅 Calendrier des Formations</h1>
        <div class="calendar-table">
            <?php if (empty($formationDates)): ?>
                <p>Aucune formation programmée pour le moment.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Formation</th>
                            <th>Date</th>
                            <th>Mode</th>
                            <th>Formateur</th>
                            <th>Ville</th>
                            <th>Détails</th>
                            <th>Inscription</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($formationDates as $fd): 
                            $formation = $formationBusiness->getById($fd->formationId);
                            $cours = $coursBusiness->getById($formation->coursId);
                            $ville = $villeBusiness->getById($formation->villeId);
                            $formateur = $formateurBusiness->getById($formation->formateurId);
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($cours->name) ?></td>
                            <td><?= htmlspecialchars(date("d/m/Y", strtotime($fd->date))) ?></td>
                            <td><?= htmlspecialchars($formation->mode) ?></td>
                            <td><?= htmlspecialchars($formateur->firstName. ' ' .$formateur->lastName) ?></td>
                            <td><?= htmlspecialchars($ville->value) ?></td>
                            <td><a href="formationDetails.php?id=<?= $formation->id ?>" class="calendar-details-link">Voir</a></td>
                            <td><a href="inscription.php?dateId=<?= $fd->id ?>" class="calendar-details-link">S'inscrire</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </section>
</main>

<footer>
    <p>&copy; 2025 FormationPro. Tous droits réservés.</p>
</footer>

</body>
</html>
