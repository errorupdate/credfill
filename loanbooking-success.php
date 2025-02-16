<?php
// loanbooking-success.php
session_start();

// Database connection
$servername = "localhost";
$username = "u931076421_loanbooking";
$password = "Credfill@3006";
$dbname = "u931076421_loanbooking";

// Get booking ID from URL
$booking_id = isset($_GET['booking_id']) ? $_GET['booking_id'] : '';

// Get booking details
$bookingDetails = null;
if ($booking_id) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM booking_submissions WHERE booking_id = ?");
    $stmt->bind_param("s", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookingDetails = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
}

// Redirect if booking not found
if (!$bookingDetails) {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Success - CredFill Services</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;700&display=swap" rel="stylesheet">
    
    <style>
        .success-section {
            min-height: calc(100vh - 100px); /* Adjust if you have header/footer */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .container {
            display: flex;
            justify-content: center;
            width: 100%;
        }
        .success-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
        }
        .success-icon {
            color: #28a745;
            font-size: 64px;
            margin-bottom: 20px;
        }
        .welcome-text {
            color: #333;
            font-size: 18px;
            margin: 20px 0;
            line-height: 1.5;
        }
        .booking-details {
            margin: 30px 0;
            text-align: left;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
        }
        .booking-details p {
            margin: 10px 0;
            font-size: 16px;
            color: #333;
        }
        .details-heading {
            color: #333;
            font-size: 18px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
        }
        .email-notice {
            color: #666;
            margin: 20px 0;
            font-size: 16px;
            padding: 15px;
            background: #e8f5e9;
            border-radius: 8px;
        }
        .redirect-text {
            color: #666;
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <section class="section success-section has-bg-image" aria-label="success" style="background-image: url('./assets/images/hero-bg.jpg')">
            <div class="container">
                <div class="success-card">
                    <div class="success-icon">✓</div>
                    <h1 class="h2" style="color: #28a745;">Application Submitted Successfully!</h1>
                    
                    <p class="welcome-text">Thank you for booking your loan with CredFill.</p>
                    
                    <div class="booking-details">
                        <p class="details-heading">Your Booking Details:</p>
                        <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($bookingDetails['booking_id']); ?></p>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($bookingDetails['name']); ?></p>
                        <p><strong>Service:</strong> <?php echo htmlspecialchars($bookingDetails['service']); ?></p>
                    </div>

                    <p class="email-notice">We have sent you an email with the booking details.</p>

                    <p class="redirect-text">Redirecting to homepage in <span id="countdown">20</span> seconds...</p>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>

    <script>
        // Countdown timer
        let seconds = 20; // Changed to 20 seconds
        const countdownElement = document.getElementById('countdown');
        
        const countdownInterval = setInterval(() => {
            seconds--;
            if (countdownElement) {
                countdownElement.textContent = seconds;
            }
            if (seconds <= 0) {
                clearInterval(countdownInterval);
                window.location.href = 'index.html';
            }
        }, 1000);
    </script>

    <!-- Your existing scripts -->
    <script src="./assets/js/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>