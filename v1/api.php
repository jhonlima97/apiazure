<?php

require('../db/config.php');

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM users WHERE ID = ?";
            $params = array($id);
            $stmt = sqlsrv_query($conn, $sql, $params);
        } else {
            $sql = "SELECT * FROM users";
            $stmt = sqlsrv_query($conn, $sql);
        }

        if ($stmt === false) {
            http_response_code(500);
            echo json_encode(array("message" => "Error en la consulta."));
            die(print_r(sqlsrv_errors(), true));
        }

        $rows = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $rows[] = $row;
        }

        echo json_encode($rows);
        break;

    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        $name = $input['Name'];
        $email = $input['Email'];

        $sql = "INSERT INTO users (Name, Email) VALUES (?, ?)";
        $params = array($name, $email);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            http_response_code(500);
            echo json_encode(array("message" => "Error al insertar el registro."));
            die(print_r(sqlsrv_errors(), true));
        }
        echo json_encode(array("message" => "Registro insertado correctamente."));
        break;

    case 'PUT':
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['ID'];
        $name = $input['Name'];
        $email = $input['Email'];

        $sql = "UPDATE users SET Name = ?, Email = ? WHERE ID = ?";
        $params = array($name, $email, $id);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            http_response_code(500);
            echo json_encode(array("message" => "Error al actualizar el registro."));
            die(print_r(sqlsrv_errors(), true));
        }
        echo json_encode(array("message" => "Registro actualizado correctamente."));
        break;

    case 'DELETE':
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['ID'];

        $sql = "DELETE FROM users WHERE ID = ?";
        $params = array($id);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            http_response_code(500);
            echo json_encode(array("message" => "Error al eliminar el registro."));
            die(print_r(sqlsrv_errors(), true));
        }
        echo json_encode(array("message" => "Registro eliminado correctamente."));
        break;

    
    default:
        http_response_code(405);
        echo json_encode(array("message" => "Método no permitido."));
        break;
}

sqlsrv_close($conn);
?>
