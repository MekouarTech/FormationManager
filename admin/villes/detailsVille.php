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

$dao = new VillesDALImpl($db->getConnection());
$business = new VillesBusinessImpl($dao);

$villeId = isset($_GET['id']) ? $_GET['id'] : null;

$ville = null;
$pays = null;

if ($villeId) {
    $ville = $business->getById($villeId);
    if ($ville) {
        $pays = $business->getPaysById($ville->__get('paysId'));
        echo $pays->value;
    } else {
        header("Location: listVilles.php?message=notfound");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de la Ville</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">

    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-8">
        <h1 class="text-3xl font-bold mb-6 text-blue-700">🏙️ Détails de la Ville</h1>

        <?php if ($ville): ?>
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <div class="mb-4">
                        <h2 class="text-sm text-gray-500 font-semibold uppercase">Nom de la Ville</h2>
                        <p class="text-lg font-medium text-gray-800"><?php echo htmlspecialchars($ville->__get('value')); ?></p>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-sm text-gray-500 font-semibold uppercase">Pays</h2>
                        <p class="text-lg font-medium text-gray-800">
                            <?php echo $pays ? htmlspecialchars($pays->__get('value')) : 'Non défini'; ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <a href="editVille.php?id=<?php echo $ville->__get('id'); ?>" 
                   class="text-yellow-600 font-semibold hover:underline text-base">✏️ Éditer</a>
                <a href="deleteVille.php?id=<?php echo $ville->__get('id'); ?>" 
                   class="text-red-600 font-semibold hover:underline text-base"
                   onclick="return confirm('Voulez-vous vraiment supprimer cette ville ?');">🗑️ Supprimer</a>
                <a href="listVilles.php" 
                   class="text-blue-600 font-semibold hover:underline text-base">🔙 Retour à la liste</a>
            </div>
        <?php else: ?>
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">Ville non trouvée.</div>
        <?php endif; ?>
    </div>

</body>
</html>
