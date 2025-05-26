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

$dao = new DomaineDALImpl($db->getConnection());
$business = new DomaineBusinessImpl($dao);

$domaines = $business->getAll();

$message = '';
if (isset($_GET['message'])) {
    switch ($_GET['message']) {
        case 'deleted':
            $message = 'Domaine supprimé avec succès.';
            break;
        case 'added':
            $message = 'Domaine ajouté avec succès.';
            break;
        case 'updated':
            $message = 'Domaine mis à jour avec succès.';
            break;
        case 'notfound':
            $message = 'Domaine non trouvé.';
            break;
        case 'cannot_delete_linked':
            $message = '❌ Impossible de supprimer ce domaine car il est utilisé dans un ou plusieurs sujets..';
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Domaines</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">

    <div class="max-w-5xl mx-auto bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">📚 Liste des Domaines</h1>
            <a href="addDomaine.php" class="text-blue-600 hover:underline font-medium">➕ Ajouter un Domaine</a>
        </div>

        <?php if ($message): ?>
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Nom du Domaine</th>
                    <th class="border px-4 py-2">Description</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($domaines as $domaine): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($domaine->id); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($domaine->name); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($domaine->description); ?></td>
                        <td class="border px-4 py-2 space-x-2">
                            <a href="detailsDomaine.php?id=<?php echo $domaine->id; ?>" class="text-blue-600 hover:underline">🔍 Détails</a>
                            <a href="editDomaine.php?id=<?php echo $domaine->id; ?>" class="text-yellow-500 hover:underline">✏️ Éditer</a>
                            <a href="deleteDomaine.php?id=<?php echo $domaine->id; ?>" class="text-red-500 hover:underline" onclick="return confirm('Voulez-vous vraiment supprimer ce domaine ?');">🗑️ Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($domaines)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">Aucun domaine trouvé.</td>
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
