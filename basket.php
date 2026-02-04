<?php
session_start();
require_once "db/db.php";
require "includes/header.php";

if (!isset($_SESSION["user_id"]))
{
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION["user_id"];

// Get active basket
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

// Get products that are inside of the basket
$stmt = $conn->prepare("
    SELECT 
        p.product_id,
        p.name,
        p.image,
        p.price,
        bp.quantity
    FROM basket_products bp
    JOIN products p ON bp.product_id = p.product_id
    WHERE bp.basket_id = ?
");
$stmt->bind_param("i", $basket_id);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

// Calculate total quantity and price for summary
$total_quantity = 0;
$total_price = 0;

foreach ($products as $item)
{
    $total_quantity += $item["quantity"];
    $total_price += $item["price"] * $item["quantity"];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basket</title>
    <link rel="stylesheet" href="css/index_style.css">
</head>
<body>
    <div class="container">
        <main>
        <div class="inner-main">
            <div class="basket-page">
                <div class="basket-info">
                <h1>Basket</h1>

                <?php if (empty($products)): ?>
                    <p>Your basket is empty.</p>
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
                                <td data-label="Image">
                                    <img src="pictures/<?= htmlspecialchars($item["image"]) ?>" width="75px">
                                </td >
                                <td data-label="Name"><?= htmlspecialchars($item["name"]) ?></td>
                                <td data-label="Price"><?= number_format($item["price"], 2, '.', ' ') ?>,-</td>
                                <td data-label="Quantity"><?= (int)$item["quantity"] ?></td>
                                <td data-label="Total">
                                    <?= number_format($item["price"] * $item["quantity"], 2, '.', ' ') ?>,-
                                </td>
                                <td><a href="db/remove_product.php?product_id=<?= $item["product_id"] ?>">X</a></td>
                            </tr>
                    <?php endforeach; ?>
                    </table>
                <?php endif; ?>
                </div>

                <!-- Summary of products and go to payment -->
                <div class="basket-summary">
                    <h2>Summary</h2>

                    <?php if (empty($products)): ?>
                        <p>No items in basket.</p>
                    <?php else: ?>
                        <p><strong>Total items:</strong> <?= htmlspecialchars($total_quantity) ?></p>
                        <p><strong>Total price:</strong> <?= number_format($total_price, 2, '.', ' ') ?>,-</p>

                        <!-- TODO: fix getting basket_id without using $_GET (prevent someone adding someone else order_id in URL)-->
                        <form action="db/place_order.php" method="POST">
                            <button type="submit">Place order</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        </main>
    </div>

    <?php require "includes/footer.php"; ?>
</body>
</html>