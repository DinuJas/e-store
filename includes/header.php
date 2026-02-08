<?php
session_start();
require_once "db/db.php";

$user_id = $_SESSION["user_id"];

// Getting username
$stmt = $conn->prepare("SELECT username FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc())
{
    $username = $row["username"];
}

// Show how many items that are in your basket
$basket_count = 0;

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

$stmt = $conn->prepare("
    SELECT SUM(quantity) AS total_items
    FROM basket_products
    WHERE basket_id = ?
");
$stmt->bind_param("i", $basket_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$basket_count = $row["total_items"];
?>

<header>
<div class="inner-header">
    <a href="index.php"><img src="pictures/temp_logo.jpg" width="100px"></a>
    
    <form>
        <input type="search" placeholder="Search">
        <button>Search</button>
    </form>

    <div>
        <a href="profile.php">
            <img src="pictures/temp_logo.jpg" width="50px" height="50px">
            <?php echo htmlspecialchars($username) ?>
        </a>
    </div>

    <a href="basket.php">
        <img src="pictures/temp_basket.png" width="50px" height="50px">
        <div><?php echo $basket_count ?></div>
    </a>
</header>
