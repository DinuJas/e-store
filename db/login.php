<?php

session_start();
include "db.php";

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

            header("Location: ../index.php");
            exit;
        }
        else
        {
            echo "Invalid password <a href='../login.html'>Login here</a>";
        }
    }
    else
    {
        echo "Incorrect email or password <a href='../login.html'>Login here</a>";
    }

    $stmt->close();
    $conn->close();
}

?>