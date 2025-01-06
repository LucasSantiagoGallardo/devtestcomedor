<?php
// Configuración de la base de datos
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$host = 'localhost';
$db = 'app';
$user = 'root';
$pass = '';

// Conexión a la base de datos
$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Error de conexión a la base de datos: " . $conn->connect_error]);
    exit;
}

// Obtener el DNI desde la solicitud (método POST)
$dni = $_POST['dni'] ?? '36832355';
if (!$dni) {
    echo json_encode(["success" => false, "message" => "DNI no proporcionado"]);
    exit;
}

// Obtener la fecha actual en formato YYYY-MM-DD
$fechaHoy = date("Y-m-d");

// Consulta SQL para obtener el menú elegido por el usuario en la fecha de hoy
$query = "SELECT id, fecha, tipo, nombre, ingredientes, calorias, foto, cantidad_porcion
          FROM menus
          WHERE fecha = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $fechaHoy, $dni);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontró un menú para el día de hoy
if ($result->num_rows > 0) {
    $menu = $result->fetch_assoc();
    echo json_encode(["success" => true, "menu" => $menu]);
} else {
    // No se encontró un menú para el día de hoy
    echo json_encode(["success" => false, "message" => "No se encontró un menú para hoy."]);
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
