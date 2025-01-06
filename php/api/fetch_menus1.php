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

// Consulta para obtener los datos de la tabla menus
$query = "SELECT `id`, `fecha`, `tipo`, `nombre`, `ingredientes`, `calorias`, `foto`, `cantidad_porcion` FROM `menus` WHERE 1";
$result = $conn->query($query);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    $menus = [];

    // Recorrer los resultados y agregarlos a la lista de menús
    while ($row = $result->fetch_assoc()) {
        $menus[] = [
            "id" => $row["id"],
            "fecha" => $row["fecha"],
            "tipo" => $row["tipo"],
            "nombre" => $row["nombre"],
            "ingredientes" => $row["ingredientes"],
            "calorias" => $row["calorias"],
            "foto" => $row["foto"],
            "cantidad_porcion" => $row["cantidad_porcion"]
        ];
    }

    // Devolver los datos en formato JSON
    echo json_encode(["success" => true, "menus" => $menus]);
} else {
    // No hay menús disponibles
    echo json_encode(["success" => false, "message" => "No hay menús disponibles."]);
}

// Cerrar la conexión
$conn->close();
?>
