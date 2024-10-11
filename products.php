<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grocery_db";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch categories for filtering
$category_sql = "SELECT * FROM categories";
$categories = $conn->query($category_sql);

// Check if category filter is applied
$selected_category = isset($_GET['category_id']) ? $_GET['category_id'] : '';

// Fetch products based on selected category
$sql = "SELECT p.product_id, p.name, p.description, p.price, p.stock_level, p.image, c.category_name 
        FROM product p 
        LEFT JOIN categories c ON p.category_id = c.category_id";
if (!empty($selected_category)) {
    $sql .= " WHERE p.category_id = $selected_category";
}
$result = $conn->query($sql);

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    
    $sql = "SELECT * FROM product WHERE product_id = $product_id";
    $product_result = $conn->query($sql);
    
    if ($product_result->num_rows > 0) {
        $product = $product_result->fetch_assoc();
        
        // Initialize the cart if not set
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Add product to cart or increment quantity
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$product_id] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1
            ];
        }
        
        echo json_encode(['success' => true, 'message' => 'Product added to cart']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Hamro Grocery</title>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .content {
            max-width: 1200px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        .category-filter {
            margin: 20px 0;
            text-align: center;
        }

        .category-filter select {
            padding: 8px;
            font-size: 16px;
        }

        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .product {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .product img {
            width: 100%;
            max-height: 150px;
            object-fit: cover;
        }

        .product h3 {
            margin: 10px 0;
        }

        .product p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }

        .product-price {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .add-to-cart {
            display: inline-block;
            padding: 10px 15px;
            background-color: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<!-- Include the header and navigation -->
<?php include('header.php'); ?>
<?php include('nav.php'); ?>

<div class="content">
    <h1>Our Products</h1>

    <!-- Category Filter -->
    <div class="category-filter">
        <form method="get" action="">
            <select name="category_id" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <?php while ($category = $categories->fetch_assoc()) { ?>
                    <option value="<?php echo $category['category_id']; ?>" <?php echo ($category['category_id'] == $selected_category) ? 'selected' : ''; ?>>
                        <?php echo $category['category_name']; ?>
                    </option>
                <?php } ?>
            </select>
        </form>
    </div>

    <!-- Product Display -->
    <div class="product-list">
        <?php if ($result->num_rows > 0) { ?>
            <?php while ($product = $result->fetch_assoc()) { ?>
                <div class="product">
                    <img src="images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" onerror="this.onerror=null; this.src='images/default.png';">
                    <h3><?php echo $product['name']; ?></h3>
                    <p><?php echo $product['description']; ?></p>
                    <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                    <button class="add-to-cart" onclick="addToCart(<?php echo $product['product_id']; ?>)">Add to Cart</button>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No products available in this category.</p>
        <?php } ?>
    </div>
</div>

<!-- Include the footer -->
<?php include('footer.php'); ?>

<script>
    function addToCart(productId) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert(response.message);
                    } else {
                        alert("Failed: " + response.message);
                    }
                } catch (e) {
                    alert("Error parsing response: " + xhr.responseText);
                }
            } else {
                alert("Error: " + xhr.status);
            }
        };

        xhr.onerror = function () {
            alert("Request failed. Please try again.");
        };

        xhr.send("product_id=" + encodeURIComponent(productId));
    }
</script>

</body>
</html>
