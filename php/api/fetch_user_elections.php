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

// Consulta para obtener las últimas 7 elecciones del usuario, junto con los detalles del menú correspondiente
$query = "
    SELECT 
        e.id AS id, 
        e.fecha AS election_date, 
        e.user_id, 
        e.opcion_menu, 
        e.currrent, 
        m.id AS menu_id, 
        m.fecha AS menu_date, 
        m.tipo, 
        m.nombre, 
        m.ingredientes, 
        m.calorias, 
        m.foto, 
        m.cantidad_porcion 
    FROM 
        elecciones e 
    JOIN 
        menus m ON m.tipo = e.opcion_menu 
    WHERE 
        e.user_id = ?  and e.fecha = m.fecha
    ORDER BY 
        e.fecha DESC 
    LIMIT 7
";

$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Error en la preparación de la consulta", "error" => $conn->error]);
    exit;
}

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$elections = [];
while ($row = $result->fetch_assoc()) {
    $elections[] = $row;
}

// Verifica si se encontraron resultados
if (empty($elections)) {
    echo json_encode(["success" => false, "message" => "No se encontraron elecciones para el usuario especificado"]);
} else {
    echo json_encode(["success" => true, "elections" => $elections]);
}

// Cierre de la consulta y la conexión
$stmt->close();
$conn->close();
?>
