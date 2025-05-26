<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/autoload.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['dateId'])) {
    echo "Formation invalide.";
    exit();
}

$formationDateId = intval($_GET['dateId']);
$db = new Database();
$connection = $db->getConnection();

$user = $_SESSION['user'];
$inscriptionBusiness = new InscriptionBusinessImpl(new InscriptionDALImpl($connection));

$message = null;
$error = null;

$isAlreadyRegistered = $inscriptionBusiness->isUserAlreadyRegistered($user['email'], $formationDateId);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$isAlreadyRegistered) {
    $phone = $_POST['phone'] ?? null;
    $company = $_POST['company'] ?? null;

    // PHP regex validation
    if (!preg_match('/^\+?[0-9]{8,15}$/', $phone)) {
        $error = "Numéro de téléphone invalide. Exemple : +212600000000 ou 0600000000.";
    } else {
        $inscription = new Inscription(
            null,
            $user['firstName'],
            $user['lastName'],
            $phone,
            $user['email'],
            $company,
            false,
            $formationDateId
        );

        $inscriptionBusiness->insert($inscription);
        $message = "🎉 Inscription réussie !";
        $isAlreadyRegistered = true;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - FormationPro</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        function validatePhoneForm(event) {
            const phoneInput = document.querySelector('input[name="phone"]');
            const phoneRegex = /^\+?[0-9]{8,15}$/;
            if (!phoneRegex.test(phoneInput.value)) {
                alert("Veuillez entrer un numéro de téléphone valide. Exemple : +212600000000 ou 0600000000.");
                event.preventDefault();
                return false;
            }
            return true;
        }
    </script>
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
    <section class="section-message">
        <?php if ($isAlreadyRegistered): ?>
            <h1>✅ Vous êtes déjà inscrit à cette formation.</h1>
        <?php elseif ($message): ?>
            <h1><?= $message ?></h1>
        <?php else: ?>
            <h1>Formulaire d'inscription</h1>

            <?php if ($error): ?>
                <p style="color:red;"><strong><?= htmlspecialchars($error) ?></strong></p>
            <?php endif; ?>

            <form method="post" action="" onsubmit="return validatePhoneForm(event)">
                <div>
                    <label>Nom:</label>
                    <input type="text" value="<?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName']) ?>" disabled>
                </div>
                <div>
                    <label>Email:</label>
                    <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                </div>
                <div>
                    <label>Téléphone:</label>
                    <input type="text" name="phone" required pattern="^\+?[0-9]{8,15}$" title="Exemple : +212600000000 ou 0600000000">
                </div>
                <div>
                    <label>Société:</label>
                    <input type="text" name="company">
                </div>
                <div>
                    <button type="submit">Valider l'inscription</button>
                </div>
            </form>
        <?php endif; ?>
        <p><a href="listInscriptions.php">Mes Inscriptions</a></p>
    </section>
</main>

<footer>
    <p>&copy; 2025 FormationPro. Tous droits réservés.</p>
</footer>

</body>
</html>
