<?php
// api/index.php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include_once('../Config/database.php');
include_once('../Model/Appointment.php');

$appointment = new Appointment($pdo);

// Parse the incoming request method
$request_method = $_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'GET':
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $result = $appointment->getById($id);
            echo json_encode($result);
        } else {
            $result = $appointment->getAll();
            echo json_encode($result);
        }
        break;

    case 'POST':
        // Create new appointment
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->name, $data->mob, $data->date, $data->doctor, $data->department)) {
            $result = $appointment->create($data->name, $data->mob, $data->date, $data->doctor, $data->department);
            if ($result) {
                echo json_encode(["message" => "Appointment created successfully."]);
            } else {
                echo json_encode(["message" => "Failed to create appointment."]);
            }
        } else {
            echo json_encode(["message" => "Missing required fields."]);
        }
        break;

    case 'PUT':
        // Update appointment
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->id, $data->name, $data->mob, $data->date, $data->doctor, $data->department)) {
            $result = $appointment->update($data->id, $data->name, $data->mob, $data->date, $data->doctor, $data->department);
            if ($result) {
                echo json_encode(["message" => "Appointment updated successfully."]);
            } else {
                echo json_encode(["message" => "Failed to update appointment."]);
            }
        } else {
            echo json_encode(["message" => "Missing required fields."]);
        }
        break;

    case 'DELETE':
        // Delete appointment
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $result = $appointment->delete($id);
            if ($result) {
                echo json_encode(["message" => "Appointment deleted successfully."]);
            } else {
                echo json_encode(["message" => "Failed to delete appointment."]);
            }
        } else {
            echo json_encode(["message" => "Missing appointment ID."]);
        }
        break;

    default:
        echo json_encode(["message" => "Invalid Request Method"]);
        break;
}
?>
