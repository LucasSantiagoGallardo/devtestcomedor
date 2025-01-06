<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$host = 'localhost';
$db = 'corteva';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

$query = "SELECT fecha, tipo, nombre, ingredientes, calorias, foto, cantidad_porcion FROM menus";
$result = $conn->query($query);

$menus = [];
while ($row = $result->fetch_assoc()) {
    $menus[] = $row;
}

echo json_encode(['success' => true, 'menus' => $menus]);

$conn->close();
?>
