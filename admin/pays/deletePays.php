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
    header("Location: listPays.php?message=notfound");
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

try{

    if ($business->delete($id)) {
        header("Location: listPays.php?message=deleted");
        exit;
    } else {
        header("Location: listPays.php?message=error");
        exit;
    }
    
} catch (Exception $e) {
    header("Location: listPays.php?message=cannot_delete_linked");
    exit;
}
?>
