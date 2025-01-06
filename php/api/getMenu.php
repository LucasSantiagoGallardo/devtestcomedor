<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$host = 'localhost';
$db = 'app';
$user = 'root';
$pass = '';

$date = $_GET['date'] ?? null;

if (!$date) {
    echo json_encode(['error' => 'No date provided']);
    exit;
}

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$query = "SELECT * FROM menus WHERE date = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(['error' => 'No menu found for this date']);
}

$stmt->close();
$conn->close();
?>
