<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"]))
{
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Get active basket for this user
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
$product_id = $_GET["product_id"];

// Get quantity
$stmt = $conn->prepare("
    SELECT quantity 
    FROM basket_products
    WHERE basket_id = ?
    AND product_id = ?
");
$stmt->bind_param("ii", $basket_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$quantity = $row["quantity"];

// Reduce quantity
if ($quantity > 1)
{
    $stmt = $conn->prepare("
        UPDATE basket_products
        SET quantity = quantity - 1
        WHERE basket_id = ?
        AND product_id = ? 
    ");
    $stmt->bind_param("ii", $basket_id, $product_id);
    if ($stmt->execute())
    {
        header("Location: ../basket.php");
        exit();
    }
}
else 
{
    // Delete product 
    $stmt = $conn->prepare("
        DELETE FROM basket_products 
        WHERE basket_id = ? 
        AND product_id = ?
    ");
    $stmt->bind_param("ii", $basket_id, $product_id);
    if ($stmt->execute())
    {
        header("Location: ../basket.php");
        exit();
    }
}
?>