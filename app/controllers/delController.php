<?php
require_once '../../config/database.php';
require_once '../models/Movie.php';
$movieModel = new Movie($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['movie_id'])) {
    $movie_id = intval($_POST['movie_id']);

    try {
        $db->beginTransaction();

        // Delete related entries
        $db->prepare("DELETE FROM MovieActors WHERE movie_id = ?")->execute([$movie_id]);
        $db->prepare("DELETE FROM MovieGenres WHERE movie_id = ?")->execute([$movie_id]);

        // Delete movie
        $stmt = $db->prepare("DELETE FROM Movies WHERE movie_id = ?");
        $stmt->execute([$movie_id]);

        $db->commit();
        echo json_encode(["status" => "success", "message" => "Movie deleted successfully."]);
    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}

?>