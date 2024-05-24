<?php
require_once '../db_config.php';
// Create a new database connection
$conn = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
include ("session.php");

$email = $_SESSION["email"];
$query = "SELECT * FROM users WHERE email='$email'";
$res = $conn->query($query);

if (!($res->num_rows) > 0) {
    header("Location:logout.php");
}
$row = mysqli_fetch_array($res);
$userID = $row["id"];

include_once "function.php";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if ($_GET["action"] === "FUTURE") {
        $forecastPeriodDays = getForecastPeriodDays($cycles);

        // Output JSON response
        header('Content-Type: application/json');
        echo json_encode($forecastPeriodDays);
    } elseif ($_GET["action"] === "PAST") {
        $periodDays = getPeriodDays($cycles);

        // Output JSON response
        header('Content-Type: application/json');
        echo json_encode($periodDays);
    } elseif ($_GET["action"] === "OVULATION") {
        $ovulationDays = getOvulation($cycles);

        // Output JSON response
        header('Content-Type: application/json');
        echo json_encode($ovulationDays);
    } else {
        echo "Invalid action";
    }
} else {
    echo "Invalid request method";
}
$conn->close();
?>