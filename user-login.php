<?php
// Start session
session_start();

// Include the database connection file
include "connect.php";
include 'Includes/functions/functions.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve email and password from the form
    $useremail = $_POST['user_email'];
    $password = $_POST['user_password'];
    $hashedpass = sha1($password);

    // Prepare and execute SQL query to check if email and password match
    $stmt = $con->prepare("SELECT * FROM clients WHERE client_email = ? AND client_password = ?");
    $stmt->execute([$useremail, $hashedpass]);

    // Fetch the result
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if a user with the provided credentials exists
    if ($user) {
        // User authenticated, set session variables
        $_SESSION['client_id'] = $user['client_id'];
        
        // Redirect to order_food.php
        header("Location: order_food.php");
        exit; // Stop further execution
    } else {
        // User not found or invalid credentials, redirect back to login page with error message
        header("Location: login.php?error=1");
        exit;
    }
}
?>
