<?php
session_start();
require_once "db/db.php";
require "includes/header.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$total_price = 0;

// Get order_id that is 'pending' from orders table
$stmt = $conn->prepare("
    SELECT order_id 
    FROM orders
    WHERE status = 'pending'
    AND user_id = ?
    LIMIT 1
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$order_id = (int)$row["order_id"];

// Join products with order_items and get every item for this order that is 'pending'
$stmt = $conn->prepare("
    SELECT p.product_id, p.image, p.name, p.price, o.quantity
    FROM products p
    JOIN order_items o ON p.product_id = o.product_id
    WHERE o.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

foreach ($products as $item) {
    $total_price += $item["price"] * $item["quantity"];
}

$shipping_cost = 100;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="css/index_style.css">
</head>

<body>
    <div class="container">
        <main>
            <div class="inner-main">
                <div class="payment-page">
                    <div class="payment-info">
                    <h3>Contact information</h3>

                    <!-- TODO: Finish able to pay for things -->
                    <div class="payment-products">
                        <h3>Your products</h3>

                        <?php if (empty($products)): ?>
                            <p>No order items</p>
                        <?php else: ?>
                            <table>
                                <tr>
                                    <th>Pictures</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Delete</th>
                                </tr>

                                <?php foreach ($products as $item): ?>
                                    <tr>
                                        <td>
                                            <img src="pictures/<?= $item["image"] ?>" width="75px">
                                        </td>
                                        <td><?= $item["name"] ?></td>
                                        <td><?= $item["price"] ?></td>
                                        <td><?= $item["quantity"] ?></td>
                                        <td><?= $item["price"] ?></td>
                                        <td><a href="db/remove_item.php?product_id=<?= $item["product_id"] ?>">X</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>

                            <h4>Details</h4>
                            <p>Shipping: 100,-</p>
                            <p>Total price: <?= $total_price + $shipping_cost ?></p>
                        <?php endif; ?>

                        <form action="db/order.php" method="POST">
                            <button>Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php require "includes/footer.php"; ?>
</body>

</html>
