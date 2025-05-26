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

if (!isset($_GET['id'])) {
    header("Location: listPays.php?message=invalid");
    exit;
}

$id = $_GET['id'];

$dao = new PaysDALImpl($db->getConnection());
$business = new PaysBusinessImpl($dao);

$pays = $business->getById($id);

if (!$pays) {
    header("Location: listPays.php?message=notfound");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Pays</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-8">
        <h1 class="text-3xl font-bold mb-6 text-blue-700">🌍 Détails du Pays</h1>

        <div class="grid md:grid-cols-2 gap-8">
            <div>
                <div class="mb-4">
                    <h2 class="text-sm text-gray-500 font-semibold uppercase">ID</h2>
                    <p class="text-lg font-medium text-gray-800"><?php echo htmlspecialchars($pays->id); ?></p>
                </div>
                <div class="mb-4">
                    <h2 class="text-sm text-gray-500 font-semibold uppercase">Value</h2>
                    <p class="text-lg font-medium text-gray-800"><?php echo htmlspecialchars($pays->value); ?></p>
                </div>
            </div>

        </div>

        <div class="mt-8 flex space-x-4">
            <a href="editPays.php?id=<?php echo $pays->id; ?>" 
               class="text-yellow-600 font-semibold hover:underline text-base">✏️ Éditer</a>
            <a href="deletePays.php?id=<?php echo $pays->id; ?>" 
               class="text-red-600 font-semibold hover:underline text-base"
               onclick="return confirm('Voulez-vous vraiment supprimer ce pays ?');">🗑️ Supprimer</a>
            <a href="listPays.php" 
               class="text-blue-600 font-semibold hover:underline text-base">🔙 Retour à la liste</a>
        </div>
    </div>
</body>
</html>
