<?php
include_once "header.php";
require_once '../db_config.php';

// Create a new database connection
$conn = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = $color = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["inputEmail"];
    $password = md5($_POST["inputPassword"]);
    $name = $_POST["inputName"];
    $dob = $_POST["inputDob"];
    $weight = $_POST["inputWeight"];

    if (empty($email) || empty($password) || empty($name) || empty($dob) || empty($weight)) {
        $message = "All fields are required";
        $color = "red";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL)) {

        // Check if the email already exists
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "$email already exists! <a style='color: white;' href='forget_pass.php'>Forgot your password?</a>";
            $color = "red";
        } else {
            // Insert the user details into the database
            $query = "INSERT INTO users (name, email, password, dob, weight) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssi", $name, $email, $password, $dob, $weight);
            $stmt->execute();

            $message = "Account created successfully!";
            $color = "green";
        }
    } else {
        $color = "red";
        $message = "Enter a valid email";
    }
}
?>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Create Account</h3>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3 mx-3" style="background-color:<?php echo $color; ?>;
                                     color: white; padding: 5px; border-radius: 5px; text-align: center;">
                                        <p><?php echo $message; ?></p>
                                    </div>
                                    <form method="post" action="register.php" onsubmit="return validateForm()">
                                        <div class="form-floating mb-3 mx-3">
                                            <input class="form-control" id="inputName" type="text"
                                                placeholder="Enter full name" name="inputName" />
                                            <label for="inputName">Full Name</label>
                                        </div>
                                        <div class="form-floating mb-3 mx-3">
                                            <input class="form-control" id="inputEmail" type="text"
                                                placeholder="name@example.com" name="inputEmail" />
                                            <label for="inputEmail">Email address</label>
                                        </div>
                                        <div class="form-floating mb-3 mx-3">
                                            <input class="form-control" id="inputDob" type="date" name="inputDob" />
                                            <label for="inputDob">Date of Birth</label>
                                            <span class="error-span" style="color:grey; font-size:16px;">Should be at
                                                least 12 yrs old</span>
                                        </div>
                                        <div class="form-floating mb-3 mx-3">
                                            <input class="form-control" id="inputWeight" type="float"
                                                name="inputWeight" />
                                            <label for="inputWeight">Weight (kg)</label>
                                        </div>
                                        <div class="form-floating mb-3 mx-3">
                                            <input class="form-control" id="inputPassword" type="password"
                                                placeholder="Create a password" name="inputPassword" />
                                            <label for="inputPassword">Password</label>
                                        </div>

                                        <div class="mt-4 mb-0">
                                            <div class="d-grid"><button class="btn btn-primary btn-block"
                                                    type="submit">Create
                                                    Account</button></div>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small"><a href="login.php">Have an account? Go to login</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <script>
            document.getElementById('inputDob').setAttribute('max', new Date(new Date().getFullYear() - 12, 0, 1).toISOString().split('T')[0]);

            function validateForm() {
                var email = document.getElementById("inputEmail").value;
                var password = document.getElementById("inputPassword").value;
                var fullName = document.getElementById("inputName").value;
                var dob = document.getElementById("inputDob").value;
                var weight = document.getElementById("inputWeight").value;
                var error = false;

                console.log(email, password, fullName, dob, weight);

                // Remove existing error spans
                var errorSpans = document.querySelectorAll('.error-span');
                errorSpans.forEach(function (span) {
                    span.remove();
                });

                // Validate each field
                if (email === "") {
                    displayError("inputEmail", "Email is required");
                    error = true;
                }
                if (password === "") {
                    displayError("inputPassword", "Password is required");
                    error = true;
                }
                if (fullName === "") {
                    displayError("inputName", "Full name is required");
                    error = true;
                }
                if (!isNaN(fullName)) {
                    displayError("inputName", "Name can't be a number");
                    error = true;
                }
                if (fullName.length < 3) {
                    displayError("inputName", "Name should be of at least 3 character");
                    error = true;
                }
                if (dob === "") {
                    displayError("inputDob", "Date of birth is required");
                    error = true;
                }
                if (weight === "") {
                    displayError("inputWeight", "Weight is required");
                    error = true;
                }
                if (isNaN(weight)) {
                    displayError("inputWeight", "Weight should be a number");
                    error = true;
                }
                if (weight > 100 || weight < 20) {
                    displayError("inputWeight", "Weight should be in between 20 to 100 kg");
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


        <?php include_once ("footer.php"); ?>