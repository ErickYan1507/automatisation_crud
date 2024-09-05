<?php

class EmixDSL {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function create($items) {
        $query = "INSERT INTO emix (" . implode(', ', array_map(function($field) { return $field['name']; }, $fields)) . ") VALUES ";
        $values = [];
        foreach ($items as $item) {
            $values[] = "('" . implode("', '", array_map(function($field) use ($item) { return $item[$field['name']]; }, $fields)) . "')";
        }
        $query .= implode(', ', $values);
        return $this->conn->query($query);
    }

    public function read() {
        $query = "SELECT * FROM emix";
        return $this->conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $newItem) {
        $query = "UPDATE emix SET " . implode(', ', array_map(function($field) use ($newItem) {
            return $field['name'] . "='" . $newItem[$field['name']] . "'";
        }, $fields)) . " WHERE id=$id";
        return $this->conn->query($query);
    }

    public function delete($id) {
        $query = "DELETE FROM emix WHERE id=$id";
        return $this->conn->query($query);
    }
}