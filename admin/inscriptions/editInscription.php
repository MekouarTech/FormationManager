<?php
require_once '../../classes/services/AuthService.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/database.php';

$db = new Database();
$conn = $db->getConnection();
$auth = new AuthService($conn);

if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    header("Location: ../../public/login.php");
    exit();
}

$inscriptionDao = new InscriptionDALImpl($conn);
$inscriptionBusiness = new InscriptionBusinessImpl($inscriptionDao);

$inscriptionId = $_GET['id'] ?? null;
if (!$inscriptionId) {
    header("Location: listInscriptionsAdmin.php?message=notfound");
    exit();
}

$inscription = $inscriptionBusiness->getById($inscriptionId);
if (!$inscription) {
    header("Location: listInscriptionsAdmin.php?message=notfound");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inscription->firstName = $_POST['firstName'] ?? '';
    $inscription->lastName = $_POST['lastName'] ?? '';
    $inscription->email = $_POST['email'] ?? '';
    $inscription->phone = $_POST['phone'] ?? '';
    $inscription->company = $_POST['company'] ?? '';
    $inscription->paid = isset($_POST['paid']) ? 1 : 0;

    $inscriptionBusiness->update($inscription);
    header("Location: listInscriptionsAdmin.php?message=updated");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Inscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">

    <div class="max-w-xl mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">✏️ Modifier l'Inscription</h1>

        <form method="post">
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Prénom</label>
                <input type="text" name="firstName" value="<?= htmlspecialchars($inscription->firstName) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Nom</label>
                <input type="text" name="lastName" value="<?= htmlspecialchars($inscription->lastName) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($inscription->email) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Téléphone</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($inscription->phone ?? '') ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Entreprise</label>
                <input type="text" name="company" value="<?= htmlspecialchars($inscription->company ?? '') ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="paid" class="form-checkbox" <?= $inscription->paid ? 'checked' : '' ?>>
                    <span class="ml-2">Payé</span>
                </label>
            </div>

            <div class="flex justify-between items-center">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">💾 Enregistrer</button>
                <a href="listInscriptionsAdmin.php" class="text-gray-600 hover:underline">⬅️ Annuler</a>
            </div>
        </form>
    </div>

</body>
</html>
