<?php
include_once ("header.php");
require_once '../db_config.php';

// Create a new database connection
$conn = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<body class="sb-nav-fixed">
    <!-- Top Nav Start -->
    <?php include_once ("topnav.php"); ?>
    <!-- Top Nav End -->
    <div id="layoutSidenav">
        <!-- Side Nav Start-->
        <?php include_once ("sidenav.php"); ?>
        <!-- Side Nav End-->

        <!-- Content Start -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <?php include_once ("function.php") ?>
                    <h2 class="mt-4"><?php echo getDaysBeforePeriod($cycles)['title'] ?></h2>
                    <p
                        style='color:#ce254f; ".(sizeof($cycles) === 1 ? "font-weight:bold; font-size:35px;" : "font-weight:bold; font-size: 40px;") . "color: black; margin-bottom: 30px;'>
                        <?php echo getDaysBeforePeriod($cycles)['days'] ?>
                    </p>
                    <p style='font-size: 16px; cursor: pointer;' data-bs-toggle="modal" data-bs-target="#myModal">
                        <?php echo (getPregnancyChance($cycles)); ?>
                        <span style='color:grey;'>- chance of getting pregnant.
                            <i class="fas fa-chevron-right sm"></i>
                        </span>
                    </p>
                </div>

                <div class="d-flex justify-content-center mb-4">
                    <button type="button" class="btn" style="background-color:#FB5988; color:white; width:180px;"
                    <?php echo isAddPeriodToday($cycles) ? 'disabled' : ''; ?>
                    onclick="handleMark()">Mark Today</button>
                </div>
                <!-- both modal are here -->
                <?php include_once ("modal.php") ?>

                <script>
                    function handleMark() {
                        // AJAX request to update cycle data in PHP
                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "update_cycles.php?action=UPDATE", true);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                console.log(xhr.responseText);
                                if (xhr.responseText === "success") {
                                    var modal = new bootstrap.Modal(document.getElementById('successModal'));
                                    modal.show();
                                }
                            }
                        };
                        xhr.send(); // Send the request
                    }
                </script>
                <script src="js/main.js"></script>

                <div class="container">
                    <div>
                        <?php include_once ("calendars.php") ?>
                    </div>
                </div>
            </main>
            <?php
            $conn->close();
            include_once ("footer.php");
            ?>