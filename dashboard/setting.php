<?php
include_once ("header.php");
require_once '../db_config.php';

// Create a new database connection
$conn = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Handle form submission for editing period
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['notify'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $dob = $_POST['dob'];
        $weight = $_POST['weight'];

        /*
            // Output debug information
            echo "Post: <pre>";
            print_r($_POST);
            echo "</pre><br>";
            echo var_dump($_POST["dob"]);
            echo "</br>";

            */
        // Update the period in the database
        $update_query = "UPDATE users SET name=?, email=?, dob=?, weight=? WHERE id=?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssii", $name, $email, $dob, $weight, $id);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Error updating profile: " . $conn->error;
        }
        $stmt->close();
    }
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
                <div class="container-fluid py-4">
                    <div class="row">
                        <div class="col-12">
                            <?php
                            include_once ("function.php");
                            $profile_sql = "SELECT * FROM users WHERE id='$userID'";
                            $result = $conn->query($profile_sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title mb-4">
                                                <div class="d-flex justify-content-start">
                                                    <div class="image-container">
                                                        <img src="../assets/img/image.png" id="imgProfile"
                                                            style="width: 150px; height: 150px" class="img-thumbnail" />
                                                    </div>
                                                    <div class="userData m-3">
                                                        <h2 class="d-block" style="font-size: 1.5rem; font-weight: bold">
                                                            <?php echo $row['name']; ?>
                                                        </h2>
                                                        <div class="form-check form-switch">
                                                            <!-- Inside your HTML Switch Element -->
                                                            <input class="form-check-input" type="checkbox" role="switch"
                                                                id="flexSwitchCheckDefault" name="notify">
                                                            <label class="form-check-label" for="flexSwitchCheckDefault">Allow
                                                                to
                                                                notify</label>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                                                        <li class="nav-item nav-link active"><b>Basic Info</b></li>
                                                    </ul>
                                                    <div class="tab-content ml-1" id="myTabContent">
                                                        <div class="tab-pane fade show active" id="basicInfo" role="tabpanel"
                                                            aria-labelledby="basicInfo-tab">


                                                            <div class="row">
                                                                <div class="col-sm-3 col-md-2 col-5">
                                                                    <label style="font-weight:bold;">Full Name</label>
                                                                </div>
                                                                <div class="col-md-8 col-6">
                                                                    <?php echo $row['name']; ?>
                                                                </div>
                                                            </div>
                                                            <hr />
                                                            <div class="row">
                                                                <div class="col-sm-3 col-md-2 col-5">
                                                                    <label style="font-weight:bold;">Email</label>
                                                                </div>
                                                                <div class="col-md-8 col-6">
                                                                    <?php echo $row['email']; ?>
                                                                </div>
                                                            </div>
                                                            <hr />

                                                            <div class="row">
                                                                <div class="col-sm-3 col-md-2 col-5">
                                                                    <label style="font-weight:bold;">Birth Date</label>
                                                                </div>
                                                                <div class="col-md-8 col-6">
                                                                    <?php echo $row['dob']; ?>
                                                                </div>
                                                            </div>
                                                            <hr />


                                                            <div class="row">
                                                                <div class="col-sm-3 col-md-2 col-5">
                                                                    <label style="font-weight:bold;">Weight</label>
                                                                </div>
                                                                <div class="col-md-8 col-6">
                                                                    <?php echo $row['weight']; ?> Kg
                                                                </div>
                                                            </div>
                                                            <hr />
                                                            <div class="row">
                                                                <div class="col-sm-3 col-md-2 col-5">
                                                                    <label style="font-weight:bold;">Last Period
                                                                        Date</label>
                                                                </div>
                                                                <div class="col-md-8 col-6">
                                                                    <?php echo (getLastStartDate($cycles)); ?>
                                                                </div>
                                                            </div>
                                                            <hr />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Card end -->
                                    <div class="d-flex m-4 float-md-end">
                                        <button class='btn btn-success edit-btn' data-bs-toggle='modal'
                                            data-bs-target='#profileModal' data-id='<?php echo $row['id']; ?>'
                                            data-name='<?php echo $row['name']; ?>' data-email='<?php echo $row['email']; ?>'
                                            data-dob=' <?php echo $row['dob']; ?>' data-weight='<?php echo $row['weight']; ?>'>
                                            <i class="fas fa-edit"></i>
                                            Edit</button>
                                    </div>
                                <?php }
                            } ?>
                        </div>
                    </div>
                </div>

                <!-- Edit Symptom Modal -->
                <div class="modal" id="profileModal">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Profile</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form class="form" id="profileForm" method="POST" onsubmit="return validateForm()">
                                <div class="modal-body" style="max-height: 300px; overflow-y: auto;">
                                    <input type="hidden" id="edit-id" name="id">
                                    <div class="form-group">
                                        <label for="edit-name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="edit-name" maxlength="" name="name">
                                    </div>
                                    <div class="form-group">
                                        <label for="edit-email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="edit-email" name="email"
                                            maxlength="50">
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="edit-dob" class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control" id="edit-dob" name="dob" maxlength="50">
                                        <span class="error-span" style="color:grey; font-size:16px;">
                                            Should be at least 12 yrs old</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit-weight" class="form-label">Weight (kg)</label>
                                        <input type="number" class="form-control" id="edit-weight" min="0" max="500"
                                            step="0.01" name="weight" maxlength="50">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </main>



            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['notify']) && $_POST['notify'] === 'on') {
                    // Get the period status
                    $periodStatus = getDaysBeforePeriod($cycles);

                    // Output debug information
                    echo "<pre>";
                    print_r($periodStatus);
                    echo "<pre/>";

                    // Check if conditions for displaying notification are met
                    if (($periodStatus['days'] <= 6 && $periodStatus['title'] === "Period in") || $periodStatus['title'] === "Period" || $periodStatus['title'] === "Delay" || $periodStatus['title'] === "Period is") {
                        $message = $userName . " your " . $periodStatus['title'] . ". " . $periodStatus['days'];

                        echo "<script>";
                        echo "createNotification('Period Calculator', '" . addslashes($message) . "', 'icon.png');";
                        echo "</script>";
                        echo 'Notification sent.';

                    } else {
                        echo 'Notification switch is off.';
                    }
                }
            }
            $conn->close();
            include_once ("footer.php");
            ?>

            <script src="js/jquery.min.js"></script>
            <script>
                // $(document).ready(function () {
                document.addEventListener('DOMContentLoaded', function () {
                    $('.edit-btn').click(function () {
                        var id = $(this).data('id');
                        var name = $(this).data('name');
                        var email = $(this).data('email');
                        var dob = new Date($(this).data('dob')); // Convert string to Date object
                        var weight = $(this).data('weight');

                        console.log(id, name, email, dob, weight);

                        // Format the date as "yyyy-MM-dd"
                        var formattedDate = dob.getFullYear() + '-' + ('0' + (dob.getMonth() + 1)).slice(-2) + '-' + ('0' + dob.getDate()).slice(-2);

                        $('#edit-id').val(id);
                        $('#edit-name').val(name);
                        $('#edit-email').val(email);
                        $('#edit-dob').val(formattedDate);
                        $('#edit-weight').val(weight);
                    });

                    var switchElement = document.getElementById('flexSwitchCheckDefault');

                    switchElement.addEventListener('change', function () {
                        if (this.checked) {
                            // If the switch is turned on, request permission
                            if (Notification.permission !== 'granted') {
                                Notification.requestPermission().then(function (permission) {
                                    // Handle permission result
                                    if (permission === 'granted') {
                                        console.log('Notification permission granted.');
                                    } else {
                                        console.log('Notification permission denied.');
                                    }
                                });
                            }
                        } else {
                            // If the switch is turned off, disable notification sending
                            console.log('Notification permission removed.');
                        }
                    });
                });
                document.getElementById('edit-dob').setAttribute('max', new Date(new Date().getFullYear() - 12, 0, 1).toISOString().split('T')[0]);
            </script>
            <script>
                // Function to create a notification
                function createNotification(title, text, icon) {
                    console.log("Creating notification with message:", text);
                    // Check if the browser supports notifications
                    if ('Notification' in window) {
                        // Check the notification permission
                        if (Notification.permission === "granted") {
                            // Create the notification
                            new Notification(title, {
                                body: text,
                                icon
                            });
                        } else if (Notification.permission !== 'denied') {
                            // Request permission if not denied
                            Notification.requestPermission().then(function (permission) {
                                if (permission === "granted") {
                                    // Create the notification
                                    new Notification(title, {
                                        body: text,
                                        icon
                                    });
                                }
                            });
                        }
                    }
                }
            </script>
            <script>
                function validateForm() {
                    var email = document.getElementById("edit-email").value;
                    var fullName = document.getElementById("edit-name").value;
                    var dob = document.getElementById("edit-dob").value;
                    var weight = document.getElementById("edit-weight").value;
                    var error = false;

                    console.log(email, password, fullName, dob, weight);

                    // Remove existing error spans
                    var errorSpans = document.querySelectorAll('.error-span');
                    errorSpans.forEach(function (span) {
                        span.remove();
                    });

                    // Validate each field
                    if (email === "") {
                        displayError("edit-email", "Email is required");
                        error = true;
                    }
                    if (fullName === "") {
                        displayError("edit-name", "Full name is required");
                        error = true;
                    }
                    if (isNaN(fullName)) {
                        displayError("edit-name", "Name can't be a number");
                        error = true;
                    }
                    if (fullName.length < 3) {
                        displayError("edit-name", "Name should be of at least 3 character");
                        error = true;
                    }
                    if (dob === "") {
                        displayError("edit-dob", "Date of birth is required");
                        error = true;
                    }
                    if (weight === "") {
                        displayError("edit-weight", "Weight is required");
                        error = true;
                    }
                    if (isNaN(weight)) {
                        displayError("edit-weight", "Weight should be a number");
                        error = true;
                    }
                    if (weight > 100 || weight < 20) {
                        displayError("edit-weight", "Weight should be in between 20 to 100 kg");
                        error = true;
                    }

                    // Return false if there's any error
                    if (error) {
                        return false;
                    }

                    return true;
                }

                // Function to display error message next to input field
                function displayError(fieldId, errorMessage) {
                    var field = document.getElementById(fieldId);
                    var errorSpan = document.createElement('span');
                    errorSpan.className = 'error-span';
                    errorSpan.style.color = 'red';
                    errorSpan.innerHTML = errorMessage;
                    field.parentNode.insertBefore(errorSpan, field.nextSibling);
                }
            </script>