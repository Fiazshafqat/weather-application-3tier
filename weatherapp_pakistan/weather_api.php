<?php
// Database credentials
$host = "10.0.3.29";
$user = "pakistan_user";
$password = "Pakistan@123"; // update if needed
$database = "weather_db_pakistan"; // make sure this matches your actual DB

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed"]);
  exit();
}

// Set header to return JSON
header('Content-Type: application/json');

// Get city from query param (sanitize input)
$city = isset($_GET['city']) ? trim($_GET['city']) : "";
$city = $conn->real_escape_string($city);

// Build query
if ($city !== "") {
  $sql = "SELECT * FROM weather WHERE city LIKE '$city%' LIMIT 10";
} else {
  $sql = "SELECT * FROM weather LIMIT 20";
}

// Fetch results
$result = $conn->query($sql);
$data = [];

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $data[] = [
      "city" => $row["city"],
      "temperature" => $row["temperature"],
      "condition" => $row["condition"]
    ];
  }
}

echo json_encode($data);
$conn->close();
