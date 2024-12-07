<?php
// Start session
session_start();

// Include the database connection file
include "connect.php";
include 'Includes/functions/functions.php';
include "Includes/templates/header.php";

// Check if user is logged in
if (!isset($_SESSION['client_id'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit;
}

// Retrieve user information from the database based on client_id
$stmt = $con->prepare("SELECT * FROM clients WHERE client_id = ?");
$stmt->execute([$_SESSION['client_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user exists
if (!$user) {
    // If user not found, redirect to login page
    header("Location: login.php");
    exit;
}

// Logout logic
if (isset($_POST['logout'])) {
    // Destroy the session
    session_destroy();
    
    // Redirect to index.php after logout
    header("Location: index.php");
    exit;
}

// Update user information
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form submission
    $client_name = $_POST['client_name'];
    $client_email = $_POST['client_email'];
    $client_address = $_POST['client_address'];
    $client_phone = $_POST['client_phone'];

    // Update user details in the database
    $stmt = $con->prepare("UPDATE clients SET client_name = ?, client_email = ?, client_address = ?, client_phone = ? WHERE client_id = ?");
    $stmt->execute([$client_name, $client_email, $client_address, $client_phone, $_SESSION['client_id']]);
    
    // Redirect back to profile page after updating
    header("Location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        /* Styles for profile section */
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
        }
        .container h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .container p {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .logout-btn {
            background-color: #ff7f50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .logout-btn:hover {
            background-color: #ff6347;
        }
        /* Style for form */
        .edit-form {
            margin-top: 20px;
        }
        .edit-form input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>User Profile</h2>
    <p>Full Name: <?php echo $user['client_name']; ?></p>
    <p>Email: <?php echo $user['client_email']; ?></p>
    <p>Address: <?php echo $user['client_address']; ?></p>
    <p>Phone: <?php echo $user['client_phone']; ?></p>

    <!-- Edit form -->
    <form action="" method="post" class="edit-form">
        <input type="text" name="client_name" placeholder="New Full Name">
        <input type="email" name="client_email" placeholder="New Email">
        <input type="text" name="client_address" placeholder="New Address">
        <input type="text" name="client_phone" placeholder="New Phone">
        <button type="submit">Update</button>
    </form>

    <!-- Logout button -->
    <form action="" method="post">
        <button class="logout-btn" type="submit" name="logout">Logout</button>
    </form>
</div>

</body>
</html>
