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

$id = $_GET['id'] ?? null;
$domaine = null;
$error = '';

if ($id) {
    $domaine = $business->getById($id);
    if (!$domaine) {
        header("Location: listDomaines.php?message=notfound");
        exit;
    }
} else {
    header("Location: listDomaines.php?message=notfound");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if (!empty($name) && !empty($description)) {
        $domaine->name = $name;
        $domaine->description = $description;
        if ($business->update($domaine)) {
            header("Location: listDomaines.php?message=updated");
            exit;
        } else {
            $error = 'Erreur lors de la mise à jour.';
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
    <title>Modifier un Domaine</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">

    <div class="max-w-xl mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Modifier un Domaine</h1>

        <?php if ($error): ?>
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label for="name" class="block font-medium">Nom du Domaine</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($domaine->name); ?>" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>

            <div>
                <label for="description" class="block font-medium">Description</label>
                <textarea id="description" name="description" class="w-full border border-gray-300 rounded px-3 py-2" required><?php echo htmlspecialchars($domaine->description); ?></textarea>
            </div>

            <div class="flex justify-between items-center">
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Mettre à jour</button>
                <a href="listDomaines.php" class="text-blue-600 hover:underline">🔙 Retour à la liste</a>
            </div>
        </form>
    </div>

</body>
</html>
