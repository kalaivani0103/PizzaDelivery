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
</head>

    <body>
        <div class="container">
        <h1>Payment Processing</h1>
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

        // Simulated pseudonym service for payment gateway 
        function simulatePaymentGateway($totalAmount) {
        // Generate a random transaction ID
        $transactionId = uniqid('TXN');
        // Simulate a delay to mimic the communication with the payment gateway 
        usleep (5000000); // Sleep for 5 second
        // Simulate the response from the payment gateway
        $responseCode = rand(1, 2); // 1 for success, 2 for failure
        $responseMessage = ($responseCode === 1) ? 'Payment Successful' : 'Payment Failed';

        // Create a response obiect with the transaction details
        $response = [
        'transaction_id' => $transactionId, 
        'amount' => $totalAmount,
        'response_code' => $responseCode, 
        'response_message' => $responseMessage
        ];

        return $response;
    }
        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve the total amount from the form 
        $totalAmount = $_POST["total_amount"] ?? '';

        // Perform payment process using the simulated payment gateway 
        $paymentResponse = simulatePaymentGateway($totalAmount);

        // Handle the payment response
        if ($paymentResponse['response_code'] === 1) {
        // Payment successful
        // You can process the successful payment here (e.g., update the order status, store transaction details) 
        // Display success message or redirect to a success page
        echo '<p class="success-message">'.htmlentities($paymentResponse['response_message'], ENT_QUOTES, 'UTF-8').'</p>';
        echo '<p>Transaction ID: '.htmlentities($paymentResponse['transaction_id'], ENT_QUOTES, 'UTF-8') . '</p>'; // Inform user about the redirection
        echo '<p>Redirecting to homepage in <span id="countdown">10</span> seconds...</p>';
        // Countdown and redirect to index.php
        echo "<script>";
        echo 'var countdown = 10; ';
        echo 'var countdownElement = document.getElementById("countdown");'; 
        echo 'var countdownInterval = setInterval(function() {'; 
        echo ' countdown--;'; 
        echo ' countdownElement.textContent = countdown;';
        echo ' if (countdown == 0) {'; 
        echo '   clearInterval(countdownInterval);';
        echo '   window.location.href = "index.php";';
        echo ' }';
        echo '}, 1000);'; // 1000 milliseconds = 1 second
        echo '</script>';
        } 
        else {
        // Payment failed
        // You can handle the failed payment here (e.g., display error message, redirect to an error page) 
        echo '<p class="error-message">Payment Failed!</p>';
        echo '<p class="error-message">' . htmlentities($paymentResponse['response_message'], ENT_QUOTES, 'UTF-8') . '</p>';
         } 
    } else {
        // Invalid request, redirect to the order page or display an error message
        echo '<p class="error-message">Invalid request!</p>';
        }
    ?>
        <div class="payment-message">
        <!-- Payment response will be displayed here -->
        </div>
    </div>
    </body>
    </html>