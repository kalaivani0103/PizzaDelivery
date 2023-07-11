<?php
    require "functions.php";

    // forgotpassword.php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $email = $_POST['email'];

        if (emailExists($email)) {
            // Store the email in the session
            $_SESSION['email'] = $email;

            // Redirect to the OTPforgotpassword.php page
            header("Location: OTPforgotpassword.php");
            die;
        } else {
            // Email does not exist, redirect back to the forgot password page with an error message
            header("Location: forgotpassword.php?error=Failed");
            die;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
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

        h2 {
            font-size: 28px;
            color: #4caf50;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        button {
            padding: 10px 20px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <form method="post">
            <?php if (isset($_GET['error'])) { ?>
                <p class="error"><?php echo $_GET['error']; ?></p>
            <?php } ?>
            <label for="email">Email:</label>
            <input type="text" name="email" id="email" placeholder="Email">
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
