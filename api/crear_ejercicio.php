<?php
// api/crear_ejercicio.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../src/Config/database.php';

$database = new Database();
$db = $database->getConnection();

// Leemos los datos que vienen del formulario (formato JSON)
$datos = json_decode(file_get_contents("php://input"));

if(!empty($datos->nombre) && !empty($datos->grupoMuscular)) {
    
    // Preparamos la consulta SQL de inserción
    $query = "INSERT INTO Ejercicio (nombre, grupoMuscular, descripcion) VALUES (:nombre, :grupo, :desc)";
    $stmt = $db->prepare($query);

    // Limpiamos los datos para evitar inyecciones SQL (Seguridad pedida en RNF-01)
    $nombre = htmlspecialchars(strip_tags($datos->nombre));
    $grupo = htmlspecialchars(strip_tags($datos->grupoMuscular));
    $desc = htmlspecialchars(strip_tags($datos->descripcion));

    // Vinculamos los parámetros
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":grupo", $grupo);
    $stmt->bindParam(":desc", $desc);

    if($stmt->execute()) {
        http_response_code(201); // 201 = Creado con éxito
        echo json_encode(array("message" => "Ejercicio creado."));
    } else {
        http_response_code(503); // 503 = Error de servidor
        echo json_encode(array("message" => "No se pudo crear el ejercicio."));
    }
} else {
    http_response_code(400); // 400 = Datos incompletos
    echo json_encode(array("message" => "Faltan datos obligatorios."));
}
?>