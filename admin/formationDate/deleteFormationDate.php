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

$formationDateDao = new FormationDateDALImpl($db->getConnection());
$formationDateBusiness = new FormationDateBusinessImpl($formationDateDao);

$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    $formationDate = $formationDateBusiness->getById($id);

    if ($formationDate) {
        try {
            $formationDateBusiness->delete($id);
            header("Location: listFormationDate.php?message=deleted");
            exit;
        } catch (PDOException $e) {
            header("Location: listFormationDate.php?message=cannot_delete_linked");
            exit;
        }
    } else {
        header("Location: listFormationDate.php?message=notfound");
        exit;
    }
} else {
    header("Location: listFormationDate.php?message=invalid");
    exit;
}
