<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$host = 'localhost';
$db = 'app';
$user = 'root';
$pass = '';

$dni = $_POST['dni'] ?? null;

if (!$dni) {
    echo json_encode(['error' => 'DNI no recibido']);
    exit;
}

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(['error' => 'Conexión fallida']);
    exit;
}

$query = "SELECT nombre, apellido, area FROM legajos WHERE DNI = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $dni);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    echo json_encode($data);
} else {
    echo json_encode(['error' => 'Usuario no encontrado']);
}

$stmt->close();
$conn->close();
?>
