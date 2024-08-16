<?php

class Database {
    private $conn;

    public function __construct($dbname="reservation", $server="localhost", $username="root", $password="") {
        $this->conn = new mysqli($server, $username, $password, $dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getdata($tablename) {
        $sql = "SELECT * FROM ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $tablename);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result : false;
    }

    public function getdatasql($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param(str_repeat("s", count($params)), ...$params);
        }
        $stmt->execute();
        return $stmt->get_result();
    }

    public function insertdata($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param(str_repeat("s", count($params)), ...$params);
        }
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

?>
