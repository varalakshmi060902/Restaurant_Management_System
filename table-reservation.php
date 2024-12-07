<!-- PHP INCLUDES -->
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
?>
    
<style type="text/css">
    .table_reservation_section {
        max-width: 850px;
        margin: 50px auto;
        min-height: 500px;
    }

    .check_availability_submit {
        background: #ffc851;
        color: white;
        border-color: #ffc851;
        font-family: work sans,sans-serif;
    }
    .client_details_tab  .form-control {
        background-color: #fff;
        border-radius: 0;
        padding: 25px 10px;
        box-shadow: none;
        border: 2px solid #eee;
    }

    .client_details_tab  .form-control:focus {
        border-color: #ffc851;
        box-shadow: none;
        outline: none;
    }
    .text_header {
        margin-bottom: 5px;
        font-size: 18px;
        font-weight: bold;
        line-height: 1.5;
        margin-top: 22px;
        text-transform: capitalize;
    }
    .layer {
        height: 100%;
        background: -moz-linear-gradient(top, rgba(45,45,45,0.4) 0%, rgba(45,45,45,0.9) 100%);
        background: -webkit-linear-gradient(top, rgba(45,45,45,0.4) 0%, rgba(45,45,45,0.9) 100%);
        background: linear-gradient(to bottom, rgba(45,45,45,0.4) 0%, rgba(45,45,45,0.9) 100%);
    }
</style>

<!-- START ORDER FOOD SECTION -->
<section style="background: url(Design/images/food_pic.jpg); background-position: center bottom; background-repeat: no-repeat; background-size: cover;">
    <div class="layer">
        <div style="text-align: center;padding: 15px;">
            <h1 style="font-size: 120px; color: white;font-family: 'Roboto'; font-weight: 100;">Book a Table</h1>
        </div>
    </div>
</section>

<section class="table_reservation_section">
    <div class="container">
        <?php
        if(isset($_POST['submit_table_reservation_form']) && $_SERVER['REQUEST_METHOD'] === 'POST')
        {
            // Selected Date and Time
            $selected_date = $_POST['selected_date'];
            $selected_time = $_POST['selected_time'];
            $desired_date = $selected_date." ".$selected_time;

            // Number of Guests
            $number_of_guests = $_POST['number_of_guests'];

            // Table ID
            $table_id = $_POST['table_id'];

            // Use client details from the session
            $client_id = $_SESSION['client_id'];
            $client_full_name = $user['client_name'];
            $client_phone_number = $user['client_phone'];
            $client_email = $user['client_email'];

            // Database transaction
            $con->beginTransaction();
            try {
                $stmt_reservation = $con->prepare("INSERT INTO reservations(date_created, client_id, selected_time, nbr_guests, table_id) VALUES (?, ?, ?, ?, ?)");
                $stmt_reservation->execute([Date("Y-m-d H:i"), $client_id, $desired_date, $number_of_guests, $table_id]);

                echo "<div class='alert alert-success'>Great! Your Reservation has been created successfully.</div>";

                $con->commit();
            } catch (Exception $e) {
                $con->rollBack();
                echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
            }
        }
        ?>

        <div class="text_header">
            <span>1. Select Date & Time</span>
        </div>
        <form method="POST" action="table-reservation.php">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="reservation_date">Date</label>
                        <input type="date" min="<?php echo date('Y-m-d',strtotime('+1 day')) ?>" name="selected_date" class="form-control" required="required">
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="reservation_time">Time</label>
                        <input type="time" name="selected_time" class="form-control" required="required">
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="number_of_guests">Number of Guests</label>
                        <input type="number" name="number_of_guests" class="form-control" required="required" min="1" max="20">
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="table_id">Table Number</label>
                        <select name="table_id" class="form-control" required="required">
                            <!-- Assuming the available table numbers are 1 to 10 -->
                            <?php for($i = 1; $i <= 10; $i++): ?>
                                <option value="<?php echo $i; ?>">Table <?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Here you can add hidden fields for client details if needed -->
            <input type="hidden" name="client_email" value="<?php echo htmlspecialchars($user['client_email']); ?>">
            <input type="hidden" name="client_phone_number" value="<?php echo htmlspecialchars($user['client_phone']); ?>">

            <div class="form-group">
                <input type="submit" name="submit_table_reservation_form" class="btn btn-info" value="Make a Reservation">
            </div>
        </form>
    </div>
</section>

<!-- PHP INCLUDES -->
<?php include "Includes/templates/footer.php"; ?>
