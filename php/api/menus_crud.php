<?php
// Configuración de la base de datos

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require 'sql.php';


// Determinar la acción a ejecutar
$action = $_GET['action'] ?? '';

// Leer el cuerpo de la solicitud JSON
$data = json_decode(file_get_contents("php://input"), true);

// Operaciones CRUD
switch ($action) {
    case 'create':
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
        break;

    case 'read':
        $query = "SELECT id, fecha, tipo, nombre, ingredientes, calorias, foto, cantidad_porcion FROM menus";
        $result = $conn->query($query);

        $menus = [];
        while ($row = $result->fetch_assoc()) {
            $menus[] = $row;
        }

        echo json_encode(["success" => true, "menus" => $menus]);
        break;

    case 'update':
        $id = $data['id'];
        $fecha = $data['fecha'];
        $tipo = $data['tipo'];
        $nombre = $data['nombre'];
        $ingredientes = $data['ingredientes'];
        $calorias = $data['calorias'];
        $foto = $data['foto'];
        $cantidad_porcion = $data['cantidad_porcion'];

        $query = "UPDATE menus SET fecha = ?, tipo = ?, nombre = ?, ingredientes = ?, calorias = ?, foto = ?, cantidad_porcion = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssissi", $fecha, $tipo, $nombre, $ingredientes, $calorias, $foto, $cantidad_porcion, $id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Menú actualizado exitosamente"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al actualizar el menú"]);
        }
        $stmt->close();
        break;

        case 'delete':
            // Asegurarse de que 'id' está presente y es un número válido
            if (!isset($data['id']) || !is_numeric($data['id'])) {
                echo json_encode(["success" => false, "message" => "ID inválido para la eliminación"]);
                break;
            }
        
            $id = (int) $data['id']; // Convertir el ID a entero para evitar problemas de inyección SQL
        
            $query = "DELETE FROM menus WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
        
            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Menú eliminado exitosamente"]);
            } else {
                echo json_encode(["success" => false, "message" => "Error al eliminar el menú"]);
            }
            $stmt->close();
            break;
    default:
        echo json_encode(["success" => false, "message" => "Acción no válida"]);
        break;
}

$conn->close();
?>
