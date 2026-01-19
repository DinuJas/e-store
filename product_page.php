<?php
session_start();    
require_once "db/db.php";
require "includes/header.php";

// Get product id
if (isset($_GET["product_id"]))
{
    $product_id = $_GET["product_id"];   
    
    // Get product info
    $stmt = $conn->prepare("SELECT image, name, description, price FROM products WHERE product_id = ?");
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
                <div class="product">
                    <img src="pictures/<?php echo htmlspecialchars($row["image"]); ?>" width="350px" height="400px">
                    <span><?php echo htmlspecialchars($row["name"]); ?></span>
                    <span><?php echo htmlspecialchars($row["description"]); ?></span>
                    <span><?php echo htmlspecialchars($row["price"]); ?>,-</span>
                </div>

                <div class="product-info">
                    Information
                    Specs?
                    Reviews?
                </div>

                <div class="product-purchase">

                </div> 
            </div>
        </div>
    </main>

    <?php require "includes/footer.php"; ?>
</div>
</body>
</html>
