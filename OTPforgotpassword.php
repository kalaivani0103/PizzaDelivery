<?php
    require "functions.php";

    // OTPforgotpassword.php
    // Retrieve the email from the session
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
    } else {
        // Email not found in session, redirect back to the forgot password page
        header("Location: forgotpassword.php");
        die;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email Verification To Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            max-width: 400px;
            padding: 40px;
            text-align: center;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 28px;
            color: #4caf50;
            margin-bottom: 20px;
        }
        button {
            padding: 10px 20px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Email Verification To Reset Password</h1>
            <br>
            <p>You need to verify your account to reset your password.</p>
            <a href="verifypassword.php">
                <button>Verify</button>
            </a>
    </div>
</body>
</html>
