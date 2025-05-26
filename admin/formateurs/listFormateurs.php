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

$dao = new FormateurDALImpl($db->getConnection());
$business = new FormateurBusinessImpl($dao);

$formateurs = $business->getAll();

$message = isset($_GET['message']) ? $_GET['message'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Liste des Formateurs</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8 bg-blue-50">

    <h1 class="text-2xl font-bold mb-6">Liste des Formateurs</h1>

    <?php if ($message === 'deleted'): ?>
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded">
            Formateur supprimé avec succès !
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['message'])): ?>
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded">
            <?php
            switch ($_GET['message']) {
                case 'deleted':
                    echo "✅ Formateur supprimé avec succès.";
                    break;
                case 'error':
                    echo "❌ Une erreur est survenue lors de la suppression.";
                    break;
                case 'notfound':
                    echo "⚠️ Formateur non trouvé.";
                    break;
                case 'cannot_delete_linked':
                    echo "❌ Impossible de supprimer ce formateur car il est assigné à une ou plusieurs formations.";
                    break;
            }
            ?>
        </div>
    <?php endif; ?>

    <a href="addFormateur.php" class="inline-block bg-blue-500 text-white px-4 py-2 rounded mb-4">Ajouter un Formateur</a>

    <table class="min-w-full bg-white border border-gray-300 rounded">
        <thead>
            <tr>
                <th class="border px-4 py-2">#</th>
                <th class="border px-4 py-2">Prénom</th>
                <th class="border px-4 py-2">Nom</th>
                <th class="border px-4 py-2">Description</th>
                <th class="border px-4 py-2">Photo</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($formateurs as $formateur): ?>
                <tr>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($formateur->id); ?></td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($formateur->firstName); ?></td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($formateur->lastName); ?></td>
                    <td class="border px-4 py-2">
                        <?php
                            $desc = htmlspecialchars($formateur->description);
                            echo strlen($desc) > 50 ? substr($desc, 0, 50) . '...' : $desc;
                        ?>
                    </td>
                    
                    <td class="py-3 px-4">
                        <?php if ($formateur->photo): ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($formateur->photo) ?>" alt="Photo" class="w-16 h-16 object-cover rounded">
                        <?php else: ?>
                            <div class="w-16 h-16 bg-gray-200 text-sm flex items-center justify-center text-gray-500 rounded">No Image</div>
                        <?php endif; ?>
                    </td>

                    <td class="border px-4 py-2 space-x-4">
                        <a href="detailsFormateur.php?id=<?php echo $formateur->id; ?>" class="text-blue-600 hover:underline">📄 Détails</a>
                        <a href="editFormateur.php?id=<?php echo $formateur->id; ?>" class="text-green-500 hover:underline">✏️ Éditer</a>
                        <a href="deleteFormateur.php?id=<?php echo $formateur->id; ?>" class="text-red-500 hover:underline" onclick="return confirm('Voulez-vous vraiment supprimer ce formateur ?');">🗑️ Supprimer</a>
                    </td>


                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    
    <div class="max-w-7xl mx-auto mt-6 text-center">
        <a href="../index.php" class="inline-block text-blue-600 hover:underline font-medium text-lg">
            ⬅️ Retour au Panneau Admin
        </a>
    </div>

</body>
</html>