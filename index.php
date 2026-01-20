<?php
session_start();
include "db/db.php";
require "includes/header.php"; 

// Get Username
$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("SELECT username FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc())
{
    $username = $row["username"];
}

// Get product info
$stmt = $conn->prepare("SELECT product_id, image, name, description, price FROM products");
$stmt->execute();
$product_info = $stmt->get_result();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-store</title>
    <link rel="stylesheet" href="css/index_style.css">
</head>

<body>
<div class="container">
    <main>
        <div class="inner-main">
            <h1>TEST PRODUCTS</h1>
            <div class="product-display">
                <?php while ($row = $product_info->fetch_assoc()): ?>
                    <div class="product">
                        <a href="product_page.php?product_id=<?php echo htmlspecialchars($row["product_id"]) ?>">
                            <img src="pictures/<?php echo htmlspecialchars($row["image"]); ?>" width="350px" height="400px">
                            <span><?php echo htmlspecialchars($row["name"]); ?></span>
                            <span><?php echo htmlspecialchars($row["description"]); ?></span>
                            <span><?php echo htmlspecialchars($row["price"]); ?>,-</span>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </main>

    <?php require "includes/footer.php"; ?>
</div>
</body>
</html>
