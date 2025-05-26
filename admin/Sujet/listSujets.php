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

$domaineDao = new DomaineDALImpl($db->getConnection());
$domaineBusiness = new DomaineBusinessImpl($domaineDao);

$sujetDao = new SujetDALImpl($db->getConnection());
$sujetBusiness = new SujetBusinessImpl($sujetDao);

$sujetList = $sujetBusiness->getAll();

$message = '';
if (isset($_GET['message'])) {
    switch ($_GET['message']) {
        case 'deleted':
            $message = 'Sujet supprimé avec succès.';
            break;
        case 'added':
            $message = 'Sujet ajouté avec succès.';
            break;
        case 'updated':
            $message = 'Sujet mis à jour avec succès.';
            break;
        case 'notfound':
            $message = 'Sujet non trouvé.';
            break;
        case 'cannot_delete_linked':
            $message = '❌ Impossible de supprimer ce sujet car il est lié à un ou plusieurs cours.';
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Sujets</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">

    <div class="max-w-5xl mx-auto bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Liste des Sujets</h1>
            <a href="addSujet.php" class="text-blue-600 hover:underline font-medium">➕ Ajouter un Sujet</a>
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
                    <th class="border px-4 py-2">Description courte</th>
                    <th class="border px-4 py-2">Description longue</th>
                    <th class="border px-4 py-2">Bénéfice Individuel</th>
                    <th class="border px-4 py-2">Bénéfice Business</th>
                    <th class="border px-4 py-2">Logo</th>
                    <th class="border px-4 py-2">Domaine</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sujetList as $sujet): ?>
                    <?php
                        $domaine = $domaineBusiness->getById($sujet->domaineId);
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2"><?= htmlspecialchars($sujet->id) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($sujet->name) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($sujet->shortDescription) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($sujet->longDescription) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($sujet->individualBenefit) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($sujet->businessBenefit) ?></td>
                        <td class="border px-4 py-2">
                            <?php if ($sujet->logo): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($sujet->logo) ?>" alt="Logo" class="w-16 h-16 object-cover rounded">
                            <?php else: ?>
                                <div class="w-16 h-16 bg-gray-200 text-sm flex items-center justify-center text-gray-500 rounded">No Logo</div>
                            <?php endif; ?>
                        </td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($domaine->name) ?></td>
                        <td class="border px-4 py-2 space-x-2">
                            <a href="detailsSujet.php?id=<?= $sujet->id ?>" class="text-blue-600 hover:underline">🔍 Détails</a>
                            <a href="editSujet.php?id=<?= $sujet->id ?>" class="text-yellow-500 hover:underline">✏️ Éditer</a>
                            <a href="deleteSujet.php?id=<?= $sujet->id ?>" class="text-red-500 hover:underline" onclick="return confirm('Voulez-vous vraiment supprimer ce sujet ?');">🗑️ Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($sujetList)): ?>
                    <tr>
                        <td colspan="9" class="text-center py-4 text-gray-500">Aucun sujet trouvé.</td>
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
