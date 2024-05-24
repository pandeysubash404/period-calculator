<?php
require_once '../../db_config.php';
// Create a new database connection
$config = mysqli_connect(DBHOST, DBUSER, DBPWD, DBNAME) or die("DB Not Connected");
?>