<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods');

$host = 'localhost';
$db = 'corteva';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

$data = json_decode(file_get_contents("php://input"));

$fecha = $data->fecha;
$tipo = $data->tipo;
$nombre = $data->nombre;
$ingredientes = $data->ingredientes;
$calorias = $data->calorias;
$foto = $data->foto;
$cantidad_porcion = $data->cantidad_porcion;

$query = "INSERT INTO menus (fecha, tipo, nombre, ingredientes, calorias, foto, cantidad_porcion) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssssiss", $fecha, $tipo, $nombre, $ingredientes, $calorias, $foto, $cantidad_porcion);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Menu saved successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save menu']);
}

$stmt->close();
$conn->close();
?>
