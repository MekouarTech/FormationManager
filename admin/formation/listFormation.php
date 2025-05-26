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

$formateurDao = new FormateurDALImpl($db->getConnection());
$formateurBusiness = new FormateurBusinessImpl($formateurDao);

$villeDao = new VillesDALImpl($db->getConnection());
$villeBusiness = new VillesBusinessImpl($villeDao);

$formationDao = new FormationDALImpl($db->getConnection());
$formationBusiness = new FormationBusinessImpl($formationDao);

$formationList = $formationBusiness->getAll();

$message = '';
if (isset($_GET['message'])) {
    switch ($_GET['message']) {
        case 'deleted':
            $message = 'Formation supprimée avec succès.';
            break;
        case 'added':
            $message = 'Formation ajoutée avec succès.';
            break;
        case 'updated':
            $message = 'Formation mise à jour avec succès.';
            break;
        case 'notfound':
            $message = 'Formation non trouvée.';
            break;
        case 'cannot_delete_linked':
            $message = '❌ Impossible de supprimer cette formation car elle est liée à une ou plusieurs dates.';
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Formations</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">

    <div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Liste des Formations</h1>
            <a href="addFormation.php" class="text-blue-600 hover:underline font-medium">➕ Ajouter une Formation</a>
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
                    <th class="border px-4 py-2">Prix</th>
                    <th class="border px-4 py-2">Mode</th>
                    <th class="border px-4 py-2">Cours</th>
                    <th class="border px-4 py-2">Formateur</th>
                    <th class="border px-4 py-2">Ville</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($formationList as $formation): ?>
                    <?php
                        $cours = $coursBusiness->getById($formation->coursId);
                        $formateur = $formateurBusiness->getById($formation->formateurId);
                        $ville = $villeBusiness->getById($formation->villeId);
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2"><?= htmlspecialchars($formation->id) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($formation->price) ?> Dh</td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($formation->mode) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($cours->name ?? 'Inconnu') ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($formateur->firstName .' '. $formateur->lastName ?? 'Inconnu') ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($ville->value ?? 'Inconnu') ?></td>
                        <td class="border px-4 py-2 space-x-2">
                            <a href="detailsFormation.php?id=<?= $formation->id ?>" class="text-blue-600 hover:underline">🔍 Détails</a>
                            <a href="editFormation.php?id=<?= $formation->id ?>" class="text-yellow-500 hover:underline">✏️ Éditer</a>
                            <a href="deleteFormation.php?id=<?= $formation->id ?>" class="text-red-500 hover:underline" onclick="return confirm('Voulez-vous vraiment supprimer cette formation ?');">🗑️ Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($formationList)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-500">Aucune formation trouvée.</td>
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
