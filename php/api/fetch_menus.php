<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods');

$host = 'localhost';
$db = 'app';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

$fecha = $_GET['fecha'];

$query = "SELECT tipo, nombre, ingredientes, calorias, foto, cantidad_porcion FROM menus WHERE fecha = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $fecha);
$stmt->execute();
$result = $stmt->get_result();

$menus = [];
while ($row = $result->fetch_assoc()) {
    $menus[] = $row;
}

echo json_encode(['success' => true, 'data' => $menus]);

$stmt->close();
$conn->close();
?>
