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

// TODO: fix when ordered an item and then removing every ite from that order messes things up

$stmt->close();
$conn->close();
?>
