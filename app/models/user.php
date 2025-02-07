<?php
class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getUserByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM Users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($username) {
        $stmt = $this->db->prepare("INSERT INTO Users (username) VALUES (?)");
        return $stmt->execute([$username]);
        return $this->db->lastInsertId();
    }
}

?>