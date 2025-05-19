<?php
ob_start();
session_start();

// Dynamically determine base path
$rootPath = dirname(__DIR__); // Goes to C:\xampp\htdocs\HMS_Bhavi
require_once $rootPath . '/config/config.php';

// Access control
if (!isset($_SESSION['username'])) {
    header("Location: {$baseurl}modules/auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>HMS</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <!-- Custom Styles -->
    <link href="<?= $baseurl ?>assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= $baseurl ?>assets/css/style.css" rel="stylesheet">
    <link href="<?= $baseurl ?>assets/css/sidebar.css" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Make baseurl available to JavaScript -->
    <script>
        const baseurl = "<?= $baseurl ?>";
    </script>
</head>

<body>
