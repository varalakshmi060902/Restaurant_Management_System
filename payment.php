<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include necessary files
include "connect.php";  // Assuming this file contains your database connection
include "Includes/functions/functions.php";
include "Includes/templates/header.php";
include "Includes/templates/navbar.php";

// Check if order_id is set in the URL
if (!isset($_GET['order_id'])) {
    header("Location: error.php");
    exit(); // Stop further execution
}

// Retrieve order_id and total_price from the URL
$order_id = $_GET['order_id'];
$total_price = isset($_GET['total_price']) ? htmlspecialchars($_GET['total_price']) : '0'; // Sanitize and provide default

// Fetch the concatenated delivery address from the database
$stmtAddress = $con->prepare("SELECT CONCAT(client_address, ', ', client_city, ', ', client_zipcode) AS delivery_address FROM clients WHERE client_id = (SELECT client_id FROM in_order WHERE order_id = ? LIMIT 1)");
$stmtAddress->execute([$order_id]);
$delivery_address = $stmtAddress->fetchColumn();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay'])) {
    // Retrieve and sanitize card details and delivery option from the form
    $person_name = htmlspecialchars($_POST['person_name']);
    $card_number = htmlspecialchars($_POST['card_number']);
    $expiry = htmlspecialchars($_POST['expiry']);
    $cvv = htmlspecialchars($_POST['cvv']);
    $delivery_option = isset($_POST['delivery_option']) ? $_POST['delivery_option'] : 'delivery';

    // Insert payment details into the Payments table
    // Insert payment details into the Payments table with formatted current date and time
    $stmtPayment = $con->prepare("INSERT INTO payment (Time, Amount, Order_ID) VALUES (NOW(), ?, ?)");
    if (!$stmtPayment) {
        echo "Error in preparing SQL statement: " . $con->error;
    }
    $stmtPayment->execute([$total_price, $order_id]);

    // Insert into placed_orders table
    $stmtOrderDetails = $con->prepare("INSERT INTO placed_orders (order_id, order_time, client_id, delivery_address, delivered, canceled)
    SELECT order_id, NOW(), client_id, ?, 0, 0 FROM in_order WHERE order_id = ? LIMIT 1");
    if (!$stmtOrderDetails) {
        echo "Error in preparing SQL statement: " . $con->error;
    }
    $stmtOrderDetails->execute([$delivery_option === 'takeaway' ? 'Vincent Pizza, 1580 Boone Street, Corpus Christi, TX, 78476 - USA' : $delivery_address, $order_id]);

    // Check if insertion was successful
    if ($stmtOrderDetails->rowCount() > 0) {
        echo "Order details inserted successfully!";
    } else {
        echo "Failed to insert order details!";
    }

    // Redirect to feedback.php
    header("Location: feedback.php?order_id={$order_id}");
    exit();
}
?>


<!-- Payment Form -->
<div class="container p-0">
    <div class="card px-4">
        <p class="h8 py-3">Order Options</p>
        <form method="post" action="">
            <div>
                <label><input type="radio" name="delivery_option" value="delivery" checked="checked" onclick="showAddress('<?php echo $delivery_address; ?>')"> Delivery to the given Address</label><br>
                <label><input type="radio" name="delivery_option" value="takeaway" onclick="showAddress('Vincent Pizza')"> Take-away from restaurant</label>
            </div>
            <div id="addressContainer" style="display: none;">
                <p id="address"></p>
            </div>
            <p class="h8 py-3">Payment Details</p>
            <div class="text-center mb-3">
                <strong>Total Price: $<?php echo $total_price; ?></strong>
            </div>
       
            <div class="row gx-3">
                <div class="col-12">
                    <div class="d-flex flex-column">
                        <p class="text mb-1">Name on the card</p>
                        <input class="form-control mb-3" type="text" name="person_name" placeholder="Name">
                    </div>
                </div>
                <div class="col-12">
                    <div class="d-flex flex-column">
                        <p class="text mb-1">Card Number</p>
                        <input class="form-control mb-3" type="text" name="card_number" placeholder="1234 5678 4356 7890">
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column">
                        <p class="text mb-1">Expiry</p>
                        <input class="form-control mb-3" type="text" name="expiry" placeholder="MM/YYYY">
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column">
                        <p class="text mb-1">CVV/CVC</p>
                        <input class="form-control mb-3 pt-2 " type="password" name="cvv" placeholder="***">
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" name="pay" class="btn btn-primary mb-3">
                        <span class="ps-3">Pay $<?php echo $total_price; ?></span>
                        <span class="fas fa-arrow-right"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

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


<script>
    function showAddress(address) {
        var addressContainer = document.getElementById('addressContainer');
        var addressParagraph = document.getElementById('address');
        
        addressParagraph.textContent = address;
        addressContainer.style.display = 'block';
    }
</script>
