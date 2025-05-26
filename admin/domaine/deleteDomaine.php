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

$dao = new DomaineDALImpl($db->getConnection());
$business = new DomaineBusinessImpl($dao);

$id = $_GET['id'] ?? null;

if ($id && $business->getById($id)) {
    try{
        $business->delete($id);
        header("Location: listDomaines.php?message=deleted");
        exit;
    }catch (PDOException $e) {
        header("Location: listDomaines.php?message=cannot_delete_linked");
        exit;
    }
} else {
    header("Location: listDomaines.php?message=notfound");
    exit;
}
