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
$password = $_POST['password'] ?? null;

if (!$dni || !$password) {
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(['error' => 'Conexión fallida']);
    exit;
}

// Consulta para verificar si el usuario existe y está registrado
$query = "SELECT `password` FROM `legajos` WHERE `DNI` = ? AND `registrado` = 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $dni);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $hashedPassword = $data['password'];
    
    // Comparación de la contraseña ingresada con el hash almacenado
    if (password_verify($password, $hashedPassword)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Contraseña incorrecta']);
    }
} else {
    echo json_encode(['error' => 'Usuario no encontrado o no registrado']);
}

$stmt->close();
$conn->close();
?>
