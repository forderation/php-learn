<?php

include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../config/redis.php';
include_once __DIR__ . '/../class/employees.php';
include_once __DIR__ . '/../request.php';

trait ReadApi
{
    public function getEmployee(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        $redis = new Redis();

        $query = $request->getQueryParam();
        $stmt = null;
        $employeeArr = array();
        $employeeArr["body"] = array();
        $employeeArr["item_count"] = 0;
        if (isset($query['id'])) {
            $id = $query['id'];
            $keyCache = "employee-" . $id;
            $cache = $redis->GetCache($keyCache);
            $val = json_decode($cache, true);
            if ($val != null) {
                return json_encode($val);
            }
            $database = new Database();
            $db = $database->getConnection();
            $items = new Employee($db);
            $items->id = $id;
            if ($items->getSingleEmployee()) {
                $e = array(
                    "id" => $items->id,
                    "name" => $items->name,
                    "email" => $items->email,
                    "age" => $items->age,
                    "designation" => $items->designation,
                    "created" => $items->created
                );
                $employeeArr["item_count"] = 1;
                array_push($employeeArr["body"], $e);
                $redis->SetCache($keyCache, json_encode($employeeArr));
            }
        } else {
            $keyCache = "employee";
            $cache = $redis->GetCache($keyCache);
            $val = json_decode($cache, true);
            if ($val != null) {
                return json_encode($val);
            }
            $database = new Database();
            $db = $database->getConnection();
            $items = new Employee($db);
            $stmt = $items->getEmployees();
            $itemCount = $stmt->rowCount();
            $employeeArr["item_count"] = $itemCount;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $e = array(
                    "id" => $id,
                    "name" => $name,
                    "email" => $email,
                    "age" => $age,
                    "designation" => $designation,
                    "created" => $created
                );
                array_push($employeeArr["body"], $e);
            }
            $redis->SetCache($keyCache, json_encode($employeeArr));
        }
        return json_encode($employeeArr);
    }
}
