<?php

include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../class/employees.php';

trait CreateApi
{
    public static function createEmployee()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $database = new Database();
        $db = $database->getConnection();
        $item = new Employee($db);
        $data = json_decode(file_get_contents("php://input"));
        $item->name = $data->name;
        $item->email = $data->email;
        $item->age = $data->age;
        $item->designation = $data->designation;
        $item->created = date('Y-m-d H:i:s');

        if ($item->createEmployee()) {
            return json_encode('Employee created successfully.');
        } else {
            return json_encode('Employee could not be created.');
        }
    }
}
