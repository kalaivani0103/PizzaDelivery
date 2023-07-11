<!-- confirmation.php -->
<!DOCTYPE html>
<html>
<head>
<title>Order Confirmation</title>
<link rel="stylesheet" type="text/css" href="styles.css">
<style>
        .container {
        max-width: 600px;
        margin: 0 auto; padding: 20px;
        }
        h1 {
        text-align: center;
        }
        .order-summary {
        margin-top: 30px;
        background-color: #f2f2f2;
        padding: 10px;
        border-radius: 5px;
        }
        .order-summary ul {
        list-style-type: none;
        padding: 0;
        }

        .order-summary 11 {
        margin-bottom: 10px;
        }
        .payment-options {
        margin-top: 30px;
        }
        .payment-options label {
        display: block;
        margin-bottom: 10px;
        }
       
        .payment-options select {
        width: 100%;
        padding: 5px;
        margin-bottom: 10px;
        }

        .payment-options button [type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        }
        .error-message {
        color: red;}

        </style>
        </head>
        <body>
        <div class="container">
        <h1>Order Confirmed</h1>
        <?php
        header('Content-Type: text/html; charset=UTF-8');
        // Set the secure flag for the session cookie 
        ini_set('session.cookie_secure', 1);
        // Enable the HTTPOnly flag for the session cookie
        ini_set('session.cookie_httponly', 1);
        // Force the session to only use cookies for session managemen
        ini_set('session.use_only_cookies', 1);
        // Enable the strict mode for the session ID
        ini_set('session.use_strict_mode', 1);
        // Set the session cookie to be accessible only through the HTTP protocol 
        ini_set('session.cookie_samesite', 'Strict');
        session_start();
        // Generate a new session ID upon successful authentication 
        function regenerateSessionId(){
            // Create a new session ID
            session_regenerate_id(true);
            // Store the new session ID
            $newSessionId = session_id();
            // Return the new session ID
            return $newSessionId;
        }

        // Validate CSRF token
        if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        // Retrieve the submitted form data
        $name = $_POST["name"];
        $pizza = $_POST["pizza"];
        $size = $_POST["size"];
        $quantity = $_POST["quantity"];
        $pickupDelivery = $_POST["pickup_delivery"]; 
        $address = $_POST["address"] ?? "";
        $price = $_POST["total_amount"] ?? ''; // Retrieve the price from the previous page

        // Display order confirmation message and summary
        // echo "<h2>Order Confirmed</h2>";
        echo "<p>Thank you, <strong>". htmlentities ($name, ENT_QUOTES, 'UTF-8')." </strong>, for your order!</p>";
        echo "<div class=\"order-summary\">";
        echo "<p>Order Summary:</p>";
        echo "<ul>";
        echo "<li><strong>Pizza:</strong>". htmlentities($pizza, ENT_QUOTES, 'UTF-8'). "</li>";
        echo "<li><strong>Size:</strong>". htmlentities($size, ENT_QUOTES, 'UTF-8'). "</li>";
        echo "<li><strong>Quantity:</strong> " . htmlentities ($quantity, ENT_QUOTES, 'UTF-8') . "</li>";
        echo "<li><strong>Pickup/Delivery:</strong> ".htmlentities($pickupDelivery, ENT_QUOTES, 'UTF-8'). "</li>"; 
        if ($pickupDelivery === "delivery") {
            echo "<li><strong>Delivery Address:</strong> ".htmlentities($address, ENT_QUOTES, 'UTF-8', false)."</li>";
        }

        echo "</ul>";
        echo "</div>";

        // Display total price and payment options
        echo "<div class=\"payment-options\">";
        echo "<p>Total Price: RM " .htmlentities ($price, ENT_QUOTES, 'UTF-8')."</p>";
        echo "<p>Please select a payment method:</p>";
        echo "<form method=\"POST\" action=\"card.php\">";
        echo "<input type=\"hidden\" name=\"name\" value=\"$name\">";
        echo "<input type=\"hidden\" name=\"pizza\" value=\"$pizza\">";
        echo "<input type=\"hidden\" name=\"size\" value=\"$size\">";
        echo "<input type=\"hidden\" name=\"quantity\" value=\"$quantity\">";
        echo "<input type=\"hidden\" name=\"pickup_delivery\" value=\"$pickupDelivery\">";
        echo "<input type=\"hidden\" name=\"address\" value=\"$address\">";
        echo "<input type=\"hidden\" name=\"total-amount\" value=\"$price\">";
        echo "<label for=\"payment_method\">Payment Method:</label>";
        echo "<select name=\"payment_method\" id=\"payment_method\">";
        echo "<option value=\"credit_card\">Credit Card</option>";
        echo "<option value=\"paypal\">PayPal</option>";
        echo "</select>";

        // Display bank selection dropdown if credit card is chosen
        echo "<div id=\"bank_selection\" style=\"display: none;\">";
        echo "<label for=\"bank\">Select Bank:</label>";
        echo "<select name=\"bank\" id=\"bank\">";
        echo "<option value=\"bank1\">Bank Islam</option>";
        echo "<option value=\"bank2\">CIMB Bank</option>";
        echo "<option value=\"bank3\">Hong Leong Bank</option>";
        echo "</select>";
        echo "</div>";

        echo "<button type=\"submit\">Pay</button>";
        echo "</form>";
        echo "</div>";
        } 
        else {
        // Invalid CSRF token, show error message
        echo "<p class=\"error-message\">Invalid CSRF token. Please try again.</p>";
        }

?>
        <script>
            document.getElementById('payment_method').addEventListener('change', function() {
                var bankSelection = document.getElementById('bank_selection');
                if (this.value === 'credit_card') {
                    bankSelection.style.display = 'block';
                } else {
                    bankSelection.style.display = 'none';
                }
            });
        </script>