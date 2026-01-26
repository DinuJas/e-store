<?php
session_start();
require_once "db/db.php";
require "includes/header.php";

if (!isset($_SESSION["user_id"]))
{
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Get data  
$stmt = $conn->prepare("
    SELECT order_id, total_price
    FROM orders
    WHERE user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$order_id = $row["order_id"];
$total_price = $row["total_price"];

$stmt = $conn->prepare("
    SELECT order_id, product_id, quantity, price_at_purchase
    FROM order_items
    WHERE order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$items = $result->fetch_assoc();


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
                            <label>Card cvv:</label><br>
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