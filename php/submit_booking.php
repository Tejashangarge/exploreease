<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = trim($_POST['name']);
    $mobile = trim($_POST['mobile']);
    $address = trim($_POST['address']);
    $email = trim($_POST['email']);
    $journey_date = $_POST['journey_date'];
    $from_location = trim($_POST['from_location']);
    $to_location = trim($_POST['to_location']);
    $travel_mode = trim($_POST['travel_mode']);
    $coach_type = isset($_POST['coach_type']) ? trim($_POST['coach_type']) : '';

    // Connect to database
    $conn = new mysqli("localhost", "root", "", "travel_booking");
    
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Insert booking with error handling
    $sql = "INSERT INTO passengers (name, mobile, address, email, journey_date, from_location, to_location, travel_mode, coach_type, seat_number) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NULL)";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("sssssssss", $name, $mobile, $address, $email, $journey_date, $from_location, $to_location, $travel_mode, $coach_type);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: booking_success.php");
        exit;
    } else {
        echo "Insert failed: " . $stmt->error;
        $stmt->close();
        $conn->close();
        exit;
    }
} else {
    // If not POST, redirect to form
    header("Location: passenger_form.php");
    exit;
}
?>
