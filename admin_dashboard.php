<?php
session_start();
include 'header.php'; // Include your header
include 'nav.php';    // Include your navbar

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grocery_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle adding/updating products
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $stock_level = $_POST['stock_level'];
    
    // Handle file upload
    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'images/' . $image_name;

    if (!empty($_POST['edit_product_id'])) {
        // Update existing product
        $edit_product_id = $_POST['edit_product_id'];
        $sql = "UPDATE product SET name=?, description=?, price=?, category_id=?, stock_level=?, image=? WHERE product_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdisii", $name, $description, $price, $category_id, $stock_level, $image_name, $edit_product_id);
        
        if ($stmt->execute() && move_uploaded_file($image_tmp_name, $image_folder)) {
            echo "<p>Product updated successfully!</p>";
        } else {
            echo "<p>Error updating product: " . $stmt->error . "</p>";
        }
    } else {
        // Add new product
        $sql = "INSERT INTO product (name, description, price, category_id, stock_level, image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdisi", $name, $description, $price, $category_id, $stock_level, $image_name);
        
        if ($stmt->execute() && move_uploaded_file($image_tmp_name, $image_folder)) {
            echo "<p>Product added successfully!</p>";
        } else {
            echo "<p>Error adding product: " . $stmt->error . "</p>";
        }
    }

    // Reset form after submission
    $name = '';
    $description = '';
    $price = '';
    $category_id = '';
    $stock_level = '';
}

// Handle deleting a product
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM product WHERE product_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        echo "<p>Product deleted successfully!</p>";
    } else {
        echo "<p>Error deleting product: " . $stmt->error . "</p>";
    }
}

// Fetch existing products
$sql = "SELECT p.product_id, p.name, p.description, p.price, p.stock_level, p.image, c.category_name 
        FROM product p 
        LEFT JOIN categories c ON p.category_id = c.category_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="content">
        <h1>Admin Dashboard</h1>
        
        <!-- Add Product Form -->
        <h2>Add / Edit Product</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="edit_product_id" value="<?php echo isset($_GET['edit_id']) ? $_GET['edit_id'] : ''; ?>">
            <label>Name:</label>
            <input type="text" name="name" required value="<?php echo isset($name) ? $name : ''; ?>">
            <label>Description:</label>
            <textarea name="description" required><?php echo isset($description) ? $description : ''; ?></textarea>
            <label>Price:</label>
            <input type="number" name="price" step="0.01" required value="<?php echo isset($price) ? $price : ''; ?>">
            <label>Category:</label>
            <select name="category_id" required>
                <?php
                // Fetch categories for dropdown
                $category_sql = "SELECT category_id, category_name FROM categories";
                $category_result = $conn->query($category_sql);
                while ($category = $category_result->fetch_assoc()) {
                    $selected = (isset($category_id) && $category_id == $category['category_id']) ? 'selected' : '';
                    echo "<option value='{$category['category_id']}' $selected>{$category['category_name']}</option>";
                }
                ?>
            </select>
            <label>Stock Level:</label>
            <input type="number" name="stock_level" required value="<?php echo isset($stock_level) ? $stock_level : ''; ?>">
            <label>Image:</label>
            <input type="file" name="image" required>
            <button type="submit" name="add_product">Save Product</button>
        </form>

        <!-- Current Products -->
        <h2>Current Products</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Category</th>
                <th>Stock Level</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
            <?php while ($product = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $product['product_id']; ?></td>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $product['description']; ?></td>
                    <td><?php echo $product['price']; ?></td>
                    <td><?php echo $product['category_name']; ?></td>
                    <td><?php echo $product['stock_level']; ?></td>
                    <td>
                        <img src="images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="50">
                    </td>
                    <td>
                        <a href="admin_dashboard.php?edit_id=<?php echo $product['product_id']; ?>">Edit</a>
                        <a href="admin_dashboard.php?delete_id=<?php echo $product['product_id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <?php include('footer.php'); // Include your footer ?>
</body>
</html>

<?php $conn->close(); // Close the database connection ?>
