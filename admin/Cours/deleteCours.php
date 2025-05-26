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

$dao = new CoursDALImpl($db->getConnection());
$business = new CoursBusinessImpl($dao);

$coursId = isset($_GET['id']) ? $_GET['id'] : null;

if ($coursId) {
    $cours = $business->getById($coursId);
    if ($cours) {
        try {
            $deleteSuccess = $business->delete($coursId);
            if ($deleteSuccess) {
                header("Location: listCours.php?message=deleted");
                exit;
            } else {
                header("Location: listCours.php?message=error");
                exit;
            }
        } catch (Exception $e) {
            header("Location: listCours.php?message=cannot_delete_linked");
            exit;
        }
    } else {
        header("Location: listCours.php?message=notfound");
        exit;
    }
} else {
    header("Location: listCours.php?message=notfound");
    exit;
}
?>
