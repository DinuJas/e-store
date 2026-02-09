<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$user_id = (int)$_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get order that is pending
    $stmt = $conn->prepare("
        SELECT order_id
        FROM orders
        WHERE status = 'pending'
        AND user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $order_id = (int)$row["order_id"];

    // Calculate total price from order_items
    $total_price = 0;
    $shipping_cost = 100;

    $stmt = $conn->prepare("
        SELECT oi.quantity, p.price
        FROM order_items oi
        JOIN products p ON oi.product_id = p.product_id
        WHERE oi.order_id = ?
    ");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($item = $result->fetch_assoc()) {
        $total_price += $item["quantity"] * $item["price"];
    }
    
    // include shipping
    $total_price += $shipping_cost; 

    // Update orders with total price
    $stmt = $conn->prepare("
        UPDATE orders
        SET total_price = ?
        WHERE order_id = ?
    ");
    $stmt->bind_param("di", $total_price, $order_id);
    $stmt->execute();


    /* Stock */
    // Get all pending order items for user
    $stmt = $conn->prepare("
        SELECT oi.product_id, oi.quantity
        FROM order_items oi
        JOIN orders o ON oi.order_id = o.order_id
        WHERE o.status = 'pending'
        AND o.user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Prepare stock update statement
    $stmt = $conn->prepare("
        UPDATE products
        SET stock = stock - ?
        WHERE product_id = ?
        AND quantity >= ?
    ");

    while ($row = $result->fetch_assoc()) {
        $quantity = (int)$row['quantity'];
        $product_id = (int)$row['product_id'];
        $quantity = (int)$row['quantity'];

        $stmt->bind_param("ii", $quantity, $product_id);
        $stmt->execute();
    }

    // Update orders status to 'paid'
    $stmt = $conn->prepare("
        UPDATE orders
        SET status = 'paid'
        WHERE order_id = ?
        AND user_id = ?
    ");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();

    // Get basket that is active
    $stmt = $conn->prepare("
        SELECT basket_id
        FROM baskets
        WHERE status = 'active'
        AND user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $basket_id = (int)$row["basket_id"];

    // Update baskets status to 'ordered'
    $stmt = $conn->prepare("
        UPDATE baskets
        SET status = 'ordered'
        WHERE basket_id = ?
        AND user_id = ?
    ");
    $stmt->bind_param("ii", $basket_id, $user_id);
    $stmt->execute();

    header("Location: ../my_orders.php");
    exit();
}

$stmt->close();
$conn->close();
?>
