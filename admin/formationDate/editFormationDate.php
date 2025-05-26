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
$formations = $formationBusiness->getAll();

if ($id) {
    $formationDate = $formationDateBusiness->getById($id);
    if (!$formationDate) {
        header("Location: listFormationDate.php?message=notfound");
        exit;
    }
} else {
    header("Location: listFormationDate.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newDate = $_POST['date'];
    $newFormationId = $_POST['formationId'];

    if ($newDate && $newFormationId) {
        $formationDate->date = $newDate;
        $formationDate->formationId = $newFormationId;

        $formationDateBusiness->update($formationDate);
        header("Location: listFormationDate.php?message=updated");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier la Date de Formation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef4ff;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            background-color: #fff;
            margin: auto;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h1 {
            color: #245caa;
            font-size: 24px;
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .actions {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
        }

        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #28a745;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #218838;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
        }

        .btn-secondary:hover {
            background-color: #545b62;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>✏️ Modifier la Date de Formation</h1>

    <form method="POST">
        <label for="date">Date</label>
        <input type="date" id="date" name="date" value="<?= htmlspecialchars($formationDate->date) ?>" required>

        <label for="formationId">Formation</label>
        <select name="formationId" id="formationId" required>
            <option value="">-- Sélectionner une formation --</option>
            <?php foreach ($formations as $formation): ?>
                <option value="<?= $formation->id ?>" <?= $formation->id == $formationDate->formationId ? 'selected' : '' ?>>
                    Formation #<?= $formation->id ?> - <?= htmlspecialchars($formation->mode) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="actions">
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="listFormationDate.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

</body>
</html>
