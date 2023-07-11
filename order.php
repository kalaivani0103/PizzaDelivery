
<!-- order.php -->
<!DOCTYPE html>
<html>
<head>
<title>Order Processing</title>
<link rel="stylesheet" type="text/css" href="styles.css"> <style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    }
    .container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff; border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
   
    h1, h2 {
    text-align: center;
    }

    p{
    margin-bottom: 10px;
    }

    .confirmation-message {
    margin-top: 30px;
    }
    .order-summary {
    border: 1px solid #ddd;
    border-radius: 5px; padding: 10px;
    margin-bottom: 20px;
    }
    .order-summary ul {
    list-style: none;
    padding: 0;
    }
    .order-summary li {
    margin-bottom: 5px;
    }
    .confirm-button {
    text-align: center;
    }
    .confirm-button button {
    padding: 10px 20px;
    background-color: #4caf50;
    color: #fff;
    border: none;
    border-radius: 4px; 
    cursor: pointer;
    }
    .confirm-button button:hover {
    background-color: #45a049;
    }
    .error-message { 
    color: red;
    text-align: center;
    }
</style>
</head>
<body>
<div class="container">
<?php
    header('Content-Type: text/html; charset=UTF-8');
    // Set the secure flag for the session cookie 
    ini_set('session.cookie_secure', 1);
    // Enable the HTTPOnly flag for the session cookie 
    ini_set('session.cookie_httponly', 1);
    // Force the session to only use cookies for session management 
    ini_set('session.use_only_cookies', 1);
    //Enable the strict mode for the session ID
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

    // Validate CSRF token
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']){
    // Retrieve the submitted form data
    $name = $_POST["name"];
    $pizza = $_POST["pizza"];
    $size = $_POST["size"];
    $quantity = $_POST["quantity"];
    $pickupDelivery = $_POST["pickup_delivery"];
    $address= $_POST["address"] ?? "" ;
    $totalAmount = $_POST["total_amount"] ?? '';


    // Encrypt sensitive information using AES encryption
    $key = 'KALAIVANISUBRAMANIAM'; // Replace with your own encryption key
    $iv = random_bytes (16); // Generate a random initialization vector

    // Assuming $address contain the sensitive data
    $encryptedAddress = openssl_encrypt($address, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

    // Convert the encrypted data to hexadecimal format
    $hexEncryptedAddress = bin2hex($encryptedAddress);

    $sname= "localhost";
    $uname= "root";
    $password = "";
    $db_name = "verify_db";
    $conn = mysqli_connect($sname, $uname, $password, $db_name);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
   

    // Execute the insert statement with the provided data
    $sql= $conn->prepare("INSERT INTO orders (name, pizza, size, quantity, pickup_delivery, address, total_amount) VALUES (?,?,?,?,?,?,?)");
    $sql->bind_param("sssssss", $name, $pizza, $size, $quantity,$pickupDelivery, $hexEncryptedAddress, $totalAmount);
    $sql->execute();
    
    // Close the database connection
    $conn->close();


    // Display order confirmation message and summary
    echo "<div class=\"confirmation-message\">";
    echo "<h2>Order Confirmation</h2>";
    echo "<p>Thank you, <strong>". htmlentities ($name, ENT_QUOTES, 'UTF-8')." </strong>, for your order!</p>"; 
    echo "<div class=\"order-summary\">";
    echo "<p>Order Summary: </p>";
    echo "<ul>";
    echo "<li><strong>Pizza:</strong>". htmlentities($pizza, ENT_QUOTES, 'UTF-8'). "</li>";
    echo "<li><strong>Size:</strong>".htmlentities ($size, ENT_QUOTES, 'UTF-8'). "</li>";
    echo "<li><strong>Quantity:</strong>". htmlentities($quantity, ENT_QUOTES, 'UTF-8') . "</li>";
    echo "<li><strong>Pickup/Delivery:</strong> ".htmlentities($pickupDelivery, ENT_QUOTES, 'UTF-8'). "</li>"; 
    if ($pickupDelivery === "pickup") {
        echo "<li><strong>Pickup Address:</strong> Pick up at the store: NO.2, JLN GEMBIRA, TMN SETIA, 81300 SKUDAI, JOHOR. </li>";
    } else {
        echo "<li><strong>Delivery Address:</strong> " . htmlentities($address, ENT_QUOTES, 'UTF-8', false) . "</li>";
    }
    echo "<li><strong>Total Amount to Pay: RM</strong> ".htmlentities($totalAmount, ENT_QUOTES, 'UTF-8'). "</li>";
    echo "</ul>";
    echo "</div>";
    echo "<div class=\"confirm-button\">";
    echo "<form action=\"confirmation.php\" method=\"post\">";
    echo "<input type=\"hidden\" name=\"name\" value=\"$name\">";
    echo "<input type=\"hidden\" name=\"pizza\" value=\"$pizza\">";
    echo "<input type=\"hidden\" name=\"size\" value=\"$size\">";
    echo "<input type=\"hidden\" name=\"quantity\" value=\"$quantity\">";
    echo "<input type=\"hidden\" name=\"pickup_delivery\" value=\"$pickupDelivery\">";
    echo "<input type=\"hidden\" name=\"address\" value=\"$address\">";
    echo "<input type=\"hidden\" name=\"total_amount\" value=\"$totalAmount\">";
    echo "<input type=\"hidden\" name=\"csrf_token\" value=\"" . $_SESSION['csrf_token'] . "\">"; 
    echo "<button type=\"submit\">Confirm Order</button>";
    echo "</form>";
    echo "</div>";
    echo "</div>";
    } 
    
    else {
          // Invalid CSRF token, show error message
        echo "<p class=\"error-message\">Invalid CSRF token. Please try again.</p>";
    }
    ?>
    </div>
    </body>
  
 </html>
 