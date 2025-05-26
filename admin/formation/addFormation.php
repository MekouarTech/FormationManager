<?php
require_once '../../classes/services/AuthService.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/database.php';


$db = new Database();

$conn = $db->getConnection();
$auth = new AuthService($conn);

if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    header("Location: ../../public/login.php");
    exit();
}


// Load dropdown data
$paysBusiness = new PaysBusinessImpl(new PaysDALImpl($conn));
$paysList = $paysBusiness->getAll();

$coursDAL = new CoursDALImpl($conn);
$coursBusiness = new CoursBusinessImpl($coursDAL);
$coursList = $coursBusiness->getAll();

$formateurDAL = new FormateurDALImpl($conn);
$formateurBusiness = new FormateurBusinessImpl($formateurDAL);
$formateurList = $formateurBusiness->getAll();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $price = $_POST['price'];
    $mode = $_POST['mode'];
    $coursId = $_POST['coursId'];
    $formateurId = $_POST['formateurId'];
    $villeId = $_POST['villeId'];

    if ($price && $mode && $coursId && $formateurId && $villeId && isset($_POST['add'])) {
        $formationDAL = new FormationDALImpl($conn);
        $formation = new Formation(null, $price, $mode, $coursId, $formateurId, $villeId);
        $result = $formationDAL->create($formation);

        if ($result) {
            header('Location: listFormation.php?success=1');
            exit();
        } else {
            $errorMessage = 'Une erreur est survenue lors de l\'ajout de la formation.';
        }
    } else {
        $errorMessage = 'Veuillez remplir tous les champs.';
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Formation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">

<div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-4">Ajouter une Formation</h1>

    <form method="POST" action="" class="space-y-4">

        <!-- Prix -->
        <div>
            <label for="price" class="block font-medium">Prix MAD</label>
            <input type="number" id="price" name="price" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>

        <!-- Mode -->
        <div>
            <label for="mode" class="block font-medium">Mode</label>
            <select id="mode" name="mode" class="w-full border border-gray-300 rounded px-3 py-2" required>
                <option value="">-- Choisir un mode --</option>
                <option value="Présentiel">Présentiel</option>
                <option value="Distanciel">Distanciel</option>
            </select>
        </div>

        <!-- Cours -->
        <div>
            <label for="coursId" class="block font-medium">Cours</label>
            <select id="coursId" name="coursId" class="w-full border border-gray-300 rounded px-3 py-2" required>
                <option value="">-- Sélectionnez un cours --</option>
                <?php foreach ($coursList as $cours): ?>
                    <option value="<?= $cours->id ?>"><?= htmlspecialchars($cours->name) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Formateur -->
        <div>
            <label for="formateurId" class="block font-medium">Formateur</label>
            <select id="formateurId" name="formateurId" class="w-full border border-gray-300 rounded px-3 py-2" required>
                <option value="">-- Sélectionnez un formateur --</option>
                <?php foreach ($formateurList as $formateur): ?>
                    <option value="<?= $formateur->id ?>"><?= htmlspecialchars($formateur->firstName . ' ' . $formateur->lastName) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Pays -->
        <div>
            <label for="pays" class="block font-medium">Pays</label>
            <select id="pays" name="paysId" class="w-full border border-gray-300 rounded px-3 py-2" required>
                <option value="">-- Sélectionnez un pays --</option>
                <?php foreach ($paysList as $pays): ?>
                    <option value="<?= $pays->id ?>"><?= htmlspecialchars($pays->value) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Ville (chargée dynamiquement) -->
        <div>
            <label for="ville" class="block font-medium">Ville</label>
            <select id="ville" name="villeId" class="w-full border border-gray-300 rounded px-3 py-2" required>
                <option value="">-- Sélectionnez une ville --</option>
            </select>
        </div>

        <!-- Submit -->
        <div class="flex justify-between items-center">
            <button type="submit" name="add" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Ajouter</button>
            <a href="listFormation.php" class="text-blue-600 hover:underline">🔙 Retour</a>
        </div>

    </form>
</div>

<script>
document.getElementById('pays').addEventListener('change', function () {
    const paysId = this.value;
    const villeSelect = document.getElementById('ville');
    villeSelect.innerHTML = '<option value="">Chargement...</option>';

    if (paysId) {
        fetch(`../api/getVillesByPays.php?paysId=${paysId}`)
            .then(response => response.json())
            .then(data => {
                villeSelect.innerHTML = '<option value="">-- Sélectionnez une ville --</option>';
                data.forEach(ville => {
                    const option = document.createElement('option');
                    option.value = ville.id;
                    option.textContent = ville.value;
                    villeSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Erreur lors du chargement des villes:', error);
                villeSelect.innerHTML = '<option value="">Erreur</option>';
            });
    } else {
        villeSelect.innerHTML = '<option value="">-- Sélectionnez une ville --</option>';
    }
});
</script>

</body>
</html>
