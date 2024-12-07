<!-- PHP INCLUDES -->

<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Start session
    session_start();

    // Include necessary files
    include "connect.php"; // Assuming this file contains your database connection
    include "Includes/functions/functions.php";
    include "Includes/templates/header.php";
    include "Includes/templates/navbar.php";

    // Check if order_id is set
    if (!isset($_GET['order_id'])) {
        // If order_id is not provided, redirect to the order page with an error message
        header("Location: order_food.php?error=no_order_id_provided");
        exit(); // Stop further execution
    }

    // Retrieve order details from the database based on the order_id
    $order_id = $_GET['order_id']; 
    // Perform database query to retrieve order details
    // Replace placeholders with actual database queries
    $stmt = $con->prepare("SELECT in_order.*, menus.menu_name, menus.menu_price FROM in_order INNER JOIN menus ON in_order.menu_id = menus.menu_id WHERE order_id = ?");
    $stmt->execute([$order_id]);
    $order_details = $stmt->fetchAll();

    // Calculate total price
    $total_price = 0;
    foreach ($order_details as $item) {
        $total_price += $item['menu_price'] * $item['quantity'];
    }

?>

<!-- CART PAGE STYLE -->
<style type="text/css">
    body {
        background: #f7f7f7;
    }

    .text_header {
        margin-bottom: 20px; /* Increase the margin to add space */
        font-size: 24px; /* Increase the font size */
        font-weight: bold;
        line-height: 1.5;
        margin-top: 22px;
        text-transform: capitalize;
    }

    .items_tab {
        border-radius: 4px;
        background-color: white;
        overflow: hidden;
        box-shadow: 0 0 5px 0 rgba(60, 66, 87, 0.04), 0 0 10px 0 rgba(0, 0, 0, 0.04);
    }

    .itemListElement {
        font-size: 14px;
        line-height: 1.29;
        border-bottom: solid 1px #e5e5e5;
        cursor: pointer;
        padding: 16px 12px 18px 12px;
    }

    .item_details {
        width: auto;
        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        -webkit-box-orient: horizontal;
        -webkit-box-direction: normal;
        -webkit-flex-direction: row;
        -webkit-box-pack: justify;
        -webkit-justify-content: space-between;
        -webkit-box-align: center;
        -webkit-align-items: center;
    }

    .item_label {
        color: #9e8a78;
        border-color: #9e8a78;
        background: white;
        font-size: 12px;
        font-weight: 700;
    }

    .btn-secondary:not(:disabled):not(.disabled).active,
    .btn-secondary:not(:disabled):not(.disabled):active {
        color: #fff;
        background-color: #9e8a78;
        border-color: #9e8a78;
    }

    .item_select_part {
        display: flex;
        -webkit-box-pack: justify;
        justify-content: space-between;
        -webkit-box-align: center;
        align-items: center;
        flex-shrink: 0;
    }

    .select_item_bttn {
        width: 55px;
        display: flex;
        margin-left: 30px;
        -webkit-box-pack: end;
        justify-content: flex-end;
    }

    .menu_price_field {
        width: auto;
        display: flex;
        margin-left: 30px;
        -webkit-box-align: baseline;
        align-items: baseline;
    }

    .order_food_section {
        max-width: 720px;
        margin: 50px auto;
        padding: 0px 15px;
    }

    .item_label.focus,
    .item_label:focus {
        outline: none;
        background: initial;
        box-shadow: none;
        color: #9e8a78;
        border-color: #9e8a78;
    }

    .item_label:hover {
        color: #fff;
        background-color: #9e8a78;
        border-color: #9e8a78;
    }

    /* Make circles that indicate the steps of the form: */
    .step {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbbbbb;
        border: none;
        border-radius: 50%;
        display: inline-block;
        opacity: 0.5;
    }

    .step.active {
        opacity: 1;
    }

    /* Mark the steps that are finished and valid: */
    .step.finish {
        background-color: #4CAF50;
    }

    .order_food_tab {
        display: none;
    }

    .next_prev_buttons {
        background-color: #4CAF50;
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        font-size: 17px;
        cursor: pointer;
    }

    .client_details_tab .form-control {
        background-color: #fff;
        border-radius: 0;
        padding: 25px 10px;
        box-shadow: none;
        border: 2px solid #eee;
    }

    .client_details_tab .form-control:focus {
        border-color: #ffc851;
        box-shadow: none;
        outline: none;
    }
