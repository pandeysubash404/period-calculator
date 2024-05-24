<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
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

    include_once ("function.php");

    if ($_GET["action"] === "UPDATE") {
        $periodLength = getAverageLengthOfPeriod($cycles);
        $todayDate = date('Y-m-d');
        $cycleLength = 0;

        // Convert dates to Unix timestamps
        $todayTimestamp = strtotime($todayDate);
        $lastStartDate = getLastStartDate($cycles);
        $lastStartDateTimestamp = strtotime($lastStartDate);

        // Calculate the difference in seconds
        $differenceInSeconds = $todayTimestamp - $lastStartDateTimestamp;

        // Convert seconds to days and round to the nearest whole number
        $updateCycleLength = round($differenceInSeconds / 86400);


        //insert a new cycle data row
        // $mark_sql = "INSERT INTO cycles (user_id, start_date, period_length, cycle_length) VALUES ('$userID', '$todayDate', $periodLength, $cycleLength)";
        // if ($conn->query($mark_sql) === TRUE) {

        $mark_sql = "INSERT INTO cycles (user_id, start_date, period_length, cycle_length) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($mark_sql);
        $stmt->bind_param("isii", $userID, $todayDate, $periodLength, $cycleLength);
        if ($stmt->execute()) {

            $update_sql = "UPDATE cycles SET cycle_length = ? WHERE user_id = ? AND start_date = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("iss", $updateCycleLength, $userID, $lastStartDate);

            // Get the ID of the last inserted row
            // $before_last_id = ($conn->insert_id) - 1;

            // // Update the cycle_length of the row before the inserted row
            // $update_sql = "UPDATE cycles SET cycle_length = $updateCycleLength WHERE id = ?";
            // $stmt = $conn->prepare($update_sql);
            // $stmt->bind_param("i", $before_last_id);
            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "Error updating record: " . $conn->error;
            }
            $stmt->close();
        } else {
            echo "Error: " . $mark_sql . "<br>" . $conn->error;
        }
    } elseif ($_GET["action"] === "INSERT") {
        $date = $_POST["last-period-start"];
        $periodLength = (int) $_POST["period-duration"];
        $cycleLength = (int) $_POST["cycle-length"];

        if (empty($date)) {
            echo "Please enter the period date";
        } else if (empty($periodLength)) {
            $periodLength = 5;
        } else if (empty($cycleLength)) {
            $cycleLength = 28;
        }

        // Insert new cycle data
        $first_sql = "INSERT INTO cycles (user_id, start_date, period_length, cycle_length) VALUES ('$userID', '$date', $periodLength, $cycleLength)";
        if ($conn->query($first_sql) === TRUE) {
            header('Location:index.php');
            exit;
        } else {
            echo "Error: " . $first_sql . "<br>" . $conn->error;
        }
    } else {
        echo "Invalid request action";
    }
}
$conn->close();
?>