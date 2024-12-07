<?php
    // Set page title
    $pageTitle = 'Login';

    include "connect.php";
    include 'Includes/functions/functions.php';
    include "Includes/templates/header.php";
    include "Includes/templates/navbar.php";
?>

<style type="text/css">
    /* Styles for login section */
    .login-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 150px); /* Subtracting header and footer heights */
    }
    .login-section h2 {
        margin-bottom: 20px;
    }
    .login-option {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .login-option form {
        margin-bottom: 20px;
    }
    .login-option form input {
        margin-bottom: 10px;
        display: block; /* Display inputs as block elements */
        width: 300px; /* Set width */
        height: 40px; /* Set height */
        padding: 10px; /* Add padding */
        border-radius: 5px; /* Add border-radius */
        border: 1px solid #ccc; /* Add border */
    }
    .login-option form button {
        background-color: #ffc851;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        margin-bottom: 10px;
        transition: background-color 0.3s ease;
    }
    .login-option form button:hover {
        background-color: #ffa600; /* darker shade of yellow */
    }
    .login-buttons {
        display: flex;
        justify-content: center;
        margin-top: 10px;
    }
    .login-buttons a {
        background-color: #ffc851; /* Yellow */
        border: none;
        color: white;
        padding: 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 0 5px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }
    .login-buttons a:hover {
        background-color: #ffa600; /* darker shade of yellow */
    }
</style>

<section class="login-section">
    <h2>Login</h2>
    <div class="login-option">
        <form action="user-login.php" method="post">
            <input type="text" name="user_email" placeholder="Customer Email" required>
            <input type="password" name="user_password" placeholder="Password" required>
            <button type="submit">Customer Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
    <div class="login-buttons">
        <a href="staff/index.php">Staff Login</a>
        <a href="manager/index.php">Manager Login</a>
        <a href="admin/index.php">Admin Login</a>
    </div>
</section>

<?php include "Includes/templates/footer.php"; ?>
