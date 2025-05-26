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

$dao = new SujetDALImpl($db->getConnection());
$business = new SujetBusinessImpl($dao);

$sujetId = isset($_GET['id']) ? $_GET['id'] : null;

if ($sujetId) {
    $sujet = $business->getById($sujetId);
    if ($sujet) {
        try{
            $deleted = $business->delete($sujetId);
            if ($deleted) {
                header("Location: listSujets.php?message=deleted");
                exit;
            } else {
                header("Location: listSujets.php?message=error");
                exit;
            }
        } catch (PDOException  $e) {
            header("Location: listSujets.php?message=cannot_delete_linked");
            exit;
        }
    } else {
        header("Location: listSujets.php?message=notfound");
        exit;
    }
} else {
    header("Location: listSujets.php?message=invalid");
    exit;
}
