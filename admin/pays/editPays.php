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

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $value = trim($_POST['value']);
    
    if (isset($value)) {
        $pays->value = $value;
        if ($business->update($pays)) {
            header("Location: listPays.php?message=updated");
            exit;
        } else {
            $error = 'Erreur lors de la mise à jour du pays.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Éditer le Pays</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-8">
        <h1 class="text-3xl font-bold mb-6 text-blue-700">✏️ Éditer le Pays</h1>

        <?php if ($error): ?>
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label for="value" class="block text-sm text-gray-500 font-semibold uppercase">Nom du Pays</label>
                <input type="text" id="value" name="value" value="<?= htmlspecialchars($pays->value) ?>" 
                       class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>

            <div class="flex justify-between items-center">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Mettre à jour</button>
                <a href="detailsPays.php?id=<?php echo $pays->id; ?>" class="text-blue-600 hover:underline">🔙 Retour aux détails</a>
            </div>
        </form>
    </div>
</body>
</html>
