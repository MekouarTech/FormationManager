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

$formationDates = $formationDateBusiness->getAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Dates de Formation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e6f0ff;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 960px;
            margin: 0 auto;
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        h1 {
            font-size: 26px;
            margin-bottom: 20px;
            color: #245caa;
        }

        .button {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 10px 16px;
            margin-bottom: 15px;
            text-decoration: none;
            border-radius: 4px;
        }

        .button:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .actions a {
            margin-right: 10px;
            text-decoration: none;
            font-size: 14px;
        }

        .view { color: #007bff; }
        .edit { color: #ffc107; }
        .delete { color: #dc3545; }

        .no-data {
            margin-top: 15px;
            color: #777;
        }

        .alert {
        padding: 15px 20px;
        border-radius: 4px;
        margin: 20px 0;
        font-family: Arial, sans-serif;
        font-size: 16px;
        border: 1px solid transparent;
        box-sizing: border-box;
        }
        .alert-info {
        position: relative;
        background-color: #d1ecf1;
        border-color: #bee5eb;
        color: #0c5460;
        padding-left: 45px;
        }

        .alert-info::before {
        content: "ℹ️";
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 20px;
        }

    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

    <div class="container">

    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-info">
            <?php
            switch ($_GET['message']) {
                case 'deleted':
                    echo "✅ Date de formation supprimée avec succès.";
                    break;
                case 'notfound':
                    echo "⚠️ Date de formation non trouvée.";
                    break;
                case 'error':
                    echo "❌ Une erreur est survenue.";
                    break;
                case 'cannot_delete_linked':
                    echo "❌ Impossible de supprimer cette date car elle est liée à une ou plusieurs inscriptions.";
                    break;
            }
            ?>
        </div>
    <?php endif; ?>


        <h1>📅 Dates de Formation</h1>

        <a href="addFormationDate.php" class="button">➕ Ajouter une date</a>

        <?php if (count($formationDates) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Formation</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($formationDates as $formationDate): 
                        $formation = $formationBusiness->getById($formationDate->formationId);
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($formationDate->id) ?></td>
                            <td><?= htmlspecialchars($formationDate->date) ?></td>
                            <td><?= $formation ? "Formation #" . $formation->id : 'Inconnue' ?></td>
                            <td class="actions">
                                <a href="detailsFormationDate.php?id=<?= $formationDate->id ?>" class="view">👁️ Voir</a>
                                <a href="editFormationDate.php?id=<?= $formationDate->id ?>" class="edit">✏️ Éditer</a>
                                <a href="deleteFormationDate.php?id=<?= $formationDate->id ?>" class="delete" onclick="return confirm('Voulez-vous vraiment supprimer cette date ?');">🗑️ Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">Aucune date de formation trouvée.</div>
        <?php endif; ?>
    </div>

    <div class="max-w-7xl mx-auto mt-6 text-center">
        <a href="../index.php" class="inline-block text-blue-600 hover:underline font-medium text-lg">
            ⬅️ Retour au Panneau Admin
        </a>
    </div>
</body>
</html>
