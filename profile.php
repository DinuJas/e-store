<?php
session_start();
require_once "db/db.php";
require "includes/header.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="css/index_style.css">
</head>

<body>
<div class="container">
    <main>
        <div class="inner-main">
            <h1>Profile</h1>
            <a href="payment.php">Current order</a><br>
            <a href="my_orders.php">My orders</a>
        </div>
    </main>

    <?php require "includes/footer.php"; ?>
</div>
</body>
</html>
