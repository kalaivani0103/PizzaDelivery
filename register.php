<?php  
require "functions.php";
$errors = array();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errors = signup($_POST);
    if (count($errors) == 0) {
        header("Location: verification.php");
        die;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
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
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        p {
            margin-top: 10px;
            font-size: 14px;
        }
        p a {
            color: #4caf50;
            text-decoration: none;
        }
        p a:hover {
            text-decoration: underline;
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
        <h1>Registration</h1>
        <form method="post">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Email" required>
			<br>
            <label for="password">Password:</label>
            <div class="password-field">
				<input type="password" name="password" id="password" placeholder="Password" required class="password-input">
				<img src="eye-closed.png" alt="Toggle Password" id="password-toggle" class="password-toggle" onclick="togglePasswordVisibility('password', 'password-toggle')">
			</div>
			<br>
            <label for="password2">Retype Password:</label>
            <div class="password-field">
				<input type="password" name="password2" id="password2" placeholder="Retype Password" required class="password-input">
				<img src="eye-closed.png" alt="Toggle Password" id="password2-toggle" class="password-toggle" onclick="togglePasswordVisibility('password2', 'password2-toggle')">
			</div>
			<br>
            <input type="submit" value="Register">
            
        </form>
		<br>
		<p>Already have an account? <a href="login.php">Login here</a>.</p>
        <p class="welcome-link"><a href="welcome.php">Home</a></p>
        <?php if (count($errors) > 0): ?>
            <div>
                <?php foreach ($errors as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
