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

    // Prepare data for insertion
    $title = $_POST['title'];
    $description = $_POST['description'];
    $flow = $_POST['flow'];
    $symptoms = implode(", ", $_POST['symptoms']);
    $temperature = $_POST['temperature'];
    $date = $_POST['date'];

    // Insert event into the database
    $sql = "INSERT INTO symptom (title, description, flow, symptoms, temperature, date, user_id) VALUES ('$title', '$description', '$flow', '$symptoms', '$temperature', '$date', '$userID')";
    if ($conn->query($sql) === TRUE) {
        echo "New event added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }


} else {
    echo "Invalid request method";
}
$conn->close();
?>