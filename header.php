<?php
if (session_status() === PHP_SESSION_NONE) 
{
    session_start();
}
?>

<header>
<div class="inner-header">
    <img src="pictures/temp_logo.jpg" width="100px">
    
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
