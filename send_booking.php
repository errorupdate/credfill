<?php
$servername = "localhost";
$username = "u931076421_booking_submis";
$password = "Credfill@3006";
$dbname = "u931076421_booking_submis";

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $service = $_POST['service'];
    $cost = $_POST['cost'];
    
    // Generate booking ID
    // Get the current year, month, and day
$year = '025';  // Fixed year part
$month = date('m');  // Get the current month (01 to 12)
$day = date('d');  // Get the current day (01 to 31)

// Generate booking ID with CF prefix, year (025), month (MM), day (DD), and 3 random digits
$booking_id = 'CF' . $year . $month . $day . rand(100, 999);

    // Connect to database
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        $response = array('status' => 'error', 'message' => 'Connection failed');
        echo json_encode($response);
        exit();
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO booking_submissions (booking_id, name, email, phone, service, cost, payment_status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("sssssd", $booking_id, $name, $email, $phone, $service, $cost);
    
    if ($stmt->execute()) {
        // Send email to company
        $to = "infocredfill@gmail.com";
        $subject = "New Booking - " . $booking_id;
        $message = "New booking details:\n\n";
        $message .= "Booking ID: " . $booking_id . "\n";
        $message .= "Name: " . $name . "\n";
        $message .= "Email: " . $email . "\n";
        $message .= "Phone: " . $phone . "\n";
        $message .= "Service: " . $service . "\n";
        $message .= "Cost: ₹" . $cost . "\n";
        
        $headers = "From: booking@credfill.com\r\n";
        $headers .= "Reply-To: " . $email;
        
        mail($to, $subject, $message, $headers);

        $response = array(
            'status' => 'success',
            'message' => 'Booking submitted successfully',
            'redirect_url' => "payment.php?booking_id=" . urlencode($booking_id) . "&amount=" . urlencode($cost)
        );
    } else {
        $response = array('status' => 'error', 'message' => 'Error submitting booking');
    }

    $stmt->close();
    $conn->close();
} else {
    $response = array('status' => 'error', 'message' => 'Invalid request');
}

header('Content-Type: application/json');
echo json_encode($response);
?>