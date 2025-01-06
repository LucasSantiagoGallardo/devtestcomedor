<?php
// Configuración de la base de datos

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include 'sql.php';

// Obtener el ID de la elección y el comentario desde la solicitud
$data = json_decode(file_get_contents("php://input"), true);
$electionId = $data['electionId'] ?? null;
$comment = $data['comment'] ?? '';

if (!$electionId) {
    echo json_encode(["success" => false, "message" => "ID de elección no proporcionado"]);
    exit;
}

// Actualizar el comentario en la base de datos
$query = "UPDATE elecciones SET comentario = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $comment, $electionId);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Comentario guardado exitosamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al guardar el comentario"]);
}

$stmt->close();
$conn->close();
?>
