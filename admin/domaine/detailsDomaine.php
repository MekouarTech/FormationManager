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

$domaineId = isset($_GET['id']) ? $_GET['id'] : null;

$domaine = null;

if ($domaineId) {
    $domaine = $business->getById($domaineId);
    if (!$domaine) {
        header("Location: listDomaines.php?message=notfound");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Domaine</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">

    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-8">
        <h1 class="text-3xl font-bold mb-6 text-blue-700">📘 Détails du Domaine</h1>

        <?php if ($domaine): ?>
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <div class="mb-4">
                        <h2 class="text-sm text-gray-500 font-semibold uppercase">Nom du Domaine</h2>
                        <p class="text-lg font-medium text-gray-800"><?php echo htmlspecialchars($domaine->__get('name')); ?></p>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-sm text-gray-500 font-semibold uppercase">Description</h2>
                        <p class="text-lg font-medium text-gray-800 whitespace-pre-line"><?php echo htmlspecialchars($domaine->__get('description')); ?></p>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <a href="editDomaine.php?id=<?php echo $domaine->__get('id'); ?>" 
                   class="text-yellow-600 font-semibold hover:underline text-base">✏️ Éditer</a>
                <a href="deleteDomaine.php?id=<?php echo $domaine->__get('id'); ?>" 
                   class="text-red-600 font-semibold hover:underline text-base"
                   onclick="return confirm('Voulez-vous vraiment supprimer ce domaine ?');">🗑️ Supprimer</a>
                <a href="listDomaines.php" 
                   class="text-blue-600 font-semibold hover:underline text-base">🔙 Retour à la liste</a>
            </div>
        <?php else: ?>
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">Domaine non trouvé.</div>
        <?php endif; ?>
    </div>

</body>
</html>
