<?php

session_start();
include "db/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1)
    {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user["password"]))
        {
            // Store user session
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["email"] = $email;

            header("Location: index.php");
            exit;
        }
        else
        {
            echo "Invalid password <a href='login.php'>Login here</a>";
        }
    }
    else
    {
        echo "Incorrect email or password <a href='login.php'>Login here</a>";
    }

    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
    </head>

    <body>
        <h2>Login</h2>    

        <form method="post">
            <label for="email">Email</label><br>
            <input type="email" name="email" placeholder="Enter your email" required><br><br>

            <label for="password">Password</label><br>
            <input type="password" name="password" placeholder="Enter your password" required><br>

            <button type="submit">Login</button><br><br>

            <span>Don't have an account? <a href="register.html">Register here</a></span>
        </form>
    </body>
</html>