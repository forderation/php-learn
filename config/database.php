<?php

class Database
{
    const HOST = "tiny.db.elephantsql.com";
    const DB_NAME = "wgkxkyjo";
    const USERNAME = "";
    const PASSWORD = "";
    private $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("pgsql:host=" . self::HOST . ";dbname=" . self::DB_NAME, self::USERNAME, self::PASSWORD);
            // $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Database could not be connected: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
