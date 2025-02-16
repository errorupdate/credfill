<?php
session_start();

// Database connection settings
$servername = "localhost";
$username = "u931076421_booking_submis";
$password = "Credfill@3006";
$dbname = "u931076421_booking_submis";

// Get booking details from URL
$booking_id = isset($_GET['booking_id']) ? $_GET['booking_id'] : '';
$amount = isset($_GET['amount']) ? $_GET['amount'] : 0;

// Verify booking exists and get details
$bookingDetails = null;
if ($booking_id) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM booking_submissions WHERE booking_id = ? AND payment_status = 'pending'");
    $stmt->bind_param("s", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookingDetails = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
}

// Redirect if booking not found or already paid
if (!$bookingDetails) {
    header("Location: index.html");
    exit();
}

// Function to send customer confirmation email
function sendCustomerConfirmation($email, $name, $booking_id, $service, $amount) {
    $to = $email;
    $subject = "Payment Confirmation - CredFill Booking #" . $booking_id;
    
    // Company website URL
    $website_url = "https://credfill.com";
    
    // WhatsApp chat link 
    $whatsapp_number = "919472194303"; 
    $whatsapp_url = "https://wa.me/" . $whatsapp_number;
    
    // HTML Message
    $message = '
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .logo { max-width: 200px; height: auto; margin-bottom: 20px; }
            .button {
                display: inline-block;
                padding: 10px 20px;
                margin: 10px 0;
                border-radius: 5px;
                text-decoration: none;
                font-weight: bold;
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
    margin-top: 1px;
    margin-left: 10px;
}
            .footer { margin-top: 20px; color: #666; font-size: 12px; }
            /* Mobile-specific styles */
            @media only screen and (max-width: 480px) {
                .logo-container {
                    max-width: 120px; /* Slightly smaller on mobile */
                    margin: 0 auto 20px auto;
                }
                .logo {
                    max-width: 120px;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <a href="' . $website_url . '">
                <img src="' . $website_url . '/assets/images/logo-dark.svg" alt="CredFill Logo" class="logo">
            </a>
            
            <p>Dear ' . htmlspecialchars($name) . ',</p>
            
            <p>Thank you for your payment at CredFill. We have successfully received your booking and look forward to serving you at the earliest. Please expect a call from us shortly if needed.</p>
            
            <p><strong>Booking Details:</strong><br>
            Booking ID: ' . htmlspecialchars($booking_id) . '<br>
            Service: ' . htmlspecialchars($service) . '<br>
            Amount Paid: ₹' . htmlspecialchars($amount) . '</p>
            
            <p>We will start processing your request immediately. You can use your Booking ID to track the status of your application and are requested to mention it for any future correspondence.</p>
            <div class="footer">
                <p>Best Regards,<br>Team CredFill</p>
                <p>
                <a href="' . $whatsapp_url . '" class="button whatsapp-btn">
                    Chat with us!
                </a>
            </p>
            <p>
                <a href="https://credfill.com/tracking" class="button tracking-btn">
                    Track Your Booking
                </a>
                </p>
            </div>
        </div>
    </body>
    </html>';
    
    // Headers for HTML email
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: booking@credfill.com\r\n";
    $headers .= "Reply-To: booking@credfill.com\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    return mail($to, $subject, $message, $headers);
}

// Handle payment confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_status'])) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($_POST['payment_status'] === 'success') {
        $stmt = $conn->prepare("UPDATE booking_submissions SET payment_status = 'completed' WHERE booking_id = ?");
        $stmt->bind_param("s", $booking_id);
        $stmt->execute();
        
        // Send confirmation email to customer
        sendCustomerConfirmation(
            $bookingDetails['email'],
            $bookingDetails['name'],
            $booking_id,
            $bookingDetails['service'],
            $bookingDetails['cost']
        );
        
        // Redirect to success page
        header("Location: payment-success.php?booking_id=" . urlencode($booking_id));
        exit();
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - CredFill Services</title>

    <!-- 
    - custom css link
  -->
    <link rel="stylesheet" href="./assets/css/style.css">

    <!-- 
    - google font link
  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;700&display=swap" rel="stylesheet">

    <!-- 
    - preload images
  -->
    <link rel="preload" as="image" href="./assets/images/hero-bg.jpg">

    <!-- 
    - Add Razorpay script
  -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style style>.payment-details {
  color: white; /* Change text color */
  padding: 20px;
  border-radius: 8px; /* Optional: Adds rounded corners */
}

.payment-details h2 {
  color: #FFf; /* Change title color */
  font-size: 28px; /* Change title font size */
  margin-bottom: 15px; /* Adjust spacing below the title */
}

.payment-details .booking-summary p {
  font-size: 24px; /* Change paragraph font size */
  color: #FFFFFF; /* Change paragraph text color */
  line-height: 1.6; /* Adjust line height */
}

.payment-details .booking-summary p strong {
  font-size: 24px; /* Change strong font size */
  color: #FFf; /* Change strong text color */
}
</style>
    
</head>
<body>
    <!-- 
    - #HEADER
  -->

    <header class="header" data-header>
      <div class="container">
        <a href="index.html" class="logo">
          <img
            src="./assets/images/logo-light.png"
            width="140"
            height="60"
            alt="CredFill home"
            class="logo-light"
          />

          <img
            src="./assets/images/logo-dark.svg"
            width="140"
            height="60"
            alt="CredFill home"
            class="logo-dark"
          />
        </a>

        <nav class="navbar" data-navbar>
          <div class="navbar-top">
            <a href="index.html" class="logo">
              <img
                src="./assets/images/logo-light.png"
                width="140"
                height="60"
                alt="CredFill home"
              />
            </a>

            <button
              class="nav-close-btn"
              aria-label="close menu"
              data-nav-toggler
            >
              <ion-icon name="close-outline" aria-hidden="true"></ion-icon>
            </button>
          </div>

          <ul class="navbar-list">
            <li>
              <a href="index.html" class="navbar-link">Home</a>
            </li>

            <li>
              <a href="services.html" class="navbar-link">Services</a>
            </li>

            <li>
              <a href="about.html" class="navbar-link">About Us </a>
            </li>

            <li>
              <a href="contact.html" class="navbar-link">Contact Us</a>
            </li>
          </ul>

          <div class="wrapper">
            <a href="mailto:infocredfill@gmail.com" class="contact-link"
              >infocredfill@gmail.com</a
            >

            <a href="tel:+91 9472194303" class="contact-link">+91 9472194303</a>
          </div>

          <ul class="social-list">
            <li>
              <a href="https://twitter.com/credfill_" class="social-link">
                <ion-icon name="logo-twitter"></ion-icon>
              </a>
            </li>

            <li>
              <a
                href="https://www.facebook.com/CredFill.in/"
                class="social-link"
              >
                <ion-icon name="logo-facebook"></ion-icon>
              </a>
            </li>

            <li>
              <a
                href="https://www.linkedin.com/company/credfill/"
                class="social-link"
              >
                <ion-icon name="logo-linkedin"></ion-icon>
              </a>
            </li>

            <li>
              <a href="https://www.instagram.com/credfill/" class="social-link">
                <ion-icon name="logo-instagram"></ion-icon>
              </a>
            </li>

            <li>
              <a href="https://www.youtube.com/@CredFill" class="social-link">
                <ion-icon name="logo-youtube"></ion-icon>
              </a>
            </li>
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
        <section class="section payment-section has-bg-image" aria-label="payment" style="background-image: url('./assets/images/hero-bg.jpg')">
            <div class="container">
                <div class="payment-details">
                    <h2 class="h2 section-title">Payment Details</h2>
                    <div class="booking-summary">
                        <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($booking_id); ?></p>
                        <p><strong>Service:</strong> <?php echo htmlspecialchars($bookingDetails['service']); ?></p>
                        <p><strong>Amount:</strong> ₹<?php echo htmlspecialchars($bookingDetails['cost']); ?></p>
                    </div>
                </div>
                <div class="redirect-message" style="text-align: center; margin-top: 30px;">
    <p style="font-size: 1.2em; margin-bottom: 15px;color: #FFFFFF;">You are being redirected to the payment page...</p>
    <div class="loading-animation" style="display: inline-block; width: 40px; height: 40px; border: 4px solid #ccc; border-top-color: #007BFF; border-radius: 50%; animation: spin 1s linear infinite;"></div>
</div>

<!-- Add this CSS for the spinner animation -->
<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
            </div>
        </section>
    </main>

    <!-- 
    - #FOOTER
  -->
    <footer class="footer">
  <div class="container">
    <div class="footer-main">
      <!-- Branding Section -->
      <div class="footer-brand">
        <a href="index.html" class="logo">
          <img src="./assets/images/logo-light.png" width="140" height="60" alt="CredFill home">
        </a>
        <p class="footer-text">&copy; 2025 CredFill. <br> All rights reserved.</p>
      </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Razorpay integration
            var options = {
                "key": "rzp_live_8VZBDNwyyjGfYI",
                "amount": <?php echo ($bookingDetails['cost'] * 100); ?>,
                "currency": "INR",
                "name": "CredFill Pvt. Ltd.",
                "description": "<?php echo htmlspecialchars($bookingDetails['service']); ?>",
                "image": "./assets/images/logo-light.png",
                "order_id": "", // Replace with order ID from your server
                "handler": function (response) {
                    // Handle successful payment
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = window.location.href;
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'payment_status';
                    input.value = 'success';
                    form.appendChild(input);
                    var paymentInput = document.createElement('input');
                    paymentInput.type = 'hidden';
                    paymentInput.name = 'razorpay_payment_id';
                    paymentInput.value = response.razorpay_payment_id;
                    form.appendChild(paymentInput);
                    document.body.appendChild(form);
                    form.submit();
                },
                "prefill": {
                    "name": "<?php echo htmlspecialchars($bookingDetails['name']); ?>",
                    "email": "<?php echo htmlspecialchars($bookingDetails['email']); ?>",
                    "contact": "<?php echo htmlspecialchars($bookingDetails['phone']); ?>"
                },
                "theme": {
                    "color": "#3399cc"
                }
            };

            // Open Razorpay payment gateway after 4 seconds
            setTimeout(function() {
                var rzp1 = new Razorpay(options);
                rzp1.open();
            }, 4000);
        });
    </script>
</body>
</html>

