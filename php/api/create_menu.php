<?php
// Configuración de la base de datos

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require 'sql.php';


// Obtener datos desde el request
$data = json_decode(file_get_contents("php://input"), true);
$fecha = $data['fecha'];
$tipo = $data['tipo'];
$nombre = $data['nombre'];
$ingredientes = $data['ingredientes'];
$calorias = $data['calorias'];
$foto = $data['foto'];
$cantidad_porcion = $data['cantidad_porcion'];

$query = "INSERT INTO menus (fecha, tipo, nombre, ingredientes, calorias, foto, cantidad_porcion) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssssiss", $fecha, $tipo, $nombre, $ingredientes, $calorias, $foto, $cantidad_porcion);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Menú creado exitosamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al crear el menú"]);
}

$stmt->close();
$conn->close();
?>
