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

$sujetDao = new SujetDALImpl($db->getConnection());
$sujetBusiness = new SujetBusinessImpl($sujetDao);

$coursId = isset($_GET['id']) ? $_GET['id'] : null;

$cours = null;
$sujet = null;

if ($coursId) {
    $cours = $business->getById($coursId);
    if ($cours) {
        $sujet = $sujetBusiness->getById($cours->__get('sujetId'));
    } else {
        header("Location: listCours.php?message=notfound");
        exit;
    }
} else {
    header("Location: listCours.php?message=notfound");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Cours</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">

    <div class="max-w-5xl mx-auto bg-white shadow-lg rounded-xl p-8">
        <h1 class="text-3xl font-bold mb-6 text-blue-700">📚 Détails du Cours</h1>

        <?php if ($cours): ?>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <div>
                        <h2 class="text-sm text-gray-500 font-semibold uppercase">Nom</h2>
                        <p class="text-lg font-medium text-gray-800"><?= htmlspecialchars($cours->__get('name')) ?></p>
                    </div>

                    <div>
                        <h2 class="text-sm text-gray-500 font-semibold uppercase">Contenu</h2>
                        <p class="text-gray-800"><?= nl2br(htmlspecialchars($cours->__get('content'))) ?></p>
                    </div>

                    <div>
                        <h2 class="text-sm text-gray-500 font-semibold uppercase">Description</h2>
                        <p class="text-gray-800"><?= nl2br(htmlspecialchars($cours->__get('description'))) ?></p>
                    </div>

                    <div>
                        <h2 class="text-sm text-gray-500 font-semibold uppercase">Audience</h2>
                        <p class="text-gray-800"><?= htmlspecialchars($cours->__get('audience')) ?></p>
                    </div>

                    <div>
                        <h2 class="text-sm text-gray-500 font-semibold uppercase">Durée</h2>
                        <p class="text-gray-800"><?= htmlspecialchars($cours->__get('duration')) ?> heures</p>
                    </div>

                    <div>
                        <h2 class="text-sm text-gray-500 font-semibold uppercase">Test Inclus</h2>
                        <p class="text-gray-800"><?= $cours->__get('testIncluded') ? 'Oui' : 'Non' ?></p>
                    </div>

                    <div>
                        <h2 class="text-sm text-gray-500 font-semibold uppercase">Contenu du Test</h2>
                        <p class="text-gray-800"><?= nl2br(htmlspecialchars($cours->__get('testContent'))) ?></p>
                    </div>

                    <div>
                        <h2 class="text-sm text-gray-500 font-semibold uppercase">Sujet</h2>
                        <p class="text-lg font-medium text-gray-800">
                            <?= $sujet ? htmlspecialchars($sujet->__get('name')) : 'Non défini' ?>
                        </p>
                    </div>
                </div>

                <div class="flex justify-center items-center">
                    <?php if ($cours->__get('logo')): ?>
                        <img src="data:image/jpeg;base64,<?= base64_encode($cours->__get('logo')) ?>" alt="Logo du cours" class="max-w-xs rounded border">
                    <?php else: ?>
                        <p class="text-gray-500 italic">Aucun logo disponible</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <a href="editCours.php?id=<?= $cours->__get('id') ?>" 
                   class="text-yellow-600 font-semibold hover:underline text-base">✏️ Éditer</a>
                <a href="deleteCours.php?id=<?= $cours->__get('id') ?>" 
                   class="text-red-600 font-semibold hover:underline text-base"
                   onclick="return confirm('Voulez-vous vraiment supprimer ce cours ?');">🗑️ Supprimer</a>
                <a href="listCours.php" 
                   class="text-blue-600 font-semibold hover:underline text-base">🔙 Retour à la liste</a>
            </div>
        <?php else: ?>
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">Cours non trouvé.</div>
        <?php endif; ?>
    </div>

</body>
</html>
