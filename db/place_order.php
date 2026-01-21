<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"])) 
{
    header("Location: ../login.php");
    exit();
}

$user_id   = (int)$_SESSION["user_id"];
$basket_id = (int)$_GET["basket_id"];

// Get basket products
$stmt = $conn->prepare("
    SELECT product_id, quantity, price
    FROM basket_products
    JOIN products USING (product_id)
    WHERE basket_id = ?
");
$stmt->bind_param("i", $basket_id);
$stmt->execute();
$result = $stmt->get_result();

$total_price = 0;
$items = [];

while ($row = $result->fetch_assoc()) {
    $items[] = $row;
    $total_price += $row["price"] * $row["quantity"];
}

// Create order 
$stmt = $conn->prepare("
    INSERT INTO orders (user_id, total_price)
    VALUES (?, ?)
");
$stmt->bind_param("id", $user_id, $total_price);
$stmt->execute();

$order_id = $conn->insert_id;

// Insert order items 
$stmt = $conn->prepare("
    INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase)
    VALUES (?, ?, ?, ?)
");

foreach ($items as $item) {
    $stmt->bind_param(
        "iiid",
        $order_id,
        $item["product_id"],
        $item["quantity"],
        $item["price"]
    );
    $stmt->execute();
}

// Redirect to payment 
header("Location: ../payment.php?order_id=" . $order_id);
exit();
?>