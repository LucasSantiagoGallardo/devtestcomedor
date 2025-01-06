<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

header('Content-Type: application/json');

// Your actual API logic here
$data = array(
    "name" => "John",
    "age" => 30,
    "city" => "New York"
);

echo json_encode($data);
?>

