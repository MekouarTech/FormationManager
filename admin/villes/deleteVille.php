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

$dao = new VillesDALImpl($db->getConnection());
$business = new VillesBusinessImpl($dao);

$villeId = $_GET['id'] ?? null;

if ($villeId) {
    $ville = $business->getById($villeId);

    if ($ville) {
        try {
            if ($business->delete($villeId)) {
                header("Location: listVilles.php?message=deleted");
                exit();
            } else {
                header("Location: listVilles.php?message=error");
                exit();
            }
        } catch (Exception $e) {
            header("Location: listVilles.php?message=cannot_delete_linked");
            exit();
        }
    } else {
        header("Location: listVilles.php?message=notfound");
        exit();
    }
} else {
    header("Location: listVilles.php?message=error");
    exit();
}
