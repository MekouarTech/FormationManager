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

function uploadPhoto($photo)
{
    $imageFileType = strtolower(pathinfo($photo["name"], PATHINFO_EXTENSION));

    $check = getimagesize($photo["tmp_name"]);
    if ($check === false) {
        die("File is not an image.");
    }

    if ($photo["size"] > 5000000) {
        die("Error, your file is too large. Maximum size is 5MB.");
    }

    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        die("Error, only JPG, JPEG, PNG & GIF files are allowed.");
    }

    return file_get_contents($photo["tmp_name"]);
}

/*function uploadPhoto($photo)
{
    $targetDir = __DIR__ . '/../../public/uploads/images/';
 
    $photoName = uniqid() . '-' . basename($photo["name"]);
    $targetFile = $targetDir . $photoName;
    
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $check = getimagesize($photo["tmp_name"]);
    if ($check === false) {
        die("File is not an image.");
    }
    
    if ($photo["size"] > 5000000) {
        die("Error, your file is too large. Maximum size is 5MB.");
    }
    
    if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
        die("Error, only JPG, JPEG, PNG & GIF files are allowed.");
    }
    
    if (move_uploaded_file($photo["tmp_name"], $targetFile)) {
        return 'uploads/images/' . $photoName; 
    } else {
        die("there was an error uploading your file.");
    }
}*/

$dao = new FormateurDALImpl($db->getConnection());
$business = new FormateurBusinessImpl($dao);

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['firstName']) && isset($_POST['lastName']) 
        && isset($_POST['description']) 
        && isset($_FILES['photo']) && isset($_POST['add']) ) {

            try {
                $photoBinary = uploadPhoto($_FILES['photo']);
                
                $formateur = new Formateur(
                    null,
                    $_POST['firstName'],
                    $_POST['lastName'],
                    $_POST['description'],
                    $photoBinary
                );
                
                $created = $business->create($formateur);

                if ($created) {
                    $successMessage = "Formateur ajouté avec succès !";
                } else {
                    $errorMessage = "Erreur lors de l'ajout du formateur.";
                }
            } catch (Exception $e) {
                $errorMessage = "Erreur : " . $e->getMessage();
            }
    } else {
        $errorMessage = "Erreur, tous les champs sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ajouter Formateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8">


    <?php if (!empty($successMessage)): ?>
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded">
            <?php echo htmlspecialchars($successMessage); ?>
        </div>
    <?php endif; ?>

    
    <?php if (!empty($errorMessage)): ?>
        <div class="mb-6 p-4 bg-red-100 text-red-700 border border-red-300 rounded">
            <?php echo htmlspecialchars($errorMessage); ?>
        </div>
    <?php endif; ?>

    <h1 class="text-2xl font-bold mb-6">Ajouter un Formateur</h1>

    <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
        <input name="firstName" type="text" placeholder="First Name" required class="border p-2 w-full">
        <input name="lastName" type="text" placeholder="Last Name" required class="border p-2 w-full">
        <textarea name="description" placeholder="Description" required class="border p-2 w-full"></textarea>
        <input name="photo" type="file" accept="image/*" required class="border p-2 w-full">
        <button type="submit" name="add" class="bg-blue-500 text-white px-4 py-2 rounded">Ajouter</button>
    </form>

    <a href="listFormateurs.php" class="mt-4 inline-block text-blue-600 hover:underline">Retour à la liste des formateurs</a>

</body>
</html>
