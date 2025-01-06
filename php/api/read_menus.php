<?php
require 'sql.php';

$query = "SELECT id, fecha, tipo, nombre, ingredientes, calorias, foto, cantidad_porcion FROM menus";
$result = $conn->query($query);

$menus = [];
while ($row = $result->fetch_assoc()) {
    $menus[] = $row;
}

echo json_encode(["success" => true, "menus" => $menus]);

$conn->close();
?>
