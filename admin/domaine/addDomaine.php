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

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if (!empty($name) && !empty($description) && isset($_POST['add'])) {
        $domaine = new Domaine(null, $name, $description);
        if ($business->create($domaine)) {
            header("Location: listDomaines.php?message=added");
            exit;
        } else {
            $error = 'Erreur lors de l\'ajout du domaine.';
        }
    } else {
        $error = 'Veuillez remplir tous les champs.';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Domaine</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">

    <div class="max-w-xl mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Ajouter un Domaine</h1>

        <?php if ($error): ?>
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label for="name" class="block font-medium">Nom du Domaine</label>
                <input type="text" id="name" name="name" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>

            <div>
                <label for="description" class="block font-medium">Description</label>
                <textarea id="description" name="description" class="w-full border border-gray-300 rounded px-3 py-2" required></textarea>
            </div>

            <div class="flex justify-between items-center">
                <button type="submit" name="add" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Ajouter</button>
                <a href="listDomaines.php" class="text-blue-600 hover:underline">🔙 Retour à la liste</a>
            </div>
        </form>
    </div>

</body>
</html>
