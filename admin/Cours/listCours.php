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

$coursDao = new CoursDALImpl($db->getConnection());
$coursBusiness = new CoursBusinessImpl($coursDao);

$sujetDao = new SujetDALImpl($db->getConnection());
$sujetBusiness = new SujetBusinessImpl($sujetDao);

$coursList = $coursBusiness->getAll();

$message = '';
if (isset($_GET['message'])) {
    switch ($_GET['message']) {
        case 'deleted':
            $message = '✅ Cours supprimé avec succès.';
            break;
        case 'added':
            $message = '✅ Cours ajouté avec succès.';
            break;
        case 'updated':
            $message = '✅ Cours mis à jour avec succès.';
            break;
        case 'notfound':
            $message = '⚠️ Cours non trouvé.';
            break;
        case 'cannot_delete_linked':
            $message = '❌ Impossible de supprimer ce cours car il est utilisé dans une ou plusieurs formations.';
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Cours</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">

    <div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Liste des Cours</h1>
            <a href="addCours.php" class="text-blue-600 hover:underline font-medium">➕ Ajouter un Cours</a>
        </div>

        <?php if ($message): ?>
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <table class="w-full table-auto border-collapse text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-3 py-2">ID</th>
                    <th class="border px-3 py-2">Nom</th>
                    <th class="border px-3 py-2">Contenu</th>
                    <th class="border px-3 py-2">Description</th>
                    <th class="border px-3 py-2">Audience</th>
                    <th class="border px-3 py-2">Durée</th>
                    <th class="border px-3 py-2">Test Inclus</th>
                    <th class="border px-3 py-2">Contenu du Test</th>
                    <th class="border px-3 py-2">Logo</th>
                    <th class="border px-3 py-2">Sujet</th>
                    <th class="border px-3 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($coursList as $cours): ?>
                    <?php
                        $sujet = $sujetBusiness->getById($cours->sujetId);
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="border px-3 py-2"><?= htmlspecialchars($cours->id) ?></td>
                        <td class="border px-3 py-2"><?= htmlspecialchars($cours->name) ?></td>
                        <td class="border px-3 py-2"><?= htmlspecialchars($cours->content) ?></td>
                        <td class="border px-3 py-2"><?= htmlspecialchars($cours->description) ?></td>
                        <td class="border px-3 py-2"><?= htmlspecialchars($cours->audience) ?></td>
                        <td class="border px-3 py-2"><?= htmlspecialchars($cours->duration) ?>h</td>
                        <td class="border px-3 py-2"><?= $cours->testIncluded ? 'Oui' : 'Non' ?></td>
                        <td class="border px-3 py-2"><?= htmlspecialchars($cours->testContent) ?></td>
                        <td class="border px-3 py-2">
                            <?php if ($cours->logo): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($cours->logo) ?>" alt="Logo" class="w-12 h-12 object-cover rounded">
                            <?php else: ?>
                                <div class="w-12 h-12 bg-gray-200 flex items-center justify-center text-gray-500 rounded">No Logo</div>
                            <?php endif; ?>
                        </td>
                        <td class="border px-3 py-2"><?= htmlspecialchars($sujet->name) ?></td>
                        <td class="border px-3 py-2 space-x-1">
                            <a href="detailsCours.php?id=<?= $cours->id ?>" class="text-blue-600 hover:underline">🔍</a>
                            <a href="editCours.php?id=<?= $cours->id ?>" class="text-yellow-500 hover:underline">✏️</a>
                            <a href="deleteCours.php?id=<?= $cours->id ?>" class="text-red-500 hover:underline" onclick="return confirm('Voulez-vous vraiment supprimer ce cours ?');">🗑️</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($coursList)): ?>
                    <tr>
                        <td colspan="11" class="text-center py-4 text-gray-500">Aucun cours trouvé.</td>
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
