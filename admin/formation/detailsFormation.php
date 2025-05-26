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
$coursBusiness = new CoursBusinessImpl(new CoursDALImpl($db->getConnection()));
$formateurBusiness = new FormateurBusinessImpl(new FormateurDALImpl($db->getConnection()));
$villeBusiness = new VillesBusinessImpl(new VillesDALImpl($db->getConnection()));

$formationId = $_GET['id'] ?? null;

$formation = null;
$cours = null;
$formateur = null;
$ville = null;

if ($formationId) {
    $formation = $formationBusiness->getById($formationId);

    if ($formation) {
        $cours = $coursBusiness->getById($formation->__get('coursId'));
        $formateur = $formateurBusiness->getById($formation->__get('formateurId'));
        $ville = $villeBusiness->getById($formation->__get('villeId'));
    } else {
        header("Location: listFormation.php?message=notfound");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de la Formation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">

<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-8">
    <h1 class="text-3xl font-bold mb-6 text-blue-700">📘 Détails de la Formation</h1>

    <?php if ($formation): ?>
        <div class="grid md:grid-cols-2 gap-8">
            <div>
                <div class="mb-4">
                    <h2 class="text-sm text-gray-500 font-semibold uppercase">Cours</h2>
                    <p class="text-lg font-medium text-gray-800"><?= htmlspecialchars($cours ? $cours->name : 'Non défini') ?></p>
                </div>

                <div class="mb-4">
                    <h2 class="text-sm text-gray-500 font-semibold uppercase">Prix</h2>
                    <p class="text-lg font-medium text-gray-800"><?= htmlspecialchars($formation->price) ?> €</p>
                </div>

                <div class="mb-4">
                    <h2 class="text-sm text-gray-500 font-semibold uppercase">Mode</h2>
                    <p class="text-lg font-medium text-gray-800"><?= htmlspecialchars($formation->mode) ?></p>
                </div>
            </div>

            <div>
                <div class="mb-4">
                    <h2 class="text-sm text-gray-500 font-semibold uppercase">Formateur</h2>
                    <p class="text-lg font-medium text-gray-800">
                        <?= $formateur ? htmlspecialchars($formateur->firstName . ' ' . $formateur->lastName) : 'Non défini' ?>
                    </p>
                </div>

                <div class="mb-4">
                    <h2 class="text-sm text-gray-500 font-semibold uppercase">Ville</h2>
                    <p class="text-lg font-medium text-gray-800">
                        <?= $ville ? htmlspecialchars($ville->value) : 'Non défini' ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-8 flex space-x-4">
            <a href="editFormation.php?id=<?= $formation->id ?>" 
               class="text-yellow-600 font-semibold hover:underline text-base">✏️ Éditer</a>
            <a href="deleteFormation.php?id=<?= $formation->id ?>" 
               class="text-red-600 font-semibold hover:underline text-base"
               onclick="return confirm('Voulez-vous vraiment supprimer cette formation ?');">🗑️ Supprimer</a>
            <a href="listFormation.php" 
               class="text-blue-600 font-semibold hover:underline text-base">🔙 Retour à la liste</a>
        </div>
    <?php else: ?>
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">Formation non trouvée.</div>
    <?php endif; ?>
</div>

</body>
</html>
