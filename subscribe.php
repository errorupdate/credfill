<?php
// Database connection settings
$servername = "localhost"; // For local development
$username = "u931076421_emailsubs";
$password = "Credfill@3006";
$dbname = "u931076421_emailsubs";

// Initialize response array
$response = array();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email_address'], FILTER_SANITIZE_EMAIL);

    // Validate email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Create connection to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check if connection was successful
        if ($conn->connect_error) {
            $response['status'] = 'error';
            $response['message'] = 'Connection failed: ' . $conn->connect_error;
        } else {
            // Insert email into the database
            $stmt = $conn->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)");
            $stmt->bind_param("s", $email);

            // Execute the query
            if ($stmt->execute()) {
                // Send email notification if the insertion is successful
                $to = "infocredfill@gmail.com"; // Your email address where you want notifications
                $subject = "New Newsletter Subscription";
                $message = "A new user has subscribed to the newsletter. Their email is: " . $email;
                $headers = "From: info@credfill.com"; // Your email address to send from

                // Send email
                if(mail($to, $subject, $message, $headers)) {
                    $response['status'] = 'success';
                    $response['message'] = 'Thank you for subscribing! We have received your email.';
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Subscription successful, but email notification failed.';
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error inserting into the database: ' . $stmt->error;
            }

            // Close the database connection
            $stmt->close();
            $conn->close();
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Invalid email address.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request.';
}

// Return response as JSON to the frontend
echo json_encode($response);
?>
