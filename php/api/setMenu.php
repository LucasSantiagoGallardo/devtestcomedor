<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$host = 'localhost';
$db = 'app';
$user = 'root';
$pass = '';

$data = json_decode(file_get_contents("php://input"), true);

$date = $data['date'] ?? null;
$option1 = $data['option1'] ?? null;
$option2 = $data['option2'] ?? null;
$option3 = $data['option3'] ?? null;

if (!$date || !$option1 || !$option2 || !$option3) {
    echo json_encode(['error' => 'Incomplete menu data']);
    exit;
}

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$query = "INSERT INTO menus (date, option1, option2, option3) VALUES (?, ?, ?, ?)
          ON DUPLICATE KEY UPDATE option1 = ?, option2 = ?, option3 = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssssss", $date, $option1, $option2, $option3, $option1, $option2, $option3);
$success = $stmt->execute();

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to save menu']);
}

$stmt->close();
$conn->close();
?>
