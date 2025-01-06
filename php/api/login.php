<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // Cambiar por el nombre de usuario de la base de datos
$password = ""; // Cambiar por la contraseña de la base de datos
$dbname = "corteva"; // Nombre de la base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Conexión fallida: ' . $conn->connect_error]));
}

// Leer los datos enviados por el cliente
$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$password = $data['password'];

// Consultar el usuario en la base de datos
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verificar la contraseña (asegurarse de que esté usando hash seguro como bcrypt)
    if (password_verify($password, $user['password_hash'])) {
        echo json_encode(['success' => true, 'message' => 'Login correcto']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
}

$conn->close();
?>
