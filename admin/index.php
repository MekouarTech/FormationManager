<?php
require_once '../classes/services/AuthService.php';
require_once '../config/database.php';
require_once '../config/autoload.php';

$db = new Database();
$pdo = $db->getConnection();
$auth = new AuthService($pdo);

if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    header("Location: ../public/login.php");
    exit();
}

// Instantiate BusinessImpls
$formateurBusiness = new FormateurBusinessImpl(new FormateurDALImpl($pdo));
$domaineBusiness = new DomaineBusinessImpl(new DomaineDALImpl($pdo));
$sujetBusiness = new SujetBusinessImpl(new SujetDALImpl($pdo));
$coursBusiness = new CoursBusinessImpl(new CoursDALImpl($pdo));
$formationBusiness = new FormationBusinessImpl(new FormationDALImpl($pdo));

$formationDateBusiness = new FormationDateBusinessImpl(new FormationDateDALImpl($pdo));
$paysBusiness = new PaysBusinessImpl(new PaysDALImpl($pdo));
$villeBusiness = new VillesBusinessImpl(new VillesDALImpl($pdo));
$inscriptionBusiness = new InscriptionBusinessImpl(new InscriptionDALImpl($pdo));

$formateursCount = $formateurBusiness->getCount();
$domainesCount = $domaineBusiness->getCount();
$sujetsCount = $sujetBusiness->getCount();
$coursCount = $coursBusiness->getCount();
$formationsCount = $formationBusiness->getCount();

$formationDateCount = $formationDateBusiness->getCount();
$inscriptionsCount = $inscriptionBusiness->getCount();
$paysCount = $paysBusiness->getCount();
$villesCount = $villeBusiness->getCount();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin Panel</a>
        <div class="d-flex">
            <a href="../public/logout.php?from=admin" class="btn btn-outline-light">Déconnexion</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="mb-4">Tableau de Bord</h2>

    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Formateurs</h5>
                    <p><?= $formateursCount ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Domaines</h5>
                    <p><?= $domainesCount ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Sujets</h5>
                    <p><?= $sujetsCount ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5>Cours</h5>
                    <p><?= $coursCount ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5>Formations</h5>
                    <p><?= $formationsCount ?></p>
                </div>
            </div>
        </div>

            <div class="col-md-3 mb-3">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <h5>Pays</h5>
                    <p><?= $paysCount ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h5>Villes</h5>
                    <p><?= $villesCount ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-light text-dark border border-secondary">
                <div class="card-body">
                    <h5>Inscriptions</h5>
                    <p><?= $inscriptionsCount ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-secondary-subtle text-dark border border-secondary">
                <div class="card-body">
                    <h5>Formation Dates</h5>
                    <p><?= $formationDateCount ?></p>
                </div>
            </div>
        </div>
</div>



    <!-- Links -->
    <hr>
    <h4>Gestion Rapide</h4>
    <div class="row">
        <?php
        $sections = [
            'Pays' => 'pays/listPays.php',
            'Villes' => 'villes/listVilles.php',
            'Formateurs' => 'formateurs/listFormateurs.php',
            'Domaines' => 'domaine/listDomaines.php',
            'Sujets' => 'sujet/listSujets.php',
            'Cours' => 'cours/listCours.php',
            'Formations' => 'formation/listFormation.php',
            'Formation Date' => 'formationDate/listFormationDate.php',
            'Les Inscriptions' => 'inscriptions/listInscriptionsAdmin.php',
        ];

        foreach ($sections as $label => $link) {
            echo "<div class='col-md-3 mb-2'><a href='$link' class='btn btn-outline-dark w-100'>$label</a></div>";
        }
        ?>
    </div>
</div>

</body>
</html>
