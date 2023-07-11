<?php 

session_start();

function signup($data)
{
	$errors = array();
 
	// Perform email validation
	if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL)){
		$errors[] = "Please enter a valid email address.";
	}

	// Perform password validation
		if($data['password'] != $data['password2']){
			$errors[] = "Both passwords should be same.";
		}

	// Validate password strength
	 $uppercase = preg_match('@[A-Z]@', $data['password']);
	 $lowercase = preg_match('@[a-z]@', $data['password']);
	 $number    = preg_match('@[0-9]@', $data['password']);
	 $specialChars = preg_match('@[^\_]@', $data['password']);
  
	 if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($data['password']) < 8) {
		 echo "<script>
		 alert('Password should be at least 8 characters in length and should include at least one upper case letter, one lower case letter, one number, and one special character.');
		 window.location.href='register.php';
		 </script>";
		 exit(); //To terminate the registration session.
	 }
 
	$check = database_run("select * from users where email = :email limit 1",['email'=>$data['email']]);
	if(is_array($check)){
		$errors[] = "Email already exists";
	}

	//save
    if (count($errors) == 0) {
        // Encrypt sensitive information using AES encryption
        $key = 'KALAIVANISUBRAMANIAM'; // Replace with your own encryption key
        $iv = "TANMINGHUIiiiiii"; // An initialization vector

        $pass = $data['password'];

        // Assuming $address contain the sensitive data
        $encryptedPassword = openssl_encrypt($pass, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        // Convert the encrypted data to hexadecimal format
        $hexEncryptedPassword = bin2hex($encryptedPassword);

        $arr['email'] = $data['email'];
        $arr['password'] = $hexEncryptedPassword;
        $arr['date'] = date("Y-m-d H:i:s");

        $query = "insert into users (email,password,date) values (:email,:password,:date)";

        database_run($query, $arr);

        $_SESSION['email'] = $data['email']; // Set the "email" value in the session
    }
    return $errors;
}

function login($data)
{
	$errors = array();
 
	//validate 
	if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL)){
		$errors[] = "Please enter a valid email";
	}

	if(strlen(trim($data['password'])) < 8){
		$errors[] = "Password must be atleast 8 chars long";
	}
 
	//check

    if (count($errors) == 0) {
        $arr['email'] = $data['email'];
        $pass = $data['password'];

        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";

        $row = database_run($query, $arr);

        if (is_array($row)) {
            $row = $row[0];

            // Decrypt the stored password
            $key = 'KALAIVANISUBRAMANIAM'; // Replace with your encryption key
            $iv = "TANMINGHUIiiiiii"; // An initialization vector
            $encryptedPassword = hex2bin($row->password); // Convert the stored password back to binary format
            $decryptedPassword = openssl_decrypt($encryptedPassword, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

            if ($pass === $decryptedPassword) {
                $_SESSION['USER'] = $row;
                $_SESSION['LOGGED_IN'] = true;
                $_SESSION['email'] = $row->email; // Set the "email" value in the session
            } else {
                $errors[] = "wrong email or password";
            }
        } else {
            $errors[] = "wrong email or password";
        }
    }
    return $errors;
}

function database_run($query,$vars = array())
{
	$string = "mysql:host=localhost;dbname=verify_db";
	//pizzadelivery";
	//
	$con = new PDO($string,'root','');

	if(!$con){
		return false;
	}

	$stm = $con->prepare($query);
	$check = $stm->execute($vars);

	if($check){
		
		$data = $stm->fetchAll(PDO::FETCH_OBJ);
		
		if(count($data) > 0){
			return $data;
		}
	}

	return false;
}

function check_login($redirect = true){

	if(isset($_SESSION['USER']) && isset($_SESSION['LOGGED_IN'])){

		return true;
	}

	if($redirect){
		header("Location: login.php");
		die;
	}else{
		return false;
	}
	
}

function check_verified($email) {
    $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
    $row = database_run($query, ['email' => $email]);

    if (is_array($row)) {
        $row = $row[0];

        if ($row->email === $row->email_verified) {
            return true;
        }
    }

    return false;
}

function emailExists($email) {
    // Assuming you have a database connection established
    // Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "verify_db";
//pizzadelivery";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

    // Prepare the query
    $query = "SELECT * FROM users WHERE email = ?";

    // Create a prepared statement
    $stmt = $conn->prepare($query);

    // Bind the email parameter
    $stmt->bind_param("s", $email);

    // Execute the query
    $stmt->execute();

    // Fetch the result
    $result = $stmt->get_result();

    // Check if the email exists in the database
    if ($result->num_rows > 0) {
        return true;  // Email exists
    } else {
        return false; // Email does not exist
    }
}

function updatePassword($newPassword)
{
    // Assuming you have a database connection established
    // Connect to the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "verify_db";
	//pizzadelivery";
	
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
	
		// Encrypt sensitive information using AES encryption
		$key = 'KALAIVANISUBRAMANIAM'; // Replace with your own encryption key
		$iv = "TANMINGHUIiiiiii"; // An initialization vector

   		$pass= $newPassword;

		// Assuming $address contain the sensitive data
		$encryptedPassword = openssl_encrypt($pass, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

		// Convert the encrypted data to hexadecimal format
		$hashedPassword = bin2hex($encryptedPassword);

    // Hash the new password
    //$hashedPassword = hash('sha256', $newPassword);

    // Prepare the query to update the password
    $query = "UPDATE users SET password = ? WHERE email = ?";

    // Create a prepared statement
    $stmt = $conn->prepare($query);

    // Bind the parameters
    $stmt->bind_param("ss", $hashedPassword, $_SESSION['email']);

    // Execute the query
    if ($stmt->execute()) {
        // Password updated successfully
        return true;
    } else {
        // Failed to update the password
        return false;
    }
}
