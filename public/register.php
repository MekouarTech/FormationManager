<?php
    
require_once __DIR__ . '../../config/autoload.php';
require_once __DIR__ . '../../config/database.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
    $db = new Database();
    $pdo = $db->getConnection();
    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPwd = $_POST['confirmpassword'];
    $roleId = 1; // 1 = client role
    $errors = '';
    $success = '';
    $register = $_POST['register'];

    if(isset($register)) {
        
        if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPwd)) {
            $errors = "Tous les champs sont obligatoires.";
        } elseif ($password !== $confirmPwd) {
            $errors = "Les mots de passe ne correspondent pas.";
        } else {
            $stmt = $pdo->prepare("SELECT id FROM Users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            if ($stmt->fetch()) {
                $errors = "Email déjà utilisé.";
            } else {
                // Hash password
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                $sql = "INSERT INTO Users (username,password,firstName, lastName, email, roleId,createdAt)
                        VALUES (:username, :password,:firstName, :lastName, :email, :roleId, :createdAt)";

                $stmt = $pdo->prepare($sql);

                $success = $stmt->execute([
                    'username'   => $email,
                    'firstName'  => $firstName,
                    'lastName'   => $lastName,
                    'email'      => $email,
                    'password'   => $hashedPassword,
                    'roleId'     => $roleId,
                    'createdAt'  => date('Y-m-d H:i:s')
                ]);

                if ($success) {
                    header('Location: login.php');
                    exit;
                } else {
                    $errors = "Erreur lors de l'inscription. Veuillez réessayer.";
                }
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
    <title>Inscription</title>
    <link rel="stylesheet" href="css/register.css">
    <script defer src="js/register.js"></script>
</head>
<body>

    <div class="container">
        <h1>Inscription</h1>

        <form id="registerForm" action="" method="POST">

            <label for="firstname">Prénom :</label>
            <input type="text" id="firstname" name="firstname" required>

            <label for="lastname">Nom :</label>
            <input type="text" id="lastname" name="lastname" required>

            <label for="email">Adresse e-mail :</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>

            <label for="confirmpassword">Confirmer le mot de passe :</label>
            <input type="password" id="confirmpassword" name="confirmpassword" required>

            <input type="submit" name="register" value="S'inscrire">
        </form>

        <p>Vous avez déjà un compte ? <a href="login.php">Connectez-vous ici</a>.</p>
        <!--p><a href="/">Retour à l'accueil</a></p-->
    </div>

</body>
</html>
