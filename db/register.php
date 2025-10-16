<?php

include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Get form data
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? || email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0)
    {
        echo "Email or Username is already registered! <a href='../register.html'>Register here</a>";
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
            echo "Registration successful! <a href='../login.html'>Login here</a>";
        }
        else
        {
            echo "Error: " . $conn->error;
        }
    }

    $stmt->close();
    $conn->close();
}

?>