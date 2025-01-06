<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Conexión a la base de datos
$host = 'localhost';
$db = 'app';
$user = 'root';
$pass = '';

// Obtener el cuerpo de la solicitud como JSON
$input = file_get_contents('php://input') ;
$data = json_decode($input, true); // Decodificar el JSON en un array asociativo

// Obtener el DNI del JSON
$dni = $data['dni'] ?? $_POST['dni'];

if (!$dni) {
    echo json_encode(['error' => 'DNI no recibido']);
    exit;
}

// Conectar a la base de datos
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(['error' => 'Conexión fallida']);
    exit;
}

// Consultar la base de datos para obtener los datos del usuario
$query = "SELECT * FROM `legajos` WHERE `DNI` = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $dni);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    
    // Construir la respuesta
    $response = [
        'status' => ($data['registrado'] == 1 ? 'registrado' : 'nuevo'),
        'nombre' => $data['NOMBRE'] ?? '',
        'apellido' => $data['APELLIDO'] ?? '',
        'registro' => $data['registrado'] ?? 0,
        'area' => $data['AREA'] ?? '',
        'empresa' => $data['EMPRESA'] ?? '',
        'rfid' => $data['DAT2'] ?? '',
    ];
    echo json_encode($response);
} else {
    echo json_encode(['error' => 'DNI no encontrado']);
}

$stmt->close();
$conn->close();
?>
