<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$host = 'localhost';
$db = 'corteva';
$user = 'root';
$pass = '';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$fecha = $data['fecha'] ?? null;
$userId = $data['user_id'] ?? null;

if (!$fecha || !$userId) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos necesarios']);
    exit;
}

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Conexión fallida: ' . $conn->connect_error]);
    exit;
}

// Consultar si ya existe una elección para la fecha y el usuario
$query = "SELECT * FROM elecciones WHERE fecha = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $fecha, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['eleccion_existente' => true]);
} else {
    echo json_encode(['eleccion_existente' => false]);
}

$stmt->close();
$conn->close();
?>
