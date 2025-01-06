<?php 

$host = 'localhost';
$db = 'corteva';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Error de conexión a la base de datos: " . $conn->connect_error]);
    exit;
}

?>