</style>

<!-- START CART SECTION -->
<section class="cart_section">
    <div class="container">
        <div class="row justify-content-center"> <!-- Centering the content -->
            <div class="col-lg-8"> <!-- Adjusting the column width -->
                <div class="card px-4 py-3"> <!-- Adding padding to the card -->
                    <h2 class="text_header">Your Cart</h2>
                    <!-- Display order items here -->
                    <?php foreach ($order_details as $item): ?>
                        <div class="cart_item">
                            <div class="item_details">
                                <h3><?php echo $item['menu_name']; ?></h3>
                                <p>Price: <?php echo $item['menu_price']; ?>$</p>
                                <p>Quantity: <?php echo $item['quantity']; ?></p>
                                <!-- Modify button that redirects to order_food.php -->
                                <a href="order_food.php" class="btn btn-primary">Modify</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <!-- Total price column -->
                    <div class="total_price d-flex justify-content-between align-items-center">
                        <h3>Total Price: <?php echo $total_price; ?>$</h3>
                        <!-- Submit button -->
                        <a href="payment.php?order_id=<?php echo $order_id; ?>&total_price=<?php echo $total_price; ?>" class="btn btn-success">Proceed to Payment</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


	<!-- WIDGET SECTION / FOOTER -->

    <section class="widget_section" style="background-color: #222227;padding: 100px 0;">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="footer_widget">
                        <img src="Design/images/restaurant-logo.png" alt="Restaurant Logo" style="width: 150px;margin-bottom: 20px;">
                        <p>
                            Our Restaurnt is one of the bests, provide tasty Menus and Dishes. You can reserve a table or Order food.
                        </p>
                        <ul class="widget_social">
                            <li><a href="#" data-toggle="tooltip" title="Facebook"><i class="fab fa-facebook-f fa-2x"></i></a></li>
                            <li><a href="#" data-toggle="tooltip" title="Twitter"><i class="fab fa-twitter fa-2x"></i></a></li>
                            <li><a href="#" data-toggle="tooltip" title="Instagram"><i class="fab fa-instagram fa-2x"></i></a></li>
                            <li><a href="#" data-toggle="tooltip" title="LinkedIn"><i class="fab fa-linkedin fa-2x"></i></a></li>
                            <li><a href="#" data-toggle="tooltip" title="Google+"><i class="fab fa-google-plus-g fa-2x"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                     <div class="footer_widget">
                        <h3>Headquarters</h3>
                        <p>
                            962 Fifth Avenue, 3rd Floor New York, NY10022
                        </p>
                        <p>
                            contact@restaurant.com
                            <br>
                            (+123) 456 789 101    
                        </p>
                     </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer_widget">
                        <h3>
                            Opening Hours
                        </h3>
                        <ul class="opening_time">
                            <li>Monday - Friday 11:30am - 2:008pm</li>
                            <li>Monday - Friday 11:30am - 2:008pm</li>
                            <li>Monday - Friday 11:30am - 2:008pm</li>
                            <li>Monday - Friday 11:30am - 2:008pm</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer_widget">
                        <h3>Subscribe to our contents</h3>
                        <div class="subscribe_form">
                            <form action="#" class="subscribe_form" novalidate="true">
                                <input type="email" name="EMAIL" id="subs-email" class="form_input" placeholder="Email Address...">
                                <button type="submit" class="submit">SUBSCRIBE</button>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- FOOTER SECTION -->
<?php include "Includes/templates/footer.php"; ?>
