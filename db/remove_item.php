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
        $stmt = $conn->prepare("DELETE FROM order_items WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();

        header("Location: ../payment.php");
        exit();
    }
}
?>