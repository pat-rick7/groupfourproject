<?php
session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grocery_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login form submission
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL statement
    $sql = "SELECT password FROM customer WHERE email = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result(); // Store result for checking if email exists

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user'] = $email;
            header("Location: index.php");
            exit();
        } else {
            $login_error = "Invalid email or password.";
        }
    } else {
        $login_error = "Invalid email or password.";
    }
    $stmt->close();
}

// Handle signup form submission
if (isset($_POST['signup'])) {
    $name = $_POST['name'];
    $email = $_POST['signup_email'];
    $phone = $_POST['phone'];
    $password = $_POST['signup_password'];

    // Check if email already exists
    $sql = "SELECT * FROM customer WHERE email = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        // Hash the password before storing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new customer
        $sql = "INSERT INTO customer (name, email, phone_number, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            die("SQL Error: " . $conn->error);
        }

        $stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);
        if ($stmt->execute()) {
            $_SESSION['user'] = $email;
            header("Location: index.php");
            exit();
        } else {
            $signup_error = "Error during signup. Please try again.";
        }
    } else {
        $signup_error = "Email already exists.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Login & Signup</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container {
            display: none;
        }
        .form-container.active {
            display: block;
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>
    <?php include('nav.php'); ?>

    <div class="content">
        <button onclick="showForm('login')">Login</button>
        <button onclick="showForm('signup')">Signup</button>

        <!-- Login Form -->
        <div id="login-form" class="form-container active">
            <h1>Customer Login</h1>
            <form method="post">
                <label>Email:</label>
                <input type="email" name="email" required>
                <label>Password:</label>
                <input type="password" name="password" required>
                <button type="submit" name="login">Login</button>
            </form>
            <?php if (isset($login_error)) echo "<p>$login_error</p>"; ?>
        </div>

        <!-- Signup Form -->
        <div id="signup-form" class="form-container">
            <h1>Customer Signup</h1>
            <form method="post">
                <label>Name:</label>
                <input type="text" name="name" required>
                <label>Email:</label>
                <input type="email" name="signup_email" required>
                <label>Phone:</label>
                <input type="text" name="phone" required>
                <label>Password:</label>
                <input type="password" name="signup_password" required>
                <button type="submit" name="signup">Sign Up</button>
            </form>
            <?php if (isset($signup_error)) echo "<p>$signup_error</p>"; ?>
        </div>
    </div>

    <?php include('footer.php'); ?>

    <script>
        function showForm(form) {
            document.getElementById('login-form').classList.remove('active');
            document.getElementById('signup-form').classList.remove('active');
            document.getElementById(form + '-form').classList.add('active');
        }
    </script>
</body>
</html>
