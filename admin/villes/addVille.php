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

$paysDao = new PaysDALImpl($db->getConnection());
$paysBusiness = new PaysBusinessImpl($paysDao);

$villeDao = new VillesDALImpl($db->getConnection());
$villeBusiness = new VillesBusinessImpl($villeDao);

$paysList = $paysBusiness->getAll();

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $value = trim($_POST['value']);
    $paysId = $_POST['paysId'] ?? null;
    $add = $_POST['add'] ?? null;

    if (!empty($value) && isset($_POST['add']) && $paysId !== null && $paysId !== "") {

        $ville = new Ville(null, $value, $paysId);
        if ($villeBusiness->create($ville)) {
            header("Location: listVilles.php?message=added");
            exit;
        } else {
            $error = 'Erreur lors de l\'ajout de la ville.';
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
    <title>Ajouter une Ville</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">

    <div class="max-w-xl mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Ajouter une Ville</h1>

        <?php if ($error): ?>
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label for="value" class="block font-medium">Nom de la ville</label>
                <input type="text" id="value" name="value" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>

            <div>
                <label for="paysId" class="block font-medium">Pays</label>
                <select id="paysId" name="paysId" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="">-- Sélectionnez un pays --</option>
                    <?php foreach ($paysList as $pays): ?>
                        <option value="<?= $pays->id ?>"><?= htmlspecialchars($pays->value) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex justify-between items-center">
                <button type="submit" name="add" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Ajouter</button>
                <a href="listVilles.php" class="text-blue-600 hover:underline">🔙 Retour à la liste</a>
            </div>
        </form>
    </div>

</body>
</html>
