<?php
// api/eliminar_ejercicio.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");

include_once '../src/Config/database.php';

$database = new Database();
$db = $database->getConnection();

// Obtenemos el ID del ejercicio a eliminar
$datos = json_decode(file_get_contents("php://input"));

if(!empty($datos->id)) {
    $query = "DELETE FROM Ejercicio WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $datos->id);

    if($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("message" => "Ejercicio eliminado."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "No se pudo eliminar."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "ID no proporcionado."));
}
?>