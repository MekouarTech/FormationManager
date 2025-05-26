<?php
require_once '../../classes/services/AuthService.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/database.php';


$db = new Database();

$auth = new AuthService($db->getConnection());

if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    header("Location: ../../public/login.php");
    exit();
}

$formationDateDao = new FormationDateDALImpl($db->getConnection());
$formationDateBusiness = new FormationDateBusinessImpl($formationDateDao);

$formationDao = new FormationDALImpl($db->getConnection());
$formationBusiness = new FormationBusinessImpl($formationDao);

$id = isset($_GET['id']) ? $_GET['id'] : null;
$formationDate = null;
$formation = null;

if ($id) {
    $formationDate = $formationDateBusiness->getById($id);
    if ($formationDate) {
        $formation = $formationBusiness->getById($formationDate->formationId);
    } else {
        header("Location: listFormationDate.php?message=notfound");
        exit;
    }
} else {
    header("Location: listFormationDate.php?message=invalid");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de la Date de Formation</title>
    <style>
        body {
            background-color: #f0f4f8;
            font-family: Arial, sans-serif;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            background: #ffffff;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1 {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .field {
            margin-bottom: 15px;
        }
        .label {
            color: #555;
            font-weight: bold;
            font-size: 14px;
        }
        .value {
            font-size: 16px;
            color: #333;
        }
        .actions {
            margin-top: 25px;
        }
        .actions a {
            margin-right: 15px;
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }
        .actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📅 Détails de la Date de Formation</h1>

        <div class="field">
            <div class="label">Date</div>
            <div class="value"><?= htmlspecialchars($formationDate->date) ?></div>
        </div>

        <div class="field">
            <div class="label">Formation liée</div>
            <div class="value">
                <?php if ($formation): ?>
                    ID #<?= htmlspecialchars($formation->id) ?> - Mode: <?= htmlspecialchars($formation->mode) ?> - Prix: <?= htmlspecialchars($formation->price) ?>
                <?php else: ?>
                    Formation non trouvée
                <?php endif; ?>
            </div>
        </div>

        <div class="actions">
            <a href="editFormationDate.php?id=<?= $formationDate->id ?>">✏️ Éditer</a>
            <a href="deleteFormationDate.php?id=<?= $formationDate->id ?>" onclick="return confirm('Supprimer cette date de formation ?');">🗑️ Supprimer</a>
            <a href="listFormationDate.php">🔙 Retour à la liste</a>
        </div>
    </div>
</body>
</html>
