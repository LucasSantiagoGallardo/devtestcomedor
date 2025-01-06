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
$photoData = $_POST['photo'] ?? null;

if (!$dni || !$photoData) {
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(['error' => 'Conexión fallida']);
    exit;
}

$photoPath = "fotos/$dni.png"; // Guardar como PNG
file_put_contents($photoPath, file_get_contents($photoData));

echo json_encode(['success' => true]);
$conn->close();
?>
