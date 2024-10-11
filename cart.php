<?php 
include('header.php'); 
include('nav.php'); 

session_start();

// Database connection details
$servername = "localhost";
$username = "root";  
$password = "";  
$dbname = "grocery_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        // Add item to cart
        $productId = (int)$_POST['product_id'];
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]++; // Increase quantity if already in cart
        } else {
            $_SESSION['cart'][$productId] = 1; // Add new item with quantity 1
        }
    } elseif (isset($_POST['remove'])) {
        // Remove item from cart
        $productId = (int)$_POST['remove'];
        unset($_SESSION['cart'][$productId]);
    } elseif (isset($_POST['update'])) {
        // Update item quantity
        foreach ($_POST['quantity'] as $productId => $quantity) {
            $productId = (int)$productId;
            $quantity = (int)$quantity;  // Ensure quantity is an integer

            if ($quantity <= 0) {
                unset($_SESSION['cart'][$productId]);  // Remove item if quantity is 0 or less
            } else {
                $_SESSION['cart'][$productId] = $quantity;  // Update quantity
            }
        }
    }
}

// Fetch product details for cart items
$cartProducts = [];
$totalPrice = 0;

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $sql = "SELECT product_id, name, price FROM product WHERE product_id IN ($ids)";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $productId = $row['product_id'];
        $price = (float)$row['price'];  // Ensure price is a float
        $quantity = (int)$_SESSION['cart'][$productId];  // Ensure quantity is an integer

        $cartProducts[$productId] = [
            'name' => $row['name'],
            'price' => $price,
            'quantity' => $quantity
        ];
        
        // Calculate total price - ensure both values are numbers
        $totalPrice += $price * $quantity;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <h1>Your Shopping Cart</h1>
    <?php if (empty($cartProducts)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <form method="post">
            <table>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($cartProducts as $productId => $product): ?>
                    <tr>
                        <td><?php echo $product['name']; ?></td>
                        <td>$<?php echo number_format($product['price'], 2); ?></td>
                        <td>
                            <input type="number" name="quantity[<?php echo $productId; ?>]" value="<?php echo $product['quantity']; ?>" min="0">
                        </td>
                        <td>
                            <button type="submit" name="remove" value="<?php echo $productId; ?>">Remove</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <button type="submit" name="update">Update Cart</button>
        </form>
        <h2>Total Price: $<?php echo number_format($totalPrice, 2); ?></h2>
    <?php endif; ?>
    <a href="products.php">Continue Shopping</a>
</body>
</html>
