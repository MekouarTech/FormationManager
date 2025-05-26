<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/autoload.php';

$db = new Database();

$coursDao = new CoursDALImpl($db->getConnection());
$coursBusiness = new CoursBusinessImpl($coursDao);

$dao = new FormationDALImpl($db->getConnection());
$formationBusiness = new FormationBusinessImpl($dao);

$formationList = $formationBusiness->getAll();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Plateforme de Formation</title>
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
    <!-- Presentation with full slider -->
    <section class="presentation-slider hero-slider">
    <div class="slider-container full-slider">
        <div class="slider-overlay"></div>
        <div class="slider-content">
            <h1>Bienvenue chez FormationPro</h1>
            <p>
                FormationPro est un centre de formation professionnel dédié à l'excellence. Nous proposons des
                programmes de formation de haute qualité adaptés aux besoins des professionnels dans différents
                domaines. Notre mission est de développer les compétences pour garantir la réussite professionnelle.
            </p>
        </div>
        <div class="slider">
            <img src="images/training1.jpeg" alt="Formation Slide 1">
            <img src="images/training2.jpg" alt="Formation Slide 2">
            <img src="images/training3.jpg" alt="Formation Slide 3">
        </div>
    </div>
</section>


<!-- Article-style mini slider -->
<section class="slider-section photo-gallery">
    <h2>Photos des formations précédentes</h2>
    <div class="gallery-grid">
        <div class="gallery-item">
            <img src="images/training1.jpeg" alt="Formation 1" />
            <p>Formation en communication</p>
        </div>
        <div class="gallery-item">
            <img src="images/training2.jpg" alt="Formation 2" />
            <p>Atelier de développement web</p>
        </div>
        <div class="gallery-item">
            <img src="images/training3.jpg" alt="Formation 3" />
            <p>Formation en leadership</p>
        </div>
    </div>
</section>

    <!-- Performance metrics -->
    <section class="performance" id="performance">
    <h2>Mesures de performance</h2>
    <div class="metrics">
        <div class="metric">
        <span class="counter" data-target="95">0</span><span>%</span><br />
        <p>Taux de satisfaction</p>
        </div>
        <div class="metric">
        <span class="counter" data-target="90">0</span><span>%</span><br />
        <p>Taux de succès</p>
        </div>
        <div class="metric">
        <span class="counter" data-target="100">0</span><span>%</span><br />
        <p>Couverture des domaines</p>
        </div>
    </div>
    </section>

        <!-- Formations articles -->
    <section class="formations-articles">
        <h2>Nos Formations</h2>
        <div class="articles-gallery">
            <?php
            $maxFormations = array_slice($formationList, 0, 6);
            foreach ($maxFormations as $formation): ?>
            
            <?php
                $cours = $coursBusiness->getById($formation->coursId);
            ?>

                <article class="gallery-item">
                    <?php if ($cours->logo): ?>
                        <img src="data:image/jpeg;base64,<?= base64_encode($cours->logo) ?>" alt="Logo" class="w-12 h-12 object-cover rounded">
                    <?php else: ?>
                        <img src="placeholder.jpg" alt="No Logo">
                    <?php endif; ?>
                    
                    <h3>
                        <a href="formationDetails.php?id=<?= $formation->id ?>">
                        <?= htmlspecialchars($cours->name) ?>
                        </a>
                    </h3>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="voir-plus">
            <a href="formations.php">Voir plus &rarr;</a>
        </div>
    </section>

    <!-- Useful links -->
    <section class="useful-links">
        <h2>Liens utiles</h2>
        <ul>
            <li><a href="login.php">Accès à votre compte</a></li>
            <li><a href="formations.php">Catalogue des formations</a></li>
            <li><a href="contact.php">Support technique</a></li>
        </ul>
    </section>

</main>

<footer>
    <p>&copy; 2025 FormationPro. Tous droits réservés.</p>
</footer>

<script src="js/slider.js"></script>
</body>
</html>
