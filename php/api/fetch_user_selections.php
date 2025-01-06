<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$host = 'localhost';
$db = 'corteva';
$user = 'root';
$pass = '';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$userId = $data['user_id'] ?? null;

if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos necesarios']);
    exit;
}

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Conexión fallida: ' . $conn->connect_error]);
    exit;
}

// Consultar las elecciones del usuario
$query = "SELECT fecha, opcion_menu FROM elecciones WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

$selections = [];
while ($row = $result->fetch_assoc()) {
    $selections[] = $row;
}

echo json_encode(['success' => true, 'selections' => $selections]);

$stmt->close();
$conn->close();
?>
