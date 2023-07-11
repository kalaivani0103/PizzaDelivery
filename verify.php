<?php
    require "mail.php";
    require "functions.php";

    $errors = array();

    if ($_SERVER['REQUEST_METHOD'] == "GET" && !check_verified($_SESSION['email'])) {
        
        // Retrieve the email from the session
        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];
        }

        // Send email
        $vars['code'] = rand(10000, 99999);

        // Save to database
        $vars['expire_time'] = (time() + (20));
        $vars['email'] = $_SESSION['email'];

        $query = "insert into verify (code,expire_time,email) values (:code,:expire_time,:email)";
        database_run($query, $vars);

        $message = "Your code is " . $vars['code'];
        $subject = "Email verification";
        $recipient = $vars['email'];
        send_mail($recipient, $subject, $message);
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (!check_verified($_SESSION['email'])) {
            $query = "SELECT * FROM verify WHERE code = :code AND email = :email";
            $vars = array(
                'email' => $_SESSION['email'],
                'code' => $_POST['code']
            );
    
            $row = database_run($query, $vars);
    
            if (is_array($row)) {
                $row = $row[0];
                $time = time();
    
                if ($row->expire_time > $time) {
                    $query = "UPDATE users SET email_verified = :email WHERE email = :email LIMIT 1";
                    database_run($query, $vars);
    
                    header("Location: login.php");
                    die;
                } else {
                    echo "Code expired";
                    header("Location: verify.php");
                    die;
                }
            } else {
                echo "Wrong code";
            }
        } else {
            echo "You're already verified";
            header("Location: login.php");
            die;
        }
    }
    
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Account Verification</title>
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
        input[type="text"] {
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Email Verification</h1>
        <div>
            <br>A 5-digit pin number was sent to your email address.<br>
            <div>
                <?php if (count($errors) > 0): ?>
                    <?php foreach ($errors as $error): ?>
                        <?= $error ?> <br>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div><br>
            <form method="post">
                <input type="text" name="code" placeholder="Enter your Pin number"><br>
                <br>
                <input type="submit" value="Verify">
            </form>
        </div>
    </div>
</body>
</html>
