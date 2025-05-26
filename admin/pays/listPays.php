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

$dao = new PaysDALImpl($db->getConnection());
$business = new PaysBusinessImpl($dao);

$paysList = $business->getAll();

$message = '';
if (isset($_GET['message'])) {
    switch ($_GET['message']) {
        case 'deleted':
            $message = 'Pays supprimé avec succès.';
            break;
        case 'added':
            $message = 'Pays ajouté avec succès.';
            break;
        case 'updated':
            $message = 'Pays mis à jour avec succès.';
            break;
        case 'notfound':
            $message = 'Pays non trouvé.';
            break;
        case 'cannot_delete_linked':
            $message = '❌ Impossible de supprimer ce pay car il est utilisé dans une ou plusieurs villes.';
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Pays</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">

<div class="max-w-5xl mx-auto bg-white shadow-md rounded-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Liste des Pays</h1>
        <a href="addPays.php" class="text-blue-600 hover:underline font-medium">➕ Ajouter un Pays</a>
    </div>

    <?php if ($message): ?>
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <table class="w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Nom</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($paysList as $pays): ?>
                <tr class="hover:bg-gray-50">
                    <td class="border px-4 py-2"><?= htmlspecialchars($pays->id) ?></td>
                    <td class="border px-4 py-2"><?= htmlspecialchars($pays->value) ?></td>
                    <td class="border px-4 py-2 space-x-2">
                        <a href="detailsPays.php?id=<?= $pays->id ?>" class="text-blue-600 hover:underline">🔍 Détails</a>
                        <a href="editPays.php?id=<?= $pays->id ?>" class="text-yellow-500 hover:underline">✏️ Éditer</a>
                        <a href="deletePays.php?id=<?= $pays->id ?>" class="text-red-500 hover:underline" onclick="return confirm('Voulez-vous vraiment supprimer ce pays ?');">🗑️ Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($paysList)): ?>
                <tr>
                    <td colspan="3" class="text-center py-4 text-gray-500">Aucun pays trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<div class="max-w-7xl mx-auto mt-6 text-center">
    <a href="../index.php" class="inline-block text-blue-600 hover:underline font-medium text-lg">
        ⬅️ Retour au Panneau Admin
    </a>
</div>

</body>
</html>
