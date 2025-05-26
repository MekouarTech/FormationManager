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

$formationBusiness = new FormationBusinessImpl(new FormationDALImpl($db->getConnection()));

$formationId = $_GET['id'] ?? null;

if ($formationId) {
    $formation = $formationBusiness->getById($formationId);

    if ($formation) {
        try{
            if ($formationBusiness->delete($formationId)) {
                header("Location: listFormation.php?message=deleted");
                exit;
            } else {
                header("Location: listFormation.php?message=delete_failed");
                exit;
            }
        } catch (PDOException $e) {
            header("Location: listFormation.php?message=cannot_delete_linked");
            exit;
        }    
    } else {
        header("Location: listFormation.php?message=notfound");
        exit;
    }
} else {
    header("Location: listFormation.php?message=invalid");
    exit;
}
