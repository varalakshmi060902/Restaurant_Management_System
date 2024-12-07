<?php
    //Set page title
    $pageTitle = 'Sign Up';

    include "connect.php";
    include 'Includes/functions/functions.php';
    include "Includes/templates/header.php";
    include "Includes/templates/navbar.php";
?>

<style type="text/css">
    /* Styles for signup section */
    .signup-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 150px); /* Subtracting header and footer heights */
    }
    .signup-section h2 {
        margin-bottom: 20px;
    }
    .signup-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%; /* Use 100% width for better control in alignment */
        max-width: 400px; /* Limiting the width to make the form more readable */
    }
    .signup-option form {
        display: flex;
        flex-direction: column;
        width: 100%;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Optional: adds shadow for better visibility */
        border-radius: 5px; /* Optional: rounds the corners */
        background-color: #fff; /* Optional: ensures background is white */
    }
    .signup-option form input,
    .signup-option form button {
        width: 100%; /* Ensure inputs take full width of the form */
        padding: 8px;
        margin-bottom: 15px; /* Increase margin-bottom for better spacing */
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    .signup-option form button {
        background-color: #ffc851;
        color: white;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease; /* Smooth transition for hover effect */
    }
    .signup-option form button:hover {
        background-color: #e0b042; /* Slightly darker on hover */
    }
</style>


<section class="signup-section">
    <h2>Sign Up</h2>
    <div class="signup-option">
        <form action="customer-signup.php" method="post">
            <input type="text" name="customer_name" placeholder="Customer Name" required>
            <input type="email" name="customer_email" placeholder="Email" required>
            <input type="password" name="customer_password" placeholder="Password" required>
            <input type="text" name="customer_address" placeholder="Address" required>
            <input type="text" name="customer_city" placeholder="City" required>
            <input type="text" name="customer_zipcode" placeholder="zip code" required>
            <input type="number" name="customer_phone" placeholder="Phone Number" required>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</section>

<?php include "Includes/templates/footer.php"; ?>
