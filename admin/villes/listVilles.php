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

$paysDao = new PaysDALImpl($db->getConnection());
$paysBusiness = new PaysBusinessImpl($paysDao);

$villeDao = new VillesDALImpl($db->getConnection());
$villeBusiness = new VillesBusinessImpl($villeDao);

$villeList = $villeBusiness->getAll();

$message = '';
if (isset($_GET['message'])) {
    switch ($_GET['message']) {
        case 'deleted':
            $message = 'Ville supprimée avec succès.';
            break;
        case 'added':
            $message = 'Ville ajoutée avec succès.';
            break;
        case 'updated':
            $message = 'Ville mise à jour avec succès.';
            break;
        case 'notfound':
            $message = 'Ville non trouvée.';
            break;
        case 'cannot_delete_linked':
            $message = '❌ Impossible de supprimer cette ville car elle est liée à une ou plusieurs formations.';
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Villes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">

    <div class="max-w-5xl mx-auto bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Liste des Villes</h1>
            <a href="addVille.php" class="text-blue-600 hover:underline font-medium">➕ Ajouter une Ville</a>
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
                    <th class="border px-4 py-2">Ville</th>
                    <th class="border px-4 py-2">Pays</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($villeList as $ville): ?>
                    <?php
                        $pays = $paysBusiness->getById($ville->paysId);
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2"><?= htmlspecialchars($ville->id) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($ville->value) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($pays->value) ?></td>
                        <td class="border px-4 py-2 space-x-2">
                            <a href="detailsVille.php?id=<?= $ville->id ?>" class="text-blue-600 hover:underline">🔍 Détails</a>
                            <a href="editVille.php?id=<?= $ville->id ?>" class="text-yellow-500 hover:underline">✏️ Éditer</a>
                            <a href="deleteVille.php?id=<?= $ville->id ?>" class="text-red-500 hover:underline" onclick="return confirm('Voulez-vous vraiment supprimer cette ville ?');">🗑️ Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($villeList)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">Aucune ville trouvée.</td>
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
