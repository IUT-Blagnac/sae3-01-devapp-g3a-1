<?php
require_once "includes/connexion.inc.php";
require_once "includes/helpers.php";
$room = strtoupper($_GET["room"] ?? "");
if (strlen($room) < 4) {
    header("Location: index.php?error=true");
}

try {
    // Pour le badges, les 2 dernières valeurs
    $sql = "SELECT * FROM mesures WHERE room = :room ORDER BY id DESC LIMIT 2";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":room", $room);
    $stmt->execute();
    $badgeRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}

// 1st graphic
try {
    $sqlChart = "
    SELECT *
    FROM mesures
    WHERE room = :room
    ORDER BY date_heure ASC;
    ";
    $stmtChart = $pdo->prepare($sqlChart);
    $stmtChart->bindParam(":room", $room);
    $stmtChart->execute();
    $chartData = $stmtChart->fetchAll(PDO::FETCH_ASSOC);

    $temperatureData = [];
    $humidityData = [];
    $activityData = [];
    $tvocData = [];
    $illuminationData = [];
    $infraredData = [];
    $infraredVisibleData = [];
    $pressureData = [];
    $timestamps = [];

    foreach ($chartData as $row) {
        $temperatureData[] = (float) $row['temperature'];
        $humidityData[] = (float) $row['humidity'];
        $activityData[] = (float) $row['activity'];
        $tvocData[] = (float) $row['tvoc'];
        $illuminationData[] = (float) $row['illumination'];
        $infraredData[] = (float) $row['infrared'];
        $infraredVisibleData[] = (float) $row['infrared_and_visible'];
        $pressureData[] = (float) $row['presure'];
        $timestamps[] = $row['date_heure'];
    }

    $temperatureJson = json_encode($temperatureData);
    $humidityJson = json_encode($humidityData);
    $activityJson = json_encode($activityData);
    $tvocJson = json_encode($tvocData);
    $illuminationJson = json_encode($illuminationData);
    $infraredJson = json_encode($infraredData);
    $infraredVisibleJson = json_encode($infraredVisibleData);
    $pressureJson = json_encode($pressureData);
    $timestampsJson = json_encode($timestamps);
} catch (PDOException $e) {
    die("Error fetching chart data: " . $e->getMessage());
}

?>
<!doctype html>
<html lang="fr"><!-- [Head] start -->
<head><title>Salle <?= $room ?></title><!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="../assets/images/favicon.svg" type="image/x-icon"><!-- [Page specific CSS] start -->
    <!-- [Font] Family -->
    <link rel="stylesheet" href="../assets/fonts/tabler-icons.min.css"><!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="../assets/css/style.css" id="main-style-link">
    <link rel="stylesheet" href="../assets/css/style-preset.css">
    <link rel="stylesheet" href="../assets/css/final.css">
</head><!-- [Head] end --><!-- [Body] Start -->
<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr"
      data-pc-theme_contrast="" data-pc-theme="light"><!-- [ Pre-loader ] start -->
<div class="loader-bg">
    <div class="loader-track">
        <div class="loader-fill"></div>
    </div>
