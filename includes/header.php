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
?>

<header>
<div class="inner-header">
    <a href="index.php"><img src="pictures/temp_logo.jpg" width="100px"></a>
    
    <form>
        <input type="search" placeholder="Search">
        <button>Search</button>
    </form>

    <div>
        <img src="pictures/temp_logo.jpg" width="50px" height="50px">
        <?php echo htmlspecialchars($username) ?>
    </div>

    <img src="pictures/temp_basket.jpg" width="50px" height="50px">
</div>
</header>
