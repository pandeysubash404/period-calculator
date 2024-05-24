<?php include_once ("header.php");

require_once '../db_config.php';

// Create a new database connection
$conn = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$login_error = "";

if (isset($_POST["submitlogin"])) {
    $email = $_POST["email"];
    $password = md5($_POST["password"]);

    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        // Login successful
        session_start();
        $_SESSION["email"] = $email;
        $_SESSION["name"] = $row["name"];
        $_SESSION["logged_in"] = true;
        setcookie("last_activity", time(), time() + (60 * 60 * 24 * 30));

        header("Location: index.php");
        exit;
    } else {
        $login_error = "Invalid email or password !";
    }
} ?>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Login</h3>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($login_error)) { ?>
                                        <div class="mb-3 mx-3" style="background-color:red;
                                     color: white; padding: 5px; border-radius: 5px; text-align: center;">
                                            <p><?php echo $login_error; ?></p>
                                        </div>
                                    <?php } ?>
                                    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST"
                                        onsubmit="return validateloginForm()" name="loginForm">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputEmail" type="email"
                                                placeholder="name@example.com" name="email" />
                                            <label for="inputEmail">Email address</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputPassword" type="password"
                                                placeholder="Password" name="password" />
                                            <label for="inputPassword">Password</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <a class="small" href="password.php">Forgot Password?</a>
                                            <button class="btn btn-primary" type="submit"
                                                name="submitlogin">Login</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small"><a href="register.php">Need an account? Sign up!</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="me-8">
                        <button onclick="history.back()" class="btn btn-primary">Go Back</button>
                    </div>
                </div>
            </main>
        </div>
        <script>
            function validateloginForm() {
                var email = document.getElementById("inputEmail").value;
                var password = document.getElementById("inputPassword").value;
                var error = false;

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

        <?php
        $conn->close();
        include_once ("footer.php");
        ?>