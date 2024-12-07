<?php
// Include necessary files
include "connect.php"; // Assuming this file contains your database connection
include "Includes/functions/functions.php";
include "Includes/templates/header.php";
include "Includes/templates/navbar.php";

// Check if order_id is set in the URL
if (!isset($_GET['order_id'])) {
    header("Location: index.php"); // Redirect to home page if order_id is missing
    exit(); // Stop further execution
}

$order_id = $_GET['order_id'];

// Fetch order details along with the menu prices
$stmtOrder = $con->prepare("SELECT in_order.menu_id, in_order.quantity, menus.menu_name, menus.menu_price FROM in_order JOIN menus ON in_order.menu_id = menus.menu_id WHERE in_order.order_id = ?");
$stmtOrder->execute([$order_id]);
$order_details = $stmtOrder->fetchAll();

// Calculate total price
$total_price = 0;
foreach ($order_details as $item) {
    $total_price += $item['menu_price'] * $item['quantity'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $menu_ids = $_POST['menu_id'];
    $ratings = $_POST['rating'];
    $comments = $_POST['comment'];

    // Fetch client_id associated with the order_id
    $stmt_client_id = $con->prepare("SELECT client_id FROM placed_orders WHERE order_id = ?");
    $stmt_client_id->execute([$order_id]);
    $client_id_row = $stmt_client_id->fetch(PDO::FETCH_ASSOC);

    $client_id = $client_id_row['client_id'];

    try {
        $con->beginTransaction();
        for ($i = 0; $i < count($menu_ids); $i++) {
            $stmt = $con->prepare("INSERT INTO feedback (menu_id, client_id, Feedback, Comments) VALUES (?, ?, ?, ?)");
            $stmt->execute([$menu_ids[$i], $client_id, $ratings[$i], $comments[$i]]);
        }
        $con->commit();
        header("Location: index.php"); // Redirect to the login page
        exit();
    } catch (Exception $e) {
        $con->rollBack();
        echo "<div>Error submitting feedback: " . $e->getMessage() . "</div>";
    }
}
?>
<style type = text/css>

.feedback-container {
    margin: 50px auto;
    max-width: 800px;
    padding: 30px;
    border-radius: 10px;
    background-color: #f4f4f4;
    font-family: Arial, sans-serif;
}

.feedback-title {
    font-size: 24px;
    margin-bottom: 20px;
}

.feedback-message {
    font-size: 18px;
    margin-bottom: 20px;
}

.feedback-fields label {
    font-size: 16px;
}

.feedback-fields input[type="number"],
.feedback-fields input[type="text"] {
    font-size: 16px;
    width: 100px;
    margin-right: 10px;
}

.feedback-buttons {
    margin-top: 20px;
}

.feedback-buttons button,
.feedback-buttons a {
    font-size: 16px;
    padding: 10px 20px;
}

.feedback-total {
    font-size: 18px;
    margin-top: 20px;
    font-weight: bold;
}


</style>

<div class="container feedback-container">
    <div class="feedback-content">
        <h2 class="feedback-title">Thank You for Your Order!</h2>
        <p class="feedback-message">Your order has been successfully placed. Below are the details:</p>
        <p class="feedback-total">Total Price: <?php echo $total_price; ?>$</p>
        <form action="feedback.php?order_id=<?php echo $order_id; ?>" method="post">
            <ul>
                <?php foreach ($order_details as $item): ?>
                <li>
                    <?php echo htmlspecialchars($item['menu_name']); ?> - Quantity: <?php echo $item['quantity']; ?> - Unit Price: <?php echo $item['menu_price']; ?>$
                    <input type="hidden" name="menu_id[]" value="<?php echo $item['menu_id']; ?>">
                    <div class="feedback-fields">
                        <label for="rating">Rating (1-5):</label>
                        <input type="number" name="rating[]" min="1" max="5" required>
                        <label for="comment">Comment:</label>
                        <input type="text" name="comment[]">
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
            <div class="feedback-buttons">
                <button type="submit" class="btn btn-primary">Submit Feedback</button>
                <a href="index.php" class="btn btn-secondary">Skip Feedback</a>
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

