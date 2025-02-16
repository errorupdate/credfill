<?php
session_start();
$booking_id = isset($_GET['booking_id']) ? $_GET['booking_id'] : '';

// Database connection settings
$servername = "localhost";
$username = "u931076421_booking_submis";
$password = "Credfill@3006";
$dbname = "u931076421_booking_submis";

// Get booking details
$bookingDetails = null;
if ($booking_id) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM booking_submissions WHERE booking_id = ? AND payment_status = 'completed'");
    $stmt->bind_param("s", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookingDetails = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
}

// Redirect if booking not found or payment not completed
if (!$bookingDetails) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - CredFill Services</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="preload" as="image" href="./assets/images/hero-bg.jpg">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <style>
        .success-section {
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 120px 0 60px;
        }
        .success-message {
            text-align: center;
            background: rgba(0, 0, 0, 0.6);
            padding: 40px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }
        .redirect-timer {
            margin-top: 20px;
            font-size: 0.9em;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <header class="header" data-header>
        <div class="container">
            <a href="index.html" class="logo">
                <img src="./assets/images/logo-light.png" width="140" height="60" alt="CredFill home" class="logo-light">
                <img src="./assets/images/logo-dark.svg" width="140" height="60" alt="CredFill home" class="logo-dark">
            </a>

            <nav class="navbar" data-navbar>
                <div class="navbar-top">
                    <a href="index.html" class="logo">
                        <img src="./assets/images/logo-light.png" width="140" height="60" alt="CredFill home">
                    </a>
                    <button class="nav-close-btn" aria-label="close menu" data-nav-toggler>
                        <ion-icon name="close-outline" aria-hidden="true"></ion-icon>
                    </button>
                </div>

                <ul class="navbar-list">
                    <li><a href="index.html" class="navbar-link">Home</a></li>
                    <li><a href="bdservices.html" class="navbar-link">Compliance</a></li>
                    <li><a href="credit.html" class="navbar-link">Loans</a></li>
                    <li><a href="https://credfill.in/" class="navbar-link">Tax Refunds</a></li>
                    <li><a href="blogs.html" class="navbar-link">Blogs</a></li>
                    <li><a href="contact.html" class="navbar-link">Contact Us</a></li>
                </ul>
            </nav>

            <a href="tel:+91 9472194303" class="btn btn-primary">Talk to Us!</a>

            <button class="nav-open-btn" aria-label="open menu" data-nav-toggler>
                <ion-icon name="menu-outline" aria-hidden="true"></ion-icon>
            </button>

            <div class="overlay" data-nav-toggler data-overlay></div>
        </div>
    </header>

    <main>
        <section class="section success-section has-bg-image" aria-label="success" style="background-image: url('./assets/images/hero-bg.jpg')">
            <div class="container">
                <div class="success-message">
                    <h2>Payment Successful!</h2>
                    <div class="success-details">
                        <p>Thank you for your payment! Your booking has been successfully confirmed.</p>
                        <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($booking_id); ?></p>
                        <p><strong>Service:</strong> <?php echo htmlspecialchars($bookingDetails['service']); ?></p>
                        <p><strong>Amount Paid:</strong> ₹<?php echo htmlspecialchars($bookingDetails['cost']); ?></p>
                        <p>A confirmation email has been sent to your registered email address. Our team will be in touch with you shortly to assist further.</p>
                    </div>
                    
                    <div class="redirect-timer">
                        Redirecting to home page in <span id="timer">20</span> seconds...
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        let timeLeft = 20;
        const timerElement = document.getElementById('timer');
        
        const countdown = setInterval(() => {
            timeLeft--;
            timerElement.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(countdown);
                window.location.href = 'index.html';
            }
        }, 1000);
    </script>
</body>
</html>