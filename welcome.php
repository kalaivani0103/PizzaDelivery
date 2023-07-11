<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Pizza Delivery</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
            text-align: center;
        }
        h1 {
            font-size: 36px;
            color: #4caf50;
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            color: #888;
            margin-bottom: 20px;
        }
        .pizza-images {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
        }
        .pizza-images img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 10px;
            cursor: pointer;
            transition: transform 0.3s ease-in-out;
        }
        .pizza-images img:hover {
            transform: scale(1.1);
        }
        .pizza-images img:hover::after {
            content: attr(title);
            display: block;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 5px;
            white-space: nowrap;
        }
        .button-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .button-container .button {
            padding: 10px 20px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 10px;
            text-decoration: none;
            font-size: 18px;
        }
        .button-container .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Pizza Delivery</h1>
        <p>Delicious pizzas delivered right to your doorstep!</p>
        <div class="pizza-images">
            <img src="Margherita.jpg" alt="Margherita Pizza" title="Margherita Pizza">
            <img src="Pepperoni.jpg" alt="Pepperoni Pizza" title="Pepperoni Pizza">
            <img src="Vegetarian.jpg" alt="Vegetarian Pizza" title="Vegetarian Pizza">
        </div>
        <div class="button-container">
            <a href="register.php" class="button">Register</a>
            <a href="login.php" class="button">Login</a>
        </div>
    </div>
</body>
</html>
