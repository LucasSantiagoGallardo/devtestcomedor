<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$host = 'localhost';
$db = 'corteva';
$user = 'root';
$pass = '';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Extraer datos
$fecha = $data['fecha'] ?? '2024-01-20';
$userId = $data['user_id'] ?? '36832355';
$opcionMenu = $data['opcion_menu'] ?? 'platoprincipal';

if (!$fecha || !$userId || !$opcionMenu) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos necesarios']);
    exit;
}

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Conexión fallida: ' . $conn->connect_error]);
    exit;
}

// Verificar si user_id existe en usuarios


// Insertar en elecciones si user_id es válido
$query = "INSERT INTO elecciones (fecha, user_id, opcion_menu) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $fecha, $userId, $opcionMenu);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Elección guardada exitosamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar elección: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
