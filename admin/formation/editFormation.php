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

$dao = new FormationDALImpl($db->getConnection());
$business = new FormationBusinessImpl($dao);

$formationId = $_GET['id'] ?? null;
$formation = null;

if ($formationId) {
    $formation = $business->getById($formationId);
}

if (!$formation) {
    header("Location: listFormation.php?message=notfound");
    exit;
}

$coursBusiness = new CoursBusinessImpl(new CoursDALImpl($db->getConnection()));
$coursList = $coursBusiness->getAll();

$formateurBusiness = new FormateurBusinessImpl(new FormateurDALImpl($db->getConnection()));
$formateurList = $formateurBusiness->getAll();

$villeBusiness = new VillesBusinessImpl(new VillesDALImpl($db->getConnection()));
$villeList = $villeBusiness->getAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $price = trim($_POST['price']);
    $mode = $_POST['mode'] ?? '';
    $coursId = $_POST['coursId'] ?? null;
    $formateurId = $_POST['formateurId'] ?? null;
    $villeId = $_POST['villeId'] ?? null;

    if (!empty($price) && !empty($mode) && $coursId && $formateurId && $villeId && isset($_POST['update'])) {
        $formation->price = $price;
        $formation->mode = $mode;
        $formation->coursId = $coursId;
        $formation->formateurId = $formateurId;
        $formation->villeId = $villeId;

        if ($business->update($formation)) {
            header("Location: listFormation.php?message=updated");
            exit;
        } else {
            $error = 'Erreur lors de la mise à jour de la formation.';
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
    <title>Modifier une Formation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">

<div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-4">✏️ Modifier une Formation</h1>

    <?php if ($error): ?>
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label for="price" class="block font-medium">Prix (MAD)</label>
            <input type="number" step="0.01" name="price" id="price" value="<?= htmlspecialchars($formation->price) ?>" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>

        <div>
            <label for="mode" class="block font-medium">Mode</label>
            <select name="mode" id="mode" class="w-full border border-gray-300 rounded px-3 py-2" required>
                <option value="Présentiel" <?= $formation->mode === 'Présentiel' ? 'selected' : '' ?>>Présentiel</option>
                <option value="Distanciel" <?= $formation->mode === 'Distanciel' ? 'selected' : '' ?>>Distanciel</option>
            </select>
        </div>

        <div>
            <label for="coursId" class="block font-medium">Cours</label>
            <select name="coursId" id="coursId" class="w-full border border-gray-300 rounded px-3 py-2" required>
                <option value="">-- Sélectionnez un cours --</option>
                <?php foreach ($coursList as $cours): ?>
                    <option value="<?= $cours->id ?>" <?= $cours->id == $formation->coursId ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cours->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="formateurId" class="block font-medium">Formateur</label>
            <select name="formateurId" id="formateurId" class="w-full border border-gray-300 rounded px-3 py-2" required>
                <option value="">-- Sélectionnez un formateur --</option>
                <?php foreach ($formateurList as $formateur): ?>
                    <option value="<?= $formateur->id ?>" <?= $formateur->id == $formation->formateurId ? 'selected' : '' ?>>
                        <?= htmlspecialchars($formateur->firstName . ' ' . $formateur->lastName) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="villeId" class="block font-medium">Ville</label>
            <select name="villeId" id="villeId" class="w-full border border-gray-300 rounded px-3 py-2" required>
                <option value="">-- Sélectionnez une ville --</option>
                <?php foreach ($villeList as $ville): ?>
                    <option value="<?= $ville->id ?>" <?= $ville->id == $formation->villeId ? 'selected' : '' ?>>
                        <?= htmlspecialchars($ville->value) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="flex justify-between items-center">
            <button type="submit" name="update" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Mettre à jour</button>
            <a href="listFormation.php" class="text-blue-600 hover:underline">🔙 Retour à la liste</a>
        </div>
    </form>
</div>

</body>
</html>
