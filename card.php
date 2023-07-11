<!DOCTYPE html>
<html>
<head>
<title>Payment Processing</title>
<link rel="stylesheet" type="text/css" href="styles.css">
<style>
        body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        }

        .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
        text-align: center;
        }

        .payment-form {
        margin-top: 30px;
        }

        .payment-form label {
        display: block;
        margin-bottom: 10px;
        }
      
        .payment-form input[type="text"] {
        width: 100%;
        padding: 5px;
        margin-bottom: 10px;
        }

        .payment-form button [type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        }
        .payment-message {
        margin-top: 30px;
        text-align: center;
        font-size: 20px;
        }
        .success-message {
        color: green;
        }

        .error-message { 
        color: red;
        }

</style>
<script>
        // Automatically add '/' symbol in the expiration field and restrict to numbers 
        function formatExpirationDate() {
        var expirationDateInput = document.getElementById('expiration_date');
        var expirationDate = expirationDateInput.value;
        var formattedExpirationDate = " " ;

        // Remove non-digit characters
        expirationDate = expirationDate.replace(/\D/g, '');

        // Limit to a maximum of 4 digits
        expirationDate = expirationDate.slice(0, 4);

        // Format with '/'
        if (expirationDate.length >= 3) {
        formattedExpirationDate = expirationDate.slice(0, 2) + '/' + expirationDate.slice(2, 4);
        } else {
            formattedExpirationDate = expirationDate;
        }

        expirationDateInput.value = formattedExpirationDate;
        }
        </script>
        </head>

<body>
    <?php
        header('Content-Type: text/html; charset=UTF-8');
        // Set the secure flag for the session cookie 
        ini_set('session.cookie_secure', 1);
        // Enable the HTTPOnly flag for the session cookie 
        ini_set('session.cookie_httponly', 1);
        // Force the session to only use cookies for session management 
        ini_set('session.use_only_cookies', 1);
        // Enable the strict mode for the session ID
        ini_set('session.use_strict_mode', 1);
        // Set the session cookie to be accessible only through the HTTP protocol 
        ini_set('session.cookie_samesite', 'Strict');
        session_start();
        // Generate a new session ID upon successful authentication 
        function regenerateSessionId()
        {
        // Create a new session ID
        session_regenerate_id(true);
        // Store the new session ID
        $newSessionId = session_id();
        // Return the new session ID
        return $newSessionId;
        }
?>

<div class="container">
        <h1>Payment Gateway</h1>

        <div class="payment-form">
            <form method="POST" action="payment.php">
                <p>Please enter your payment information:</p>
                <br>
                <label for="name">Name:</label>
                <input type="text" name="name" value="<?php echo isset($_POST['name']) ? htmlentities($_POST['name'], ENT_QUOTES, 'UTF-8') : ''; ?>" required><br>
                <label for="card_number">Card Number:</label>
                <input type="text" name="card_number" pattern="[0-9]+" required><br>
                <label for="expiration_date">Expiration Date:</label>
                <input type="text" name="expiration_date" id="expiration_date" oninput="formatExpirationDate()" required><br>
                <label for="cvv">CVV:</label>
                <input type="text" name="cvv" pattern="[0-9]{3}" required><br>
                <button type="submit">Pay Now</button>
            </form>
        </div>
    </div>
</body>
</html>