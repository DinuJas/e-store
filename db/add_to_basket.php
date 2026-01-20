<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}

$user_id = (int)$_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php");
    exit;
}

$product_id = (int)$_POST["product_id"];
$quantity = max(1, (int)$_POST["quantity"]);

$conn->begin_transaction();

try {

    // Get active basket
    $stmt = $conn->prepare("
        SELECT basket_id 
        FROM baskets 
        WHERE user_id = ? AND status = 'active'
        LIMIT 1
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a basket is already active
    if ($row = $result->fetch_assoc()) 
    {
        $basket_id = $row["basket_id"];
    } 
    else 
    {
        // Create basket
        $stmt = $conn->prepare("
            INSERT INTO baskets (user_id, status)
            VALUES (?, 'active')
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $basket_id = $conn->insert_id;
    }

    // Check if product already in basket
    $stmt = $conn->prepare("
        SELECT quantity 
        FROM basket_products
        WHERE basket_id = ? AND product_id = ?
    ");
    $stmt->bind_param("ii", $basket_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) 
    {
        // Update quantity
        $new_quantity = $row["quantity"] + $quantity;

        $stmt = $conn->prepare("
            UPDATE basket_products
            SET quantity = ?
            WHERE basket_id = ? AND product_id = ?
        ");
        $stmt->bind_param("iii", $new_quantity, $basket_id, $product_id);
        $stmt->execute();
    } 
    else 
    {
        // Insert new product
        $stmt = $conn->prepare("
            INSERT INTO basket_products (basket_id, product_id, quantity)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("iii", $basket_id, $product_id, $quantity);
        $stmt->execute();
    }

    $conn->commit();

    header("Location: ../product_page.php?product_id=" . $product_id);
    exit;
} 
catch (Exception $e) 
{
    $conn->rollback();
    die("Something went wrong");
}




