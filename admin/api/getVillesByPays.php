<?php
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/database.php';

if (!isset($_GET['paysId'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing paysId']);
    exit;
}

$db = new Database();
$paysDao = new PaysDALImpl($db->getConnection());
$paysBusiness = new PaysBusinessImpl($paysDao);

$paysId = intval($_GET['paysId']);
$villes = $paysBusiness->getVillesByPaysId($paysId);

header('Content-Type: application/json');
echo json_encode(array_map(function($ville) {
    return [
        'id' => $ville->id,
        'value' => $ville->value
    ];
}, $villes));
?>
