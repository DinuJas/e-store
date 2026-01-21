<?php
session_start();
require_once "db/db.php";
require "includes/header.php";

if (!isset($_SESSION["user_id"]))
{
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION["user_id"];

// Get active basket
$stmt = $conn->prepare("
    SELECT basket_id 
    FROM baskets 
    WHERE user_id = ?
    AND status = 'active'
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$basket_id = $row["basket_id"];

$stmt = $conn->prepare("SELECT product_id, quantity FROM basket_products WHERE basket_id = ?");
$stmt->bind_param("i", $basket_id);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basket</title>
    <link rel="stylesheet" href="css/index_style.css">
</head>
<body>
    <div class="container">
        <main>
            <div class="inner-main">
                <h1>Basket</h1>

                <?php if (empty($products)): ?>
                    <p>Your basket is empty.</p>
                <?php else: ?>
                    <?php foreach ($products as $item): ?>
                        <p>Product ID: <?= $item["product_id"] ?> - Qty: <?= $item["quantity"] ?></p>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <?php require "includes/footer.php"; ?>
</body>
</html>