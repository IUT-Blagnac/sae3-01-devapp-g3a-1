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
    <link rel="stylesheet" href="../assets/css/plugins/datepicker-bs5.min.css"><!-- [Page specific CSS] end -->
    <!-- [Font] Family -->
    <link rel="stylesheet" href="../assets/fonts/inter/inter.css" id="main-font-link">
    <!-- [phosphor Icons] https://phosphoricons.com/ -->
    <link rel="stylesheet" href="../assets/fonts/phosphor/duotone/style.css">
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="../assets/fonts/tabler-icons.min.css"><!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="../assets/fonts/feather.css">
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="../assets/fonts/fontawesome.css">
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="../assets/fonts/material.css"><!-- [Template CSS Files] -->
    <link rel="stylesheet" href="../assets/css/style.css" id="main-style-link">
    <script src="../assets/js/tech-stack.js"></script>
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
            <!--<div class="col-lg-7 col-md-12">
                <div class="card" id="allGraphic">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between"><h5 class="mb-0">Courses</h5>
                            <button class="btn btn-sm btn-link-primary">View Report</button>
                        </div>
                        <h4 class="mb-1">$7,860</h4>
                        <p class="d-inline-flex align-items-center text-success gap-1 mb-0"><i
                                    class="ti ti-arrow-narrow-up"></i> 2.1%</p>
                        <p class="text-muted mb-1">Sales from 1-12 Dec, 2023</p>
                        <div id="course-report-bar-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body"><p class="text-muted mb-1">Total Revenue</p>
                        <div class="d-flex align-items-center justify-content-between"><h4 class="mb-0">7,265</h4>
                            <p class="d-inline-flex align-items-center gap-1 mb-0">+11.02% <i
                                        class="ti ti-arrow-up-right text-success"></i></p></div>
                        <div id="total-revenue-line-1-chart"></div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body"><p class="text-muted mb-1">Total Subscription</p>
                        <div class="d-flex align-items-center justify-content-between"><h4 class="mb-0">5,326</h4>
                            <p class="d-inline-flex align-items-center gap-1 mb-0">+12.02% <i
                                        class="ti ti-arrow-down-right text-danger"></i></p></div>
                        <div id="total-revenue-line-2-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="widget-calender" id="pc-datepicker-6"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3"><h5 class="mb-0">
                                Visitors</h5>
                            <div class="dropdown"><a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none"
                                                     href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                     aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#">Today</a>
                                    <a class="dropdown-item" href="#">Weekly</a> <a class="dropdown-item" href="#">Monthly</a>
                                </div>
                            </div>
                        </div>
                        <div id="visitors-bar-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3"><h5 class="mb-0">Earning
                                Courses</h5><select class="form-select rounded-3 form-select-sm w-auto">
                                <option>Day</option>
                                <option>Month</option>
                                <option selected="selected">Year</option>
                            </select></div>
                        <div id="earning-courses-line-chart"></div>
                    </div>
                </div>
            </div>-->
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
<script src="../assets/js/plugins/popper.min.js"></script>
<script src="../assets/js/plugins/simplebar.min.js"></script>
<script src="../assets/js/plugins/bootstrap.min.js"></script>
<script src="../assets/js/plugins/i18next.min.js"></script>
<script src="../assets/js/plugins/i18nextHttpBackend.min.js"></script>
<script src="../assets/js/icon/custom-font.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/theme.js"></script>
<script src="../assets/js/multi-lang.js"></script>
<script src="../assets/js/plugins/feather.min.js"></script>
<script>layout_change('light');</script>
<script>change_box_container('false');</script>
<script>layout_caption_change('true');</script>
<script>layout_rtl_change('false');</script>
<script>preset_change('preset-1');</script>
<script>main_layout_change('vertical');</script><!-- [Page Specific JS] start --><!-- bootstrap-datepicker -->
<script src="../assets/js/plugins/datepicker-full.min.js"></script>
<script src="../assets/js/plugins/apexcharts.min.js"></script>
<script src="../assets/js/plugins/peity-vanilla.min.js"></script><!-- custom widgets js -->
<script src="../assets/js/widgets/revenue-sales-chart.js"></script>
<script src="../assets/js/widgets/course-report-bar-chart.js"></script>
<script src="../assets/js/widgets/total-revenue-line-1-chart.js"></script>
<script src="../assets/js/widgets/total-revenue-line-2-chart.js"></script>
<script src="../assets/js/widgets/student-states-chart.js"></script>
<script src="../assets/js/widgets/activity-line-chart.js"></script>
<script src="../assets/js/widgets/widget-calender.js"></script>
<script src="../assets/js/widgets/visitors-bar-chart.js"></script>
<script src="../assets/js/widgets/earning-courses-line-chart.js"></script>
<script src="../assets/js/widgets/table-donut.js"></script><!-- [Page Specific JS] end -->
</body><!-- [Body] end --></html>