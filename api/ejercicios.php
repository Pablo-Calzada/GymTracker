<?php
// api/ejercicios.php

// 1. Cabeceras HTTP para permitir que el Frontend lea el JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// 2. Incluir el archivo de conexión que acabas de crear
include_once '../src/Config/database.php';

// 3. Conectar a la base de datos
$database = new Database();
$db = $database->getConnection();

// 4. Preparar y ejecutar la consulta SQL
$query = "SELECT id, nombre, grupoMuscular, descripcion FROM Ejercicio";
$stmt = $db->prepare($query);
$stmt->execute();

$num = $stmt->rowCount();

// 5. Comprobar si hay ejercicios y transformarlos a JSON
if($num > 0) {
    $ejercicios_arr = array();
    $ejercicios_arr["data"] = array(); // Metemos los datos dentro de un array "data"

    // Recorremos los resultados de la base de datos
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $ejercicio_item = array(
            "id" => $id,
            "nombre" => $nombre,
            "grupoMuscular" => $grupoMuscular,
            "descripcion" => $descripcion
        );
        array_push($ejercicios_arr["data"], $ejercicio_item);
    }

    // Devolvemos un código 200 (OK) y los datos en JSON
    http_response_code(200);
    echo json_encode($ejercicios_arr);
} else {
    // Si no hay ejercicios, devolvemos un error 404
    http_response_code(404);
    echo json_encode(array("message" => "No se encontraron ejercicios."));
}
?>