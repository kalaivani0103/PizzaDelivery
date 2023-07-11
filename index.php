<?php
    session_start();
?>
<!DOCTYPE html>

<html>
<head>

<title>Pizza Delivery</title>
<link rel="stylesheet" type="text/css" href="styles.css"> <style>
/* CSS styles for the pizza menu */ 

        body {
        font-family: Arial, sans-serif; background-color: #f4f4f4;
        }
        .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        }

        h1 {

        text-align: center;
        margin-bottom: 20px;
        }

        .menu-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
        padding: 10px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .menu-item img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        margin-right: 20px;
        border-radius: 5px;
        }
        .menu-item-info {
        flex-grow: 1;
        }

        .menu-item-info h2 {
        margin: 0;
        }

        .menu-item-info p { margin: 5px 0;}
        .menu-item-price {
        font-weight: bold; }

        .logout-button {
        position: absolute;
        top: 10px;
        left: 10px;
        }

        .logout-button form {
         display: inline;
         margin: 0;
        padding: 0;
        }

        .logout-button input[type="submit"] {
        padding: 5px 10px;
        background-color: #4caf50;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        }

        </style>
        </head>
        <body>
        <div class="logout-button">
                <form action="logout.php" method="POST">
                <input type="submit" value="Logout">
                </form>
        </div>

        <div class="container">
        <h1>Welcome to Pizza Delivery</h1>
        <!-- Pizza Menu -->
        <div class="menu-item">
        <img src="Margherita.jpg" alt="Margherita">
        <div class="menu-item-info">
        <h2>Margherita</h2>
        <p>A classic pizza topped with fresh tomatoes, mozzarella cheese, and basil.</p> </div>
        <div class="menu-item-price">RM10</div>
        </div>
        <div class="menu-item">
        <img src="Pepperoni.jpg" alt="Pepperoni">
        <div class="menu-item-info">
        <h2>Pepperoni</h2>
        <p>A delicious pizza loaded with pepperoni slices and melted cheese.</p> </div>
        <div class="menu-item-price">RM12</div>
        </div>
        <div class="menu-item">
        <img src="Vegetarian.jpg" alt="Vegetarian">
        <div class="menu-item-info">
        <h2>Vegetarian</h2>
        <p>A pizza packed with a variety of fresh vegetables and savory flavors.</p>
        </div>

<div class="menu-item-price">RM11</div>
</div>
</div>
        <?php
        // Pizza menu with prices
        $menuItems = [
            "Margherita" => 10,
            "Pepperoni" => 12,
            "Vegetarian" => 11
        ];
        // Add more pizzas and prices here

        // Convert the menu items array to JSON for JavaScript usage 
        $menuItemPrices = json_encode($menuItems);
        ?>
        <form action="order.php" method="POST">
        <label for="name">Your Name:</label>
        <input type="text" name="name" id="name" required>
        <br><br>
        <label for="pizza">Select a pizza:</label> <select name="pizza" id="pizza">
        <?php
        foreach ($menuItems as $pizza => $price) {
        $pizza = htmlspecialchars($pizza, ENT_QUOTES, 'UTF-8'); 
        $price = htmlspecialchars($price, ENT_QUOTES, 'UTF-8'); 
        echo "<option value=\"$pizza\">$pizza (RM$price)</option>";
        }

        ?> 

        </select>
        <br><br>
        <label for="size">Select size:</label>
        <input type="radio" name="size" value="small" checked> Small
        <input type="radio" name="size" value="medium" > Medium
        <input type="radio" name="size" value="large"> Large
        <br><br>
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" min="1" required>
        <br><br>
        <label for="pickup_delivery">Pickup or Delivery:</label> <select name="pickup_delivery" id="pickup_delivery">
        <option value="pickup">Pickup</option> <option value="delivery">Delivery</option>
        </select>
        <br><br>
        <div id="delivery_address" style="display: none;">
        <label for="address">Delivery address:</label> <input type="text" name="address" id="address" required>
        </div>

        <br><br>
        <div id="total_amount">
        <label for="total">Total Amount:</label>
        <span id="total">RM0</span>
        </div>
        <br><br>
<!-- CSRF token -->
        <?php
        $token = bin2hex(random_bytes (32));
        $_SESSION['csrf_token'] = $token;
                ?>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>"> 
        <input type="hidden" name="total_amount" id="total_amount_input" value="0">
        <input type="submit" value="Place Order">
        </form>
        <script>
        // Show/hide delivery address based on user selection
        document.getElementById('pickup_delivery').addEventListener('change', function() {
        var deliveryAddress = document.getElementById('delivery_address');
        if (this.value === 'delivery') {
        deliveryAddress.style.display='block';
        } 
        else {
         deliveryAddress.style.display = 'none';
        }
       
        });

        // Calculate total amount dynamically
        const quantityInput = document.getElementById('quantity');
        const totalSpan = document.getElementById('total');
        const totalAmountInput = document.getElementById('total_amount_input'); const menuItemPrice = <?php echo $menuItemPrices; ?>;
        quantityInput.addEventListener('input', () => {
        const quantity = quantityInput.value;
        const pizzaSelect = document.getElementById('pizza');
        const selectedPizza = pizzaSelect.value;
        const selectedItemPrice = menuItemPrice[selectedPizza]; // Get the price for the selected pizza 
        const totalAmount = selectedItemPrice * quantity;
        totalSpan.textContent = `RM${totalAmount}`;
        // Update the value of the hidden input field 
        totalAmountInput.value = totalAmount;

});
</script>
</body>
</html>