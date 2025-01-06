<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require 'sql.php';


$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];

$query = "DELETE FROM menus WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Menú eliminado exitosamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al eliminar el menú"]);
}

$stmt->close();
$conn->close();
?>
