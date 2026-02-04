<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$user_id = (int)$_SESSION["user_id"];

// 1. Get active basket
$stmt = $conn->prepare("
    SELECT basket_id 
    FROM baskets 
    WHERE user_id = ? AND status = 'active'
    LIMIT 1
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    die("No active basket found.");
}

$basket_id = (int)$row["basket_id"];

// 2. Get or create pending order
$stmt = $conn->prepare("
    SELECT order_id 
    FROM orders 
    WHERE user_id = ? AND status = 'pending'
    LIMIT 1
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $order_id = (int)$row["order_id"];
} else {
    $stmt = $conn->prepare("
        INSERT INTO orders (user_id, total_price, status)
        VALUES (?, 0, 'pending')
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $order_id = $conn->insert_id;
}

// 3. Get basket products
$stmt = $conn->prepare("
    SELECT bp.product_id, bp.quantity, p.price
    FROM basket_products bp
    JOIN products p ON p.product_id = bp.product_id
    WHERE bp.basket_id = ?
");
$stmt->bind_param("i", $basket_id);
$stmt->execute();
$result = $stmt->get_result();

// 4. Insert / update order items
$stmt = $conn->prepare("
    INSERT INTO order_items
        (order_id, product_id, quantity, price_at_purchase)
    VALUES (?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE
        quantity = quantity + VALUES(quantity)
");

while ($item = $result->fetch_assoc()) {
    $stmt->bind_param(
        "iiid",
        $order_id,
        $item["product_id"],
        $item["quantity"],
        $item["price"]
    );
    $stmt->execute();
}

// 5. Redirect to payment
header("Location: ../payment.php?order_id=" . $order_id);
exit();

$stmt->close();
$conn->close();
?>