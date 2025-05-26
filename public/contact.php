<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/autoload.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contact - FormationPro</title>
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
                <li><a class="active" href="contact.php">Contact</a></li>
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
    <section class="contact-section">
        <h1>Contactez-nous</h1>
        <p>Vous avez des questions ? Remplissez le formulaire ci-dessous et nous vous répondrons rapidement.</p>

        <form id="contactForm" method="POST" class="contact-form">
            <div class="form-group">
                <label for="name">Nom complet</label>
                <input type="text" id="name" name="name" required placeholder="Votre nom complet">
            </div>
            <div class="form-group">
                <label for="email">Adresse e-mail</label>
                <input type="email" id="email" name="email" required placeholder="votre@email.com">
            </div>
            <div class="form-group">
                <label for="subject">Sujet</label>
                <input type="text" id="subject" name="subject" required placeholder="Objet du message">
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="6" required placeholder="Votre message ici..."></textarea>
            </div>
            <button type="submit" class="btn">Envoyer</button>
            <div id="formResponse" style="margin-top: 10px;"></div>
        </form>
    </section>


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

<script>
document.getElementById('contactForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const responseBox = document.getElementById('formResponse');

    fetch('send_contact.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        responseBox.textContent = data.message;
        responseBox.style.color = data.success ? 'green' : 'red';
        if (data.success) {
            form.reset();
        }
    })
    .catch(error => {
        responseBox.textContent = "Une erreur s'est produite.";
        responseBox.style.color = 'red';
    });
});
</script>


</body>
</html>
