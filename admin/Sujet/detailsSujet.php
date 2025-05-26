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

$domaineDao = new DomaineDALImpl($db->getConnection());
$domaineBusiness = new DomaineBusinessImpl($domaineDao);

$sujetId = isset($_GET['id']) ? $_GET['id'] : null;

$sujet = null;
$domaine = null;

if ($sujetId) {
    $sujet = $business->getById($sujetId);
    if ($sujet) {
        $domaine = $domaineBusiness->getById($sujet->__get('domaineId'));
    } else {
        header("Location: listSujets.php?message=notfound");
        exit;
    }
} else {
    header("Location: listSujets.php?message=notfound");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Sujet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">

    <div class="max-w-5xl mx-auto bg-white shadow-lg rounded-xl p-8">
        <h1 class="text-3xl font-bold mb-6 text-blue-700">📚 Détails du Sujet</h1>

        <?php if ($sujet): ?>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <div>
                        <h2 class="text-sm text-gray-500 font-semibold uppercase">Nom</h2>
                        <p class="text-lg font-medium text-gray-800"><?= htmlspecialchars($sujet->__get('name')) ?></p>
                    </div>

                    <div>
                        <h2 class="text-sm text-gray-500 font-semibold uppercase">Description courte</h2>
                        <p class="text-gray-800"><?= nl2br(htmlspecialchars($sujet->__get('shortDescription'))) ?></p>
                    </div>

                    <div>
                        <h2 class="text-sm text-gray-500 font-semibold uppercase">Description longue</h2>
                        <p class="text-gray-800"><?= nl2br(htmlspecialchars($sujet->__get('longDescription'))) ?></p>
                    </div>

                    <div>
                        <h2 class="text-sm text-gray-500 font-semibold uppercase">Bénéfice Individuel</h2>
                        <p class="text-gray-800"><?= nl2br(htmlspecialchars($sujet->__get('individualBenefit'))) ?></p>
                    </div>

                    <div>
                        <h2 class="text-sm text-gray-500 font-semibold uppercase">Bénéfice Business</h2>
                        <p class="text-gray-800"><?= nl2br(htmlspecialchars($sujet->__get('businessBenefit'))) ?></p>
                    </div>

                    <div>
                        <h2 class="text-sm text-gray-500 font-semibold uppercase">Domaine</h2>
                        <p class="text-lg font-medium text-gray-800">
                            <?= $domaine ? htmlspecialchars($domaine->__get('name')) : 'Non défini' ?>
                        </p>
                    </div>
                </div>

                <div class="flex justify-center items-center">
                    <?php if ($sujet->__get('logo')): ?>
                        <img src="data:image/png;base64,<?= base64_encode($sujet->__get('logo')) ?>" alt="Logo du sujet" class="max-w-xs rounded border">
                    <?php else: ?>
                        <p class="text-gray-500 italic">Aucun logo disponible</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <a href="editSujet.php?id=<?= $sujet->__get('id') ?>" 
                   class="text-yellow-600 font-semibold hover:underline text-base">✏️ Éditer</a>
                <a href="deleteSujet.php?id=<?= $sujet->__get('id') ?>" 
                   class="text-red-600 font-semibold hover:underline text-base"
                   onclick="return confirm('Voulez-vous vraiment supprimer ce sujet ?');">🗑️ Supprimer</a>
                <a href="listSujets.php" 
                   class="text-blue-600 font-semibold hover:underline text-base">🔙 Retour à la liste</a>
            </div>
        <?php else: ?>
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">Sujet non trouvé.</div>
        <?php endif; ?>
    </div>

</body>
</html>
