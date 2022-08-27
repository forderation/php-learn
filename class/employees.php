<?php
class Employee
{
    // Connection
    private $conn;

    // Table
    private $db_table = "employees";

    // Columns
    public $id;
    public $name;
    public $email;
    public $age;
    public $designation;
    public $created;

    // Statement
    private $stmtGetEmployees;
    private $stmtInsert;
    private $stmtGetEmployeeById;
    private $stmtUpdate;
    private $stmtDelete;

    // DB Connection
    public function __construct($db)
    {
        $this->conn = $db;
        $this->prepareStatement();
    }

    private function prepareStatement()
    {
        $queryGetAll = "SELECT id, name, email, age, designation, created FROM " . $this->db_table;
        $this->stmtGetEmployees = $this->conn->prepare($queryGetAll);

        $queryInsert = "INSERT INTO " . $this->db_table . " (name, email, age, designation, created) VALUES (:name, :email, :age, :designation, :created)";
        $this->stmtInsert = $this->conn->prepare($queryInsert);

        $queryGetById = $queryGetAll . " WHERE  id = ? LIMIT 1";
        $this->stmtGetEmployeeById = $this->conn->prepare($queryGetById);

        $queryUpdate = "UPDATE " . $this->db_table . " SET name = :name, email = :email, age = :age, designation = :designation, created = :created WHERE id = :id";
        $this->stmtUpdate = $this->conn->prepare($queryUpdate);

        $queryDelete = "DELETE FROM " . $this->db_table . " WHERE id = ?";
        $this->stmtDelete = $this->conn->prepare($queryDelete);
    }

    // GET ALL
    public function getEmployees()
    {
        $this->stmtGetEmployees->execute();
        return $this->stmtGetEmployees;
    }

    // CREATE
    public function createEmployee()
    {
        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->age = htmlspecialchars(strip_tags($this->age));
        $this->designation = htmlspecialchars(strip_tags($this->designation));
        $this->created = htmlspecialchars(strip_tags($this->created));

        // bind data
        $this->stmtInsert->bindParam(':name', $this->name);
        $this->stmtInsert->bindParam(':email', $this->email);
        $this->stmtInsert->bindParam(':age', $this->age);
        $this->stmtInsert->bindParam(':designation', $this->designation);
        $this->stmtInsert->bindParam(':created', $this->created);

        if ($this->stmtInsert->execute()) {
            return true;
        }
        return false;
    }

    // READ single
    public function getSingleEmployee()
    {
        $this->stmtGetEmployeeById->bindParam(1, $this->id);
        $this->stmtGetEmployeeById->execute();
        $dataRow = $this->stmtGetEmployeeById->fetch(PDO::FETCH_ASSOC);
        if (isset($dataRow) && $dataRow != false) {
            $this->name = $dataRow['name'];
            $this->email = $dataRow['email'];
            $this->age = $dataRow['age'];
            $this->designation = $dataRow['designation'];
            $this->created = $dataRow['created'];
            return true;
        }
        return false;
    }

    // UPDATE
    public function updateEmployee()
    {
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->age = htmlspecialchars(strip_tags($this->age));
        $this->designation = htmlspecialchars(strip_tags($this->designation));
        $this->created = htmlspecialchars(strip_tags($this->created));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind data
        $this->stmtUpdate->bindParam(":name", $this->name);
        $this->stmtUpdate->bindParam(":email", $this->email);
        $this->stmtUpdate->bindParam(":age", $this->age);
        $this->stmtUpdate->bindParam(":designation", $this->designation);
        $this->stmtUpdate->bindParam(":created", $this->created);
        $this->stmtUpdate->bindParam(":id", $this->id);

        if ($this->stmtUpdate->execute()) {
            return true;
        }
        return false;
    }

    // DELETE
    function deleteEmployee()
    {
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->stmtDelete->bindParam(1, $this->id);
        if ($this->stmtDelete->execute()) {
            return true;
        }
        return false;
    }
}
