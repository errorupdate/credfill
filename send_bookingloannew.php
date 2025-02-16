<?php
$servername = "localhost";
$username = "u931076421_loanbooking";
$password = "Credfill@3006";
$dbname = "u931076421_loanbooking";

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $service = $_POST['service'];
    $cost = $_POST['cost'];
    $pan = $_POST['pan']; 
    $address = $_POST['address']; 
    $pincode = $_POST['pincode'];
    $district = $_POST['district'];
    $state = $_POST['state'];
    
    // Generate booking ID
    $year = '25';
    $month = date('m');
    $day = date('d');
    $booking_id = 'CFL' . $year . $month . $day . rand(100, 999);

    // Connect to database
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        $response = array('status' => 'error', 'message' => 'Connection failed');
        echo json_encode($response);
        exit();
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO booking_submissions (booking_id, name, email, phone, service, cost, pan, address, pincode, district, state, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("sssssssssss", $booking_id, $name, $email, $phone, $service, $cost, $pan, $address, $pincode, $district, $state);
    
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
        $message .= "Pan: " . $pan . "\n";
        $message .= "Address: " . $address . "\n";
        $message .= "Pincode: " . $pincode . "\n";
        $message .= "District: " . $district . "\n";
        $message .= "State: " . $state . "\n";
        
        $headers = "From: booking@credfill.com\r\n";
        $headers .= "Reply-To: " . $email;
        
        mail($to, $subject, $message, $headers);
        
        // Send email to customer with improved styling
        $to_customer = $email;
        $subject_customer = "Booking Confirmation - " . $booking_id;
        $message_customer = "
<html>
<head>
    <title>Booking Confirmation - $booking_id</title>
    <style>
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        .logo {
            text-align: left;
            margin-bottom: 30px;
        }
        .logo img {
            max-width: 200px;
            height: auto;
        }
        .whatsapp-btn {
    background-color: #25D366;
    color: white !important;
    padding: 12px 24px;
    text-decoration: none;
    border-radius: 22px;
    font-weight: bold;
    display: inline-block;
    margin-top: 20px;
    margin-left: 20px; 
}

.tracking-btn {
    background-color: #616ccc;
    color: white !important;
    padding: 12px 24px;
    text-decoration: none;
    border-radius: 22px;
    font-weight: bold;
    display: inline-block;
    margin-top: 20px;
    margin-left: 10px;
}
</style>
</head>
<body>
    <div class='container'>
        <div class='logo'>
        <a href='https://credfill.com'>
            <img src='../assets/images/logo-light.png' alt='CredFill Logo'>
            </a>
        </div>
        <p>Dear $name,</p>
        <p>Thank you for booking your loan with CredFill. We have successfully received your booking and look forward to serving you at the earliest. Please expect a call from us shortly.</p>
        <p>For your reference, here are your booking details:</p>
        <ul>
            <li>Booking ID: $booking_id</li>
            <li>Name: $name</li>
            <li>Mobile: $phone</li>
            <li>PAN: $pan</li>
            <li>Service Name: $service</li>
            <li>Cost: ₹$cost</li>
        </ul>
        <p>We will start processing your request immediately. You can use your Booking ID to track the status of your application and are requested to mention it for any future correspondence.</p>
        <p>Happy Financing!</p>
        <p>Best Regards,<br>Team CredFill</p>
        <a href='https://wa.me/919472194303' class='whatsapp-btn'>
            Chat with us
        </a>
        <a href='https://credfill.com/tracking' class='tracking-btn'>
            Track Your Booking
        </a>
    </div>
</body>
</html>
";

        $headers_customer = "From: booking@credfill.com\r\n";
        $headers_customer .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        mail($to_customer, $subject_customer, $message_customer, $headers_customer);
        
        // Redirect to success page with details
        $response = array(
            'status' => 'success',
            'message' => 'Booking submitted successfully',
            'redirect_url' => "loanbooking-success.php?booking_id=" . urlencode($booking_id)
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