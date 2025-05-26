<?php

require_once __DIR__ . '../../classes/services/AuthService.php';
require_once __DIR__ . '../../config/autoload.php';
require_once __DIR__ . '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $pdo = $db->getConnection();

    $email = $_POST['email'];
    $password = $_POST['password'];
    $errors = '';

    $login = $_POST['Login'];

    if (isset($login)) {
        if (empty($email) || empty($password)) {
            $errors = "Tous les champs sont obligatoires.";
        } else {
            $authService = new AuthService($pdo);

            if ($authService->login($email, $password)) {
                if ($authService->isAdmin()) {
                    header('Location: ../admin/index.php');
                } else {
                    header('Location: index.php');
                }
                exit;
            } else {
                $errors = "Identifiants invalides.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>

<div class="container">
    <h1>Connexion</h1>

    <form id="loginForm" action="" method="POST">
        <label for="email">Adresse e-mail / nom d'utilisateur :</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" name="Login" value="Se connecter">
    </form>

    <?php if (!empty($errors)): ?>
        <p style="color:red;"><?= htmlspecialchars($errors) ?></p>
    <?php endif; ?>

    <p>Vous n'avez pas de compte ? <a href="register.php">Inscrivez-vous ici</a>.</p>
</div>

</body>
</html>
