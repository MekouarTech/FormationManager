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
    header("Location: listFormateurs.php?message=invalid");
    exit;
}

$id = $_GET['id'];

$dao = new FormateurDALImpl($db->getConnection());
$business = new FormateurBusinessImpl($dao);

$formateur = $business->getById($id);

if (!$formateur) {
    header("Location: listFormateurs.php?message=notfound");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Formateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-8">
        <h1 class="text-3xl font-bold mb-6 text-blue-700">👤 Détails du Formateur</h1>

        <div class="grid md:grid-cols-2 gap-8">
            <div>
                <div class="mb-4">
                    <h2 class="text-sm text-gray-500 font-semibold uppercase">Prénom</h2>
                    <p class="text-lg font-medium text-gray-800"><?php echo htmlspecialchars($formateur->firstName); ?></p>
                </div>
                <div class="mb-4">
                    <h2 class="text-sm text-gray-500 font-semibold uppercase">Nom</h2>
                    <p class="text-lg font-medium text-gray-800"><?php echo htmlspecialchars($formateur->lastName); ?></p>
                </div>
                <div class="mb-4">
                    <h2 class="text-sm text-gray-500 font-semibold uppercase">Description</h2>
                    <p class="text-gray-700 whitespace-pre-line"><?php echo nl2br(htmlspecialchars($formateur->description)); ?></p>
                </div>
            </div>

            <div class="flex flex-col items-center">
                <h2 class="text-sm text-gray-500 font-semibold uppercase mb-2">Photo</h2>
                <?php if ($formateur->photo): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($formateur->photo) ?>" alt="Photo"
                         class="w-64 h-64 object-cover border rounded shadow-md">
                <?php else: ?>
                    <div class="w-64 h-64 flex items-center justify-center bg-gray-100 text-gray-400 border rounded">
                        Aucune image
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-8 flex space-x-4">
            <a href="editFormateur.php?id=<?php echo $formateur->id; ?>" 
               class="text-yellow-600 font-semibold hover:underline text-base">✏️ Éditer</a>
            <a href="deleteFormateur.php?id=<?php echo $formateur->id; ?>" 
               class="text-red-600 font-semibold hover:underline text-base"
               onclick="return confirm('Voulez-vous vraiment supprimer ce formateur ?');">🗑️ Supprimer</a>
            <a href="listFormateurs.php" 
               class="text-blue-600 font-semibold hover:underline text-base">🔙 Retour à la liste</a>
        </div>
    </div>
</body>
</html>
