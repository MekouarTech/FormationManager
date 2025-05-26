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

$inscriptionDao = new InscriptionDALImpl($conn);
$inscriptionBusiness = new InscriptionBusinessImpl($inscriptionDao);

$formationDateDao = new FormationDateDALImpl($conn);
$formationDateBusiness = new FormationDateBusinessImpl($formationDateDao);

$formationDao = new FormationDALImpl($conn);
$formationBusiness = new FormationBusinessImpl($formationDao);

$coursDao = new CoursDALImpl($conn);
$coursBusiness = new CoursBusinessImpl($coursDao);

// Fetch all inscriptions
$inscriptions = $inscriptionBusiness->getAll();

// Capture filters
$search = $_GET['search'] ?? '';
$paidFilter = $_GET['paid'] ?? '';
$sortBy = $_GET['sortBy'] ?? 'lastName';
$sortDir = $_GET['sortDir'] ?? 'asc';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;

// Filter logic
$filteredInscriptions = array_filter($inscriptions, function ($i) use ($search, $paidFilter) {
    $matchesSearch = true;
    $matchesPaid = true;

    if (!empty($search)) {
        $fullName = strtolower($i->firstName . ' ' . $i->lastName);
        $email = strtolower($i->email);
        $searchLower = strtolower($search);
        $matchesSearch = str_contains($fullName, $searchLower) || str_contains($email, $searchLower);
    }

    if ($paidFilter === 'yes') {
        $matchesPaid = $i->paid;
    } elseif ($paidFilter === 'no') {
        $matchesPaid = !$i->paid;
    }

    return $matchesSearch && $matchesPaid;
});

// Sorting
usort($filteredInscriptions, function ($a, $b) use ($sortBy, $sortDir) {
    $valueA = strtolower($a->$sortBy ?? '');
    $valueB = strtolower($b->$sortBy ?? '');
    return $sortDir === 'asc' ? $valueA <=> $valueB : $valueB <=> $valueA;
});

// Pagination
$total = count($filteredInscriptions);
$totalPages = ceil($total / $perPage);
$offset = ($page - 1) * $perPage;
$paginatedInscriptions = array_slice($filteredInscriptions, $offset, $perPage);

function sortLink($column, $label, $currentSortBy, $currentSortDir) {
    $nextDir = ($currentSortBy === $column && $currentSortDir === 'asc') ? 'desc' : 'asc';
    $query = http_build_query(array_merge($_GET, ['sortBy' => $column, 'sortDir' => $nextDir]));
    $arrow = $currentSortBy === $column ? ($currentSortDir === 'asc' ? '▲' : '▼') : '';
    return "<a href=\"?$query\" class=\"hover:underline\">$label $arrow</a>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Inscriptions</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-8">

<div class="max-w-7xl mx-auto bg-white shadow-md rounded-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Liste des Inscriptions</h1>
    </div>

    <form method="GET" class="mb-6 flex flex-wrap items-center gap-4">
        <input type="text" name="search" placeholder="Rechercher par nom ou email..." value="<?= htmlspecialchars($search) ?>"
               class="border border-gray-300 rounded px-3 py-2 w-64">

        <select name="paid" class="border border-gray-300 rounded px-3 py-2">
            <option value="">Tous</option>
            <option value="yes" <?= $paidFilter === 'yes' ? 'selected' : '' ?>>Payé</option>
            <option value="no" <?= $paidFilter === 'no' ? 'selected' : '' ?>>Non Payé</option>
        </select>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Filtrer
        </button>
    </form>

    <table class="w-full table-auto border-collapse">
        <thead>
        <tr class="bg-gray-100">
            <th class="border px-4 py-2"><?= sortLink('lastName', 'Nom', $sortBy, $sortDir) ?></th>
            <th class="border px-4 py-2"><?= sortLink('email', 'Email', $sortBy, $sortDir) ?></th>
            <th class="border px-4 py-2">Téléphone</th>
            <th class="border px-4 py-2">Entreprise</th>
            <th class="border px-4 py-2">Cours</th>
            <th class="border px-4 py-2">Date</th>
            <th class="border px-4 py-2">Payé</th>
            <th class="border px-4 py-2">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($paginatedInscriptions as $inscription): ?>
            <?php
            $formationDate = $formationDateBusiness->getById($inscription->formationDateId);
            $formation = $formationBusiness->getById($formationDate->formationId ?? 0);
            $cours = $coursBusiness->getById($formation->coursId ?? 0);
            ?>
            <tr class="hover:bg-gray-50">
                <td class="border px-4 py-2"><?= htmlspecialchars($inscription->firstName . ' ' . $inscription->lastName) ?></td>
                <td class="border px-4 py-2"><?= htmlspecialchars($inscription->email) ?></td>
                <td class="border px-4 py-2"><?= htmlspecialchars($inscription->phone ?? 'Non fourni') ?></td>
                <td class="border px-4 py-2"><?= htmlspecialchars($inscription->company ?? 'Non fournie') ?></td>
                <td class="border px-4 py-2"><?= htmlspecialchars($cours->name ?? 'Inconnu') ?></td>
                <td class="border px-4 py-2"><?= htmlspecialchars($formationDate->date ?? 'Indisponible') ?></td>
                <td class="border px-4 py-2"><?= $inscription->paid ? '✅ Oui' : '❌ Non' ?></td>
                <td class="border px-4 py-2 space-x-2">
                    <a href="editInscription.php?id=<?= $inscription->id ?>" class="text-yellow-500 hover:underline">✏️ Éditer</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($paginatedInscriptions)): ?>
            <tr>
                <td colspan="8" class="text-center py-4 text-gray-500">Aucune inscription trouvée.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="flex justify-center mt-6 space-x-2">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"
               class="px-3 py-1 border rounded <?= $i === $page ? 'bg-blue-500 text-white' : 'bg-white text-blue-500' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
</div>

<div class="max-w-7xl mx-auto mt-6 text-center">
    <a href="../index.php" class="inline-block text-blue-600 hover:underline font-medium text-lg">
        ⬅️ Retour au Panneau Admin
    </a>
</div>

</body>
</html>
