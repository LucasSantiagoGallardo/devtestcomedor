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

// Encriptar la contraseña antes de guardarla
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$query = "UPDATE `legajos` SET `registrado` = 1, `password` = ? WHERE `DNI` = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $hashedPassword, $dni);
$success = $stmt->execute();

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Error al registrar usuario']);
}

$stmt->close();
$conn->close();
?>
