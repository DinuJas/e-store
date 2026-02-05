<?php
session_start();
require_once "db/db.php";
require "includes/header.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION["user_id"];

// Get all paid orders (should also show shipped and completed orders)
$stmt = $conn->prepare("
    SELECT order_id, total_price, status, created_at
    FROM orders
    WHERE status = 'paid'
    AND user_id = ?
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="css/index_style.css">
</head>
<body>
    <div class="container">
        <main>
            <div class="inner-main">
                <div class="my-orders-page">
                    <h3>My orders</h3>
                    <?php if (empty($products)): ?> 
                        <p>No orders</p>
                    <?php else: ?>
                        <table>
                            <tr>
                                <th>Order ID</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Created At</th>
                            </tr>

                            <?php foreach($products as $item): ?>
                            <tr>
                                <td><?= $item["order_id"] ?></td>
                                <td><?= number_format($item["total_price"], 2, '.', ' ') ?></td>
                                <td><?= $item["status"] ?></td>
                                <td><?= $item["created_at"] ?></td>
                            </tr>
                            <?php endforeach ?>
                        </table>
                    <?php endif ?>
                </div>
            </div>
        </main>
    </div>
    <?php require "includes/footer.php" ?>
</body>
</html>