</div><!-- [ Pre-loader ] End --><!-- [ Sidebar Menu ] start -->
<div class="pc-container">
    <div class="pc-content"><!-- [ breadcrumb ] start -->
        <a href="index.php" class="btn btn-outline-primary mt-3 mb-3">
            <i class="ti ti-arrow-left"></i> Retour au tableau de bord
        </a>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    <H3 class="alert-heading mb-0">Données de la salle <?= $room ?></H3>
                </div>
            </div>
            <div class="col-12 mt-4 mb-2">
                <h5 class="text-center">Dernière donnée + évolution </h5>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avtar bg-light-primary">
                                    <i class="ti ti-temperature f-24"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3"><p class="mb-1">Temperature</p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0"><?= $badgeRows[0]['temperature'] ?> °C</h4>
                                    <?= (badgeEvolution($badgeRows[0]['temperature'], $badgeRows[1]['temperature'], " °C")); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avtar bg-light-warning"><i class="ti ti-droplet f-24"></i></div>
                            </div>
                            <div class="flex-grow-1 ms-3"><p class="mb-1">Humidity</p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0"><?= $badgeRows[0]['humidity'] ?> %</h4>
                                    <?= (badgeEvolution($badgeRows[0]['humidity'], $badgeRows[1]['humidity'], " %")); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avtar bg-light-success"><i class="ti ti-activity f-24"></i></div>
                            </div>
                            <div class="flex-grow-1 ms-3"><p class="mb-1">Activity</p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0"><?= isset($badgeRows[0]['activity']) && strlen($badgeRows[0]['activity']) ? $badgeRows[0]['activity'] : 'None' ?></h4>
                                    <?= (badgeEvolution($badgeRows[0]['activity'], $badgeRows[1]['activity'])); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avtar bg-light-danger"><i class="ti ti-biohazard f-24"></i></div>
                            </div>
                            <div class="flex-grow-1 ms-3"><p class="mb-1">TVOC</p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0"><?= $badgeRows[0]['tvoc'] ?> ppb</h4>
                                    <?= (badgeEvolution($badgeRows[0]['tvoc'], $badgeRows[1]['tvoc'], " ppb")); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avtar bg-light-yellow"><i class="ti ti-sun f-24"></i></div>
                            </div>
                            <div class="flex-grow-1 ms-3"><p class="mb-1">Illumination</p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0"><?= $badgeRows[0]['illumination'] ?> lux</h4>
                                    <?= (badgeEvolution($badgeRows[0]['illumination'], $badgeRows[1]['illumination'], " lux")); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avtar bg-light-violet"><i class="ti ti-loader f-24"></i></div>
                            </div>
                            <div class="flex-grow-1 ms-3"><p class="mb-1">Infrared</p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0"><?= $badgeRows[0]['infrared'] ?></h4>
                                    <?= (badgeEvolution($badgeRows[0]['infrared'], $badgeRows[1]['infrared'])); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avtar bg-light-turquoise"><i class="ti ti-eye f-24"></i></div>
                            </div>
                            <div class="flex-grow-1 ms-3"><p class="mb-1">Infrared and Visible</p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0"><?= $badgeRows[0]['infrared_and_visible'] ?></h4>
                                    <?= (badgeEvolution($badgeRows[0]['infrared_and_visible'], $badgeRows[1]['infrared_and_visible'])); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avtar bg-light-green"><i class="ti ti-gauge f-24"></i></div>
                            </div>
                            <div class="flex-grow-1 ms-3"><p class="mb-1">Pressure</p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0"><?= $badgeRows[0]['presure'] ?> hPa</h4>
                                    <?= (badgeEvolution($badgeRows[0]['presure'], $badgeRows[1]['presure'], " hPa")); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--<div class="col-lg-7 col-md-12">-->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div><h5 class="mb-1">Rapport global</h5></div>
                            <select class="form-select rounded-3 form-select-sm w-auto">
                                <option>Today</option>
                                <option selected>Weekly</option>
                            </select></div>
                        <div id="revenue-sales-chart"></div>

                        <div class="alert alert-info text-center h4">
                            Pour
                            FILTRER
                            les données, cliquez sur les légendes du graphique
                            pour afficher ou masquer les données correspondantes.
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- [ Main Content ] end --></div>
</div><!-- [ Main Content ] end -->
<!--<footer class="pc-footer">
    <div class="footer-wrapper container-fluid">
        <div class="row">
            <div class="col my-1"><p class="m-0">Developed with &#9829; by <a
                            href="https://daner-sharifi.com" target="_blank" class="text-decoration-underline">Daner</a> & Yolan</p></div>
        </div>
    </div>
</footer>--><!-- Required Js -->
<script>
    const temperatureData = <?= $temperatureJson; ?>;
    const humidityData = <?= $humidityJson; ?>;
    const activityData = <?= $activityJson; ?>;
    const tvocData = <?= $tvocJson; ?>;
    const illuminationData = <?= $illuminationJson; ?>;
    const infraredData = <?= $infraredJson; ?>;
    const infraredVisibleData = <?= $infraredVisibleJson; ?>;
    const pressureData = <?= $pressureJson; ?>;
    const timestamps = <?= $timestampsJson; ?>;
</script>

<script src="../assets/js/final.js"></scrpit>
<script src="../assets/js/icon/custom-font.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/plugins/apexcharts.min.js"></script>
<script src="../assets/js/widgets/revenue-sales-chart.js"></script>
</body><!-- [Body] end --></html>