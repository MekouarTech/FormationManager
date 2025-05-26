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

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    
    if (!empty($nom) && isset($_POST['add'])) {
        
        $dao = new PaysDALImpl($db->getConnection());
        $business = new PaysBusinessImpl($dao);

        $pays = new Pays(null, $nom);
        if ($business->create($pays)) {
            header("Location: listPays.php?message=added");
            exit;
        } else {
            $error = 'Erreur lors de l\'ajout du pays.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Pays</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">

    <div class="max-w-xl mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Ajouter un Pays</h1>

        <?php if ($error): ?>
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label for="nom" class="block font-medium">Nom du pays</label>
                <input type="text" id="nom" name="nom" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>

            <div class="flex justify-between items-center">
                <button type="submit" name="add" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Ajouter</button>
                <a href="listPays.php" class="text-blue-600 hover:underline">🔙 Retour à la liste</a>
            </div>
        </form>
    </div>

</body>
</html>
