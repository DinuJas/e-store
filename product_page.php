<?php
session_start();    
require_once "db/db.php";
require "includes/header.php";

// Get product id
if (isset($_GET["product_id"]))
{
    $product_id = $_GET["product_id"];   
    
    // Get product info
    $stmt = $conn->prepare("
        SELECT image, name, description, price, product_info 
        FROM products 
        WHERE product_id = ?
    ");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product_info = $stmt->get_result();
    $row = $product_info->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page | <?php echo htmlspecialchars($row["name"]) ?></title>
    <link rel="stylesheet" href="css/index_style.css">
</head>

<body>
<div class="container">
    <main>
        <div class="inner-main">
            <h1> <?php echo htmlspecialchars($row["name"]); ?> | <?php echo htmlspecialchars($row["description"]) ?> </h1>
            <div class="product-page">
                <!-- TOP ROW -->
                <div class="product-top">
                    <div class="product">
                        <img src="pictures/<?php echo htmlspecialchars($row["image"]); ?>" width="350px" height="400px">
                        <span><?php echo htmlspecialchars($row["name"]); ?></span>
                        <span><?php echo htmlspecialchars($row["description"]); ?></span>
                        <span><?php echo number_format(htmlspecialchars($row["price"])); ?>,-</span>
                    </div>

                    <div class="product-purchase">
                        <form action="db/add_to_basket.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo (int)$product_id; ?>">

                            <label>Quantity</label>
                            <input type="number" name="quantity" value="1" min="1">

                            <button type="submit">Add to basket</button>
                        </form>
                    </div> 
                </div>

                <!-- BOTTOM ROW -->
                <!-- nl2br adds new lines to product info if the text contains \n -->
                <h3>Product info</h3>
                <hr>
                <div class="product-info">
                    <?php echo nl2br(htmlspecialchars($row["product_info"])) ?>
                </div>
            </div>
        </div>
    </main>

    <?php require "includes/footer.php"; ?>
</div>
</body>
</html>
