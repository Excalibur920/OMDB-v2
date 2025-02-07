<?php
class Search {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createSearch($user_id, $prompt) {
        $stmt = $this->db->prepare("INSERT IGNORE INTO Searches (user_id, prompt) VALUES (?, ?)");
        return $stmt->execute([$user_id, $prompt]);
    }

    public function getSearchByUserPrompt($user_id, $prompt) {
        $stmt = $this->db->prepare("SELECT * FROM Searches WHERE user_id = ? AND prompt = ? ");
        $stmt->execute([$user_id, $prompt]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>