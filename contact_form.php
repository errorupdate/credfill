<?php
// Database connection settings
$servername = "localhost";
$username = "u931076421_contact_form";
$password = "Credfill@3006";
$dbname = "u931076421_contact_form";

// Initialize response array
$response = array();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

    // Validate email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Create connection to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            $response['status'] = 'error';
            $response['message'] = 'Connection failed: ' . $conn->connect_error;
        } else {
            // Create contacts table if it doesn't exist
            $sql = "CREATE TABLE IF NOT EXISTS contact_submissions (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL,
                phone VARCHAR(20) NOT NULL,
                message TEXT NOT NULL,
                submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            
            if ($conn->query($sql) === FALSE) {
                $response['status'] = 'error';
                $response['message'] = 'Error creating table: ' . $conn->error;
                echo json_encode($response);
                exit();
            }

            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO contact_submissions (name, email, phone, message) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $phone, $message);

            // Execute the query
            if ($stmt->execute()) {
                // Send email notification
                $to = "infocredfill@gmail.com"; // Your email address
                $subject = "New Contact Form Submission";
                $email_message = "You have received a new contact form submission:\n\n";
                $email_message .= "Name: " . $name . "\n";
                $email_message .= "Email: " . $email . "\n";
                $email_message .= "Phone: " . $phone . "\n";
                $email_message .= "Message: " . $message . "\n";
                
                $headers = "From: booking@credfill.com";

                if(mail($to, $subject, $email_message, $headers)) {
                    $response['status'] = 'success';
                    $response['message'] = 'Thank you for contacting us! We will get back to you soon.';
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Form submitted but email notification failed.';
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error submitting form: ' . $stmt->error;
            }

            // Close connections
            $stmt->close();
            $conn->close();
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Invalid email address.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method.';
}

// Return response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>