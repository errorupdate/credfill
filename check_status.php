<?php
// check_status.php
header('Content-Type: application/json');

// Database connection
function connectDB() {
    $servername = "localhost";
    $username = "u931076421_booking_submis";
    $password = "Credfill@3006";
    $dbname = "u931076421_booking_submis";

    try {
        $db_connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db_connection;
    } catch(PDOException $e) {
        return false;
    }
}

// Get POST data
$booking_id = $_POST['booking_id'] ?? '';
$phone = $_POST['phone'] ?? '';

if (empty($booking_id) || empty($phone)) {
    echo json_encode(['error' => 'Please provide both booking ID and phone number']);
    exit;
}

$db_connection = connectDB();
if (!$db_connection) {
    echo json_encode(['error' => 'Unable to connect to database']);
    exit;
}

try {
    $stmt = $db_connection->prepare("SELECT payment_status FROM booking_submissions WHERE booking_id = ? AND phone = ?");
    $stmt->execute([$booking_id, $phone]);
    
    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $status = strtolower($result['payment_status']);
        
        switch($status) {
            case 'pending':
                echo json_encode([
                    'message' => 'Payment is pending. Please make the payment',
                    'class' => 'status-pending'
                ]);
                break;
            case 'completed':
                echo json_encode([
                    'message' => 'Processing',
                    'class' => 'status-processing'
                ]);
                break;
            default:
                echo json_encode([
                    'message' => $result['payment_status'],
                    'class' => 'status-other'
                ]);
        }
    } else {
        echo json_encode(['error' => 'No booking found with the provided details']);
    }
} catch(PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    echo json_encode(['error' => 'An error occurred while checking the status']);
}