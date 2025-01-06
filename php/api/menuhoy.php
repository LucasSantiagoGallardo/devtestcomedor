<?php
// Configuración de la base de datos
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require 'sql.php';

// Obtener el ID de usuario desde la solicitud
$data = json_decode(file_get_contents("php://input"), true);
$userId = $data['userId'] ?? null;

if (!$userId) {
    echo json_encode(["success" => false, "message" => "ID de usuario no proporcionado"]);
    exit;
}

// Verificar si el usuario ya reservó el menú para hoy
$queryReservation = "
    SELECT 
        e.id AS election_id, 
        e.opcion_menu, 
        m.nombre, 
        m.ingredientes, 
        m.calorias, 
        m.foto, 
        m.cantidad_porcion 
    FROM 
        elecciones e 
    JOIN 
        menus m ON e.opcion_menu = m.tipo AND e.fecha = m.fecha
    WHERE 
        e.user_id = ? AND e.fecha = CURDATE()
";

$stmt = $conn->prepare($queryReservation);
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Error en la preparación de la consulta", "error" => $conn->error]);
    exit;
}

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$reservation = $result->fetch_assoc();

if ($reservation) {
    echo json_encode([
        "success" => true,
        "reserved" => true,
        "menu" => $reservation
    ]);
    exit;
}

// Si no reservó, obtener las 3 opciones disponibles para hoy
$queryOptions = "
    SELECT 
        id, tipo, nombre, ingredientes, calorias, foto, cantidad_porcion 
    FROM 
        menus 
    WHERE 
        fecha = CURDATE()
";

$resultOptions = $conn->query($queryOptions);
$menus = [];
while ($row = $resultOptions->fetch_assoc()) {
    $menus[] = $row;
}

echo json_encode([
    "success" => true,
    "reserved" => false,
    "menus" => $menus
]);

$conn->close();
?>
