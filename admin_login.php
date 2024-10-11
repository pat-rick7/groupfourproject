<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grocery_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle admin login form submission
if (isset($_POST['login'])) {
    $admin_username = $_POST['username'];
    $admin_password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $hashed_password = md5($admin_password); // Using md5 for hashing
    $stmt->bind_param("ss", $admin_username, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['admin'] = $admin_username;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $login_error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles.css"> <!-- Linking the CSS file -->
</head>
<body>
    <!-- Include Header and Navbar -->
    <?php include('header.php'); ?>
    <?php include('nav.php'); ?>

    <!-- Admin Login Form -->
    <div class="content">
        <h1>Admin Login</h1>
        <form method="post">
            <label>Username:</label>
            <input type="text" name="username" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <?php if (isset($login_error)) echo "<p>$login_error</p>"; ?>
    </div>

    <!-- Include Footer -->
    <?php include('footer.php'); ?>
</body>
</html>
