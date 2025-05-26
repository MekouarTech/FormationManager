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
    die("Erreur : ID du formateur non spécifié.");
}

$id = intval($_GET['id']);

$dao = new FormateurDALImpl($db->getConnection());
$business = new FormateurBusinessImpl($dao);

try {
    $deleted = $business->delete($id);
    if ($deleted) {
        header("Location: listFormateurs.php?message=deleted");
        exit;
    } else {
        die("Erreur : Échec de la suppression du formateur.");
    }
} catch (Exception $e) {
    header("Location: listFormateurs.php?message=cannot_delete_linked");
    exit;
}
?>