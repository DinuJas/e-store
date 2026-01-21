<?php
session_start();
require_once "db/db.php";
require "includes/header.php";

if (!isset($_SESSION["user_id"]))
{
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="css/index_style.css">
</head>
<body>
    <div class="container">
        <main>
            <div class="inner-main">
                <div class="payment-page">
                    <div class="payment-info">
                        <h3>Contact information</h3>
                        <form>
                            <label>First name:</label><br>
                            <input type="text"><br>
                            <label>Second name:</label><br>
                            <input type="text"><br>

                            <label>Card number:</label><br>
                            <input type="text"><br>
                            <label>Card expiration date:</label><br>
                            <input type="date"><br>
                            <label>Card cvc:</label><br>
                            <input type="number"><br>

                            <label>Address:</label><br>
                            <input type="text"><br>
                            <label>Post code:</label><br>
                            <input type="number"><br>

                            <label>Email:</label><br>
                            <input type="text"><br>
                            <label>Phone number:</label><br>
                            <input type="text"><br>

                            <button type="submit">Order</button>
                        </form>   
                    </div> 

                    <div class="payment-products">

                    </div>
                </div> 
            </div> 
        </main>
    </div>

    <?php require "includes/footer.php"; ?>
</body>
</html>