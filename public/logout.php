<?php
/*session_start();
session_unset();
session_destroy();
header("Location: index.php");
exit;
?>
<?php
*/
require_once '../classes/services/AuthService.php';
require_once '../config/database.php';

session_start();
$db = new Database();
$pdo = $db->getConnection();
$auth = new AuthService($pdo);

$from = $_GET['from'] ?? 'public';


$auth->logout();

if ($from === 'admin') {
    header("Location: login.php");
} else {
    header("Location: index.php");
}
exit();