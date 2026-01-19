<?php
include "db/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Get form data
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $repeat_password = trim($_POST["repeat_password"]);

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? || email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if email is already used
    if ($stmt->num_rows > 0)
    {
        echo "Email or Username is already registered! <a href='register.php'>Register here</a>";
    }
    else
    {    
        // Check if password and repeated passwords match 
        if ($password != $repeat_password)
        {
            echo "Passwords does not match";  
        }
        else
        {
            // Hash password before saving
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashedPassword);
            
            if ($stmt->execute())
            {
                echo "Registration successful! <a href='login.php'>Login here</a>";
            }
            else
            {
                echo "Error: " . $conn->error;
            }
        }
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
        <title>Register</title>
    </head>

    <body>
        <h2>Register</h2>

        <form method="post">
            <label for="username">Username:</label><br>
            <input type="text" name="username" placeholder="Enter your username" required><br><br>

            <label for="email">Email:</label><br>
            <input type="email" name="email" placeholder="Enter your email" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" name="password" placeholder="Enter your password" required><br><br>

            <label for="repeat_password">Repeat password:</label><br>
            <input type="password" name="repeat_password" placeholder="Please repeat your password" required><br><br>
   
            <button type="submit">Register</button><br><br>

            <span>Already have an account? <a href="login.html">Login here</a></span>
        </form>
    </body>
</html>