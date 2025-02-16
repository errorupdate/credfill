<?php
// Database connection
$db = new mysqli('localhost', 'u931076421_servicecost', 'Credfill@3006', 'u931076421_servicecost');

if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}

// Get service ID from the URL parameter
$serviceId = isset($_GET['service_id']) ? (int)$_GET['service_id'] : 0;

// Get the cost of the service
$query = "SELECT cost FROM services WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $serviceId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $cost = $result->fetch_assoc()['cost'];
  echo json_encode(['cost' => $cost]);
} else {
  echo json_encode(['cost' => '']);
}

$stmt->close();
$db->close();
?>