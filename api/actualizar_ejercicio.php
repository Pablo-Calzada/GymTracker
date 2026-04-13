<?php
// api/actualizar_ejercicio.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../src/Config/database.php';

$database = new Database();
$db = $database->getConnection();

$datos = json_decode(file_get_contents("php://input"));

if(!empty($datos->id) && !empty($datos->nombre)) {
    $query = "UPDATE Ejercicio SET nombre = :nombre, grupoMuscular = :grupo, descripcion = :desc WHERE id = :id";
    $stmt = $db->prepare($query);

    $stmt->bindParam(":id", $datos->id);
    $stmt->bindParam(":nombre", htmlspecialchars(strip_tags($datos->nombre)));
    $stmt->bindParam(":grupo", htmlspecialchars(strip_tags($datos->grupoMuscular)));
    $stmt->bindParam(":desc", htmlspecialchars(strip_tags($datos->descripcion)));

    if($stmt->execute()) {
        echo json_encode(array("message" => "Ejercicio actualizado."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Error al actualizar."));
    }
}
?>