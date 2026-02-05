<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$user_id = (int)$_SESSION["user_id"];
$product_id = (int)$_GET["product_id"];

// Get quantity from
$stmt = $conn->prepare("
    SELECT oi.quantity
    FROM order_items oi
    JOIN orders o ON oi.order_id = o.order_id
    WHERE o.user_id = ?
    AND oi.product_id = ?
");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $quantity = (int)$row["quantity"];

    if ($quantity > 1) {
        $stmt = $conn->prepare("
            UPDATE order_items oi
            JOIN orders o ON oi.order_id = o.order_id
            SET oi.quantity = oi.quantity - 1
            WHERE o.user_id = ?
            AND oi.product_id = ?
        ");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();

        header("Location: ../payment.php");
        exit();
    }
    else {
        // Delete all order items
        $stmt = $conn->prepare("
            DELETE oi
            FROM order_items oi
            JOIN orders o ON oi.order_id = o.order_id
            WHERE o.user_id = ?
            AND oi.product_id = ?
        ");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();

        // Delete the order it self
        $stmt = $conn->prepare("
            DELETE FROM orders
            WHERE status = 'pending'
            AND user_id = ?
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }
    
    // Recalculate total_price
    $stmt = $conn->prepare("
        UPDATE orders o
        SET o.total_price = COALESCE((
            SELECT SUM(oi.quantity * p.price)
            FROM order_items oi
            JOIN products p ON p.product_id = oi.product_id
            WHERE oi.order_id = o.order_id
        ), 0)
        WHERE o.user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    header("Location: ../payment.php");
    exit();
}
?>
