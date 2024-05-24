<?php
if ($_SERVER["REQUEST_METHOD"] === "GET") {
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

    $sql = "SELECT * FROM symptom WHERE user_id = '$userID'";
    $result = $conn->query($sql);
    $events = [];
    if ($result->num_rows > 0) {
        while ($rows = $result->fetch_assoc()) {
            $events[] = $rows;
        }
    }


    // Output JSON response
    header('Content-Type: application/json');
    echo json_encode($events);

} else {
    echo "Invalid request method";
}
$conn->close();
?>

