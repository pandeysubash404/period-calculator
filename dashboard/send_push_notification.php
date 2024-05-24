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
$userName = $row["name"];

include_once "function.php";

$periodStatus = getDaysBeforePeriod($cycles);

echo "<pre>";
print_r($periodStatus);
echo "<pre/>";

if (($periodStatus['days'] <= 6 && $periodStatus['title'] === "Period in") || $periodStatus['title'] === "Period" || $periodStatus['title'] === "Delay") {
    $message = $userName . " your " . $periodStatus['title'] . ". " . $periodStatus['days'];

    sendPushNotification($message);
}

function sendPushNotification($message)
{ ?>
    <script>
        askForApproval();

        function askForApproval() {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    createNotification('Period Calculator', '<?php echo $message; ?>', '../assets/img/favicon.png');
                }
            });
        }

        function createNotification(title, text, icon) {
            console.log("Creating notification with message:", text);
            new Notification(title, {
                body: text,
                icon
            });
        }
    </script>
<?php }

$conn->close();
?>