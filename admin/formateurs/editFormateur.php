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

$dao = new FormateurDALImpl($db->getConnection());
$business = new FormateurBusinessImpl($dao);

$id = $_GET['id'] ?? null;
$successMessage = '';
$errorMessage = '';

if (!$id) {
    die("ID du formateur manquant.");
}

$formateur = $business->getById($id);
if (!$formateur) {
    die("Formateur introuvable.");
}

function handlePhotoUpload($photo)
{
    $check = getimagesize($photo["tmp_name"]);
    if ($check === false) {
        throw new Exception("Le fichier n'est pas une image valide.");
    }

    if ($photo["size"] > 5000000) {
        throw new Exception("Fichier trop grand. Taille maximale : 5MB.");
    }

    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($photo["name"], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) {
        throw new Exception("Formats autorisés : JPG, JPEG, PNG, GIF.");
    }

    return file_get_contents($photo["tmp_name"]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $formateur->firstName = $_POST['firstName'];
        $formateur->lastName = $_POST['lastName'];
        $formateur->description = $_POST['description'];

        if (!empty($_FILES['photo']['tmp_name'])) {
            $formateur->photo = handlePhotoUpload($_FILES['photo']);
        }

        $updated = $business->update($formateur);

        if ($updated) {
            $successMessage = "Formateur mis à jour avec succès !";
        } else {
            $errorMessage = "Erreur lors de la mise à jour.";
        }
    } catch (Exception $e) {
        $errorMessage = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Formateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8 bg-gray-50">

    <h1 class="text-2xl font-bold mb-6">Modifier un Formateur</h1>

    <?php if ($successMessage): ?>
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded"><?= htmlspecialchars($successMessage) ?></div>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <div class="mb-6 p-4 bg-red-100 text-red-700 border border-red-300 rounded"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-4 max-w-xl bg-white p-6 shadow rounded">
        <input name="firstName" type="text" value="<?= htmlspecialchars($formateur->firstName) ?>" required class="border p-2 w-full" placeholder="First Name">
        <input name="lastName" type="text" value="<?= htmlspecialchars($formateur->lastName) ?>" required class="border p-2 w-full" placeholder="Last Name">
        <textarea name="description" required class="border p-2 w-full" placeholder="Description"><?= htmlspecialchars($formateur->description) ?></textarea>
        
        <div>
            <label class="block mb-1 font-medium">Photo actuelle :</label>
            <?php if ($formateur->photo): ?>
                <img src="data:image/jpeg;base64,<?= base64_encode($formateur->photo) ?>" alt="Photo" class="w-32 h-32 object-cover rounded mb-2">
            <?php else: ?>
                <p class="text-sm text-gray-500">Aucune image disponible.</p>
            <?php endif; ?>
            <input name="photo" type="file" accept="image/*" class="mt-2 border p-2 w-full">
        </div>

        <div class="flex space-x-4 mt-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Mettre à jour
            </button>
            <a href="listFormateurs.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                Retour à la liste des formateurs
            </a>
        </div>

    </form>

</body>
</html>
