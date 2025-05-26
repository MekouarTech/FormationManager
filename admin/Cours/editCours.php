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

$coursDao = new CoursDALImpl($db->getConnection());
$coursBusiness = new CoursBusinessImpl($coursDao);

$sujetDao = new SujetDALImpl($db->getConnection());
$sujetBusiness = new SujetBusinessImpl($sujetDao);
$sujets = $sujetBusiness->getAll();

if (!isset($_GET['id'])) {
    header("Location: listCours.php?message=notfound");
    exit;
}

$cours = $coursBusiness->getById($_GET['id']);
if (!$cours) {
    header("Location: listCours.php?message=notfound");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cours->name = $_POST['name'];
    $cours->content = $_POST['content'];
    $cours->description = $_POST['description'];
    $cours->audience = $_POST['audience'];
    $cours->duration = $_POST['duration'];
    $cours->testIncluded = isset($_POST['testIncluded']) ? 1 : 0;
    $cours->testContent = $_POST['testContent'];
    $cours->sujetId = $_POST['sujetId'];

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $cours->logo = file_get_contents($_FILES['logo']['tmp_name']);
    }

    $coursBusiness->update($cours);
    header("Location: listCours.php?message=updated");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Cours</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">

    <div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Modifier un Cours</h1>

        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label for="name" class="block font-medium">Nom</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($cours->name) ?>" required class="w-full border rounded p-2">
            </div>

            <div>
                <label for="content" class="block font-medium">Contenu</label>
                <textarea name="content" id="content" rows="3" class="w-full border rounded p-2"><?= htmlspecialchars($cours->content) ?></textarea>
            </div>

            <div>
                <label for="description" class="block font-medium">Description</label>
                <textarea name="description" id="description" rows="3" class="w-full border rounded p-2"><?= htmlspecialchars($cours->description) ?></textarea>
            </div>

            <div>
                <label for="audience" class="block font-medium">Audience</label>
                <input type="text" name="audience" id="audience" value="<?= htmlspecialchars($cours->audience) ?>" class="w-full border rounded p-2">
            </div>

            <div>
                <label for="duration" class="block font-medium">Durée (heures)</label>
                <input type="number" name="duration" id="duration" value="<?= htmlspecialchars($cours->duration) ?>" min="0" step="0.1" class="w-full border rounded p-2">
            </div>

            <div class="flex items-center space-x-2">
                <input type="checkbox" name="testIncluded" id="testIncluded" <?= $cours->testIncluded ? 'checked' : '' ?>>
                <label for="testIncluded" class="font-medium">Test Inclus</label>
            </div>

            <div>
                <label for="testContent" class="block font-medium">Contenu du Test</label>
                <textarea name="testContent" id="testContent" rows="2" class="w-full border rounded p-2"><?= htmlspecialchars($cours->testContent) ?></textarea>
            </div>

            <div>
                <label class="block font-medium mb-1">Logo actuel</label>
                <?php if ($cours->logo): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($cours->logo) ?>" alt="Logo" class="w-24 h-24 object-cover rounded mb-2">
                <?php else: ?>
                    <div class="w-24 h-24 bg-gray-200 text-sm flex items-center justify-center text-gray-500 rounded mb-2">No Logo</div>
                <?php endif; ?>
                <input type="file" name="logo" id="logo" accept="image/*" class="w-full">
            </div>

            <div>
                <label for="sujetId" class="block font-medium">Sujet</label>
                <select name="sujetId" id="sujetId" required class="w-full border rounded p-2">
                    <option value="">-- Choisir un sujet --</option>
                    <?php foreach ($sujets as $sujet): ?>
                        <option value="<?= $sujet->id ?>" <?= $sujet->id == $cours->sujetId ? 'selected' : '' ?>>
                            <?= htmlspecialchars($sujet->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex justify-end space-x-2">
                <a href="listCours.php" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Annuler</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Mettre à jour</button>
            </div>
        </form>
    </div>

</body>
</html>
