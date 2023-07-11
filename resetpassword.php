<?php
    require "functions.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        

	    // Validate password strength
	    $uppercase = preg_match('@[A-Z]@', $newPassword);
	    $lowercase = preg_match('@[a-z]@', $newPassword);
	    $number    = preg_match('@[0-9]@', $newPassword);
	    $specialChars = preg_match('@[^\_]@', $newPassword);
  
	    if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($newPassword) < 8) {
		 echo "<script>
		 alert('Password should be at least 8 characters in length and should include at least one upper case letter, one lower case letter, one number, and one special character.');
		 window.location.href='register.php';
		 </script>";
		 exit();
	    }

        if ($newPassword != $confirmPassword) {
            // Passwords do not match, display an error message
            header("Location: resetpassword.php?error=Passwords do not match");
            die;
        }

        // Update the password in the database
        if (updatePassword($newPassword)) {
            // Password updated successfully
            header("Location: login.php");
            die;
        } else {
            // Failed to update the password, display an error message
            header("Location: resetpassword.php?error=Failed to update password");
            die;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
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

        input[type="password"] {
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
        .password-field {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
        }

        .password-input {
            flex-grow: 1;
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            width: 18px;
            height: 18px;
        }
    </style>
    <script>
        function togglePasswordVisibility(inputId, toggleId) {
            var passwordInput = document.getElementById(inputId);
            var passwordToggle = document.getElementById(toggleId);
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                passwordToggle.src = "eye-open.png";
            } else {
                passwordInput.type = "password";
                passwordToggle.src = "eye-closed.png";
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <form method="post">
            <?php if (isset($_GET['error'])) { ?>
                <p class="error"><?php echo $_GET['error']; ?></p>
            <?php } ?>
            <br> <br>
            <label for="new_password">New Password:</label>
            <div class="password-field">
                <input type="password" name="new_password" id="new_password" placeholder="New Password" class="password-input" required>
                <img src="eye-closed.png" alt="Toggle Password" id="password-toggle" class="password-toggle" onclick="togglePasswordVisibility('new_password', 'password-toggle')">
            </div>
            <br> <br>
            <label for="confirm_password">Confirm Password:</label>
            <div class="password-field">
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" class="password-input" required>
                <img src="eye-closed.png" alt="Toggle Password" id="confirm-password-toggle" class="password-toggle" onclick="togglePasswordVisibility('confirm_password', 'confirm-password-toggle')">
            </div>
            <br> <br>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
