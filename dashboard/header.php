<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - Period Calculator</title>
    <!-- Favicons -->
    <link href="../assets/img/favicon.png" rel="icon">
    <!-- <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" /> -->
    <!-- <link href="fullcalendar/fullcalendar.min.css" rel="stylesheet"> -->
    <!-- <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="css/styles.css" rel="stylesheet" />
    <!-- <link href="css/calendar.css" rel="stylesheet" /> -->
    <!-- <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script> -->
    <script src="js/all.js"></script>
</head>
<style>
    .blur-background {
        top: 10px;
        left: 0;
        width: 100%;
        height: 100%;
        backdrop-filter: blur(5px);
        z-index: 5;
    }

    .display-content {
        position: relative;
        z-index: 10;
    }

    /* Change the background color of the datepicker */
    .datepicker {
        background-color: #f8f9fa;
        /* Light gray */
    }

    /* Change the background color of the selected date */
    .datepicker table td.active,
    .datepicker table td.active:hover,
    .datepicker table td.active.disabled,
    .datepicker table td.active.disabled:hover {
        background-color: #007bff;
        /* Blue */
    }

    /* Change the background color of today's date */
    .datepicker table td.today,
    .datepicker table td.today:hover,
    .datepicker table td.today.disabled,
    .datepicker table td.today.disabled:hover {
        background-color: #28a745;
        /* Green */
    }

    /* Change the background color of the hovered date */
    .datepicker table td:hover,
    .datepicker table td:hover:hover,
    .datepicker table td:hover.disabled,
    .datepicker table td:hover.disabled:hover {
        background-color: #6c757d;
        /* Dark gray */
    }

    /* Change the text color of the selected date */
    .datepicker table td.active>a,
    .datepicker table td.active:hover>a,
    .datepicker table td.active.disabled>a,
    .datepicker table td.active.disabled:hover>a {
        color: #fff;
        /* White */
    }

    /* Change the text color of today's date */
    .datepicker table td.today>a,
    .datepicker table td.today:hover>a,
    .datepicker table td.today.disabled>a,
    .datepicker table td.today.disabled:hover>a {
        color: #fff;
        /* White */
    }
</style>