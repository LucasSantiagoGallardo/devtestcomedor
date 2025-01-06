<?php
// Configuración de la base de datos
require 'sql.php';

// Obtener las fechas de inicio y fin desde la solicitud (si están presentes)
$data = json_decode(file_get_contents("php://input"), true);
$startDate = $data['startDate'] ?? date('Y-m-d', strtotime('-7 days')); // Última semana por defecto
$endDate = $data['endDate'] ?? date('Y-m-d');

// Consulta SQL para obtener las elecciones agrupadas por fecha y opción de menú
$query = "SELECT opcion_menu, fecha, COUNT(opcion_menu) AS total
          FROM elecciones
          WHERE fecha BETWEEN ? AND ?
          GROUP BY opcion_menu, fecha
          ORDER BY fecha";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();

$elections = [];
while ($row = $result->fetch_assoc()) {
    $elections[] = $row;
}

// Enviar los resultados en formato JSON
echo json_encode(["success" => true, "elections" => $elections]);

$stmt->close();
$conn->close();
?>
