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

$sujetDao = new SujetDALImpl($db->getConnection());
$sujetBusiness = new SujetBusinessImpl($sujetDao);

$domaineDao = new DomaineDALImpl($db->getConnection());
$domaineBusiness = new DomaineBusinessImpl($domaineDao);

$id = $_GET['id'] ?? null;
$error = '';
$sujet = null;

if ($id) {
    $sujet = $sujetBusiness->getById($id);
    if (!$sujet) {
        header("Location: listSujets.php?message=notfound");
        exit;
    }
} else {
    header("Location: listSujets.php?message=notfound");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $shortDescription = trim($_POST['shortDescription']);
    $longDescription = trim($_POST['longDescription']);
    $individualBenefit = trim($_POST['individualBenefit']);
    $businessBenefit = trim($_POST['businessBenefit']);
    $domaineId = $_POST['domaineId'] ?? null;

    if (!empty($name) && !empty($shortDescription) && !empty($longDescription) && !empty($individualBenefit) && !empty($businessBenefit) && $domaineId) {
        // Handle logo upload
        $logo = $_FILES['logo'] ?? null;
        if ($logo && $logo['tmp_name']) {
            $logoData = file_get_contents($logo['tmp_name']);
        } else {
            $logoData = $sujet->logo;
        }

        $updatedSujet = new Sujet(
            $sujet->id,
            $name,
            $shortDescription,
            $longDescription,
            $individualBenefit,
            $businessBenefit,
            $logoData,
            $domaineId
        );

        if ($sujetBusiness->update($updatedSujet)) {
            header("Location: listSujets.php?message=updated");
            exit;
        } else {
            $error = "Erreur lors de la mise à jour du sujet.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le Sujet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">
    <div class="max-w-xl mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Modifier le Sujet</h1>

        <?php if ($error): ?>
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label for="name" class="block font-medium">Nom du Sujet</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($sujet->name) ?>" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>

            <div>
                <label for="shortDescription" class="block font-medium">Description courte</label>
                <textarea id="shortDescription" name="shortDescription" class="w-full border border-gray-300 rounded px-3 py-2" rows="3" required><?= htmlspecialchars($sujet->shortDescription) ?></textarea>
            </div>

            <div>
                <label for="longDescription" class="block font-medium">Description longue</label>
                <textarea id="longDescription" name="longDescription" class="w-full border border-gray-300 rounded px-3 py-2" rows="5" required><?= htmlspecialchars($sujet->longDescription) ?></textarea>
            </div>

            <div>
                <label for="individualBenefit" class="block font-medium">Bénéfice Individuel</label>
                <textarea id="individualBenefit" name="individualBenefit" class="w-full border border-gray-300 rounded px-3 py-2" rows="3" required><?= htmlspecialchars($sujet->individualBenefit) ?></textarea>
            </div>

            <div>
                <label for="businessBenefit" class="block font-medium">Bénéfice Business</label>
                <textarea id="businessBenefit" name="businessBenefit" class="w-full border border-gray-300 rounded px-3 py-2" rows="3" required><?= htmlspecialchars($sujet->businessBenefit) ?></textarea>
            </div>

            <div>
                <label for="logo" class="block font-medium">Logo (laisser vide pour ne pas changer)</label>
                <input type="file" id="logo" name="logo" class="w-full border border-gray-300 rounded px-3 py-2">
                <?php if ($sujet->logo): ?>
                    <p class="mt-2 text-sm text-gray-600">Logo actuel : ✅ (déjà présent)</p>
                <?php endif; ?>
            </div>

            <div>
                <label for="domaineId" class="block font-medium">Domaine</label>
                <select id="domaineId" name="domaineId" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="">-- Sélectionnez un domaine --</option>
                    <?php
                    $domaineList = $domaineBusiness->getAll();
                    foreach ($domaineList as $domaine):
                    ?>
                        <option value="<?= $domaine->id ?>" <?= $domaine->id == $sujet->domaineId ? 'selected' : '' ?>>
                            <?= htmlspecialchars($domaine->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex justify-between items-center">
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Mettre à jour</button>
                <a href="listSujets.php" class="text-blue-600 hover:underline">🔙 Retour à la liste</a>
            </div>
        </form>
    </div>
</body>
</html>
