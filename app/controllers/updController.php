<?php
require_once '../../config/database.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $db->beginTransaction();

        $movie_id = $_POST['dex'];
        $title = $_POST['movtitle'];
        $plot = $_POST['plot'];
        $genres = explode(", ",$_POST['genre']); 
        $actors = explode(", ",$_POST['actors']);
// echo "debug upcontroller " . $title . "<br>";
        // Update Movies table
        $stmt = $db->prepare("
            UPDATE Movies 
            SET title = :title, plot = :plot 
            WHERE movie_id = :movie_id
        ");
        $stmt->execute([
            ':title' => $title,
            ':plot' => $plot,
            ':movie_id' => $movie_id
        ]);

        // Update Genres
        $db->prepare("DELETE FROM MovieGenres WHERE movie_id = :movie_id")->execute([':movie_id' => $movie_id]);

        foreach ($genres as $genre) {
            $stmt = $db->prepare("INSERT INTO Genres (genre_name) VALUES (:genre)");
            $stmt->execute([':genre' => $genre]);

            $stmt = $db->prepare("
                INSERT INTO MovieGenres (movie_id, genre_id) 
                VALUES (:movie_id, (SELECT genre_id FROM Genres WHERE genre_name = :genre))
            ");
            $stmt->execute([':movie_id' => $movie_id, ':genre' => $genre]);
        }

        // Update Actors
         $db->prepare("DELETE FROM MovieActors WHERE movie_id = :movie_id")->execute([':movie_id' => $movie_id]);

        foreach ($actors as $actor) {
            $stmt = $db->prepare("INSERT INTO Actors (actor_name) VALUES (:actor)");
            $stmt->execute([':actor' => $actor]);

            $stmt = $db->prepare("
                INSERT INTO MovieActors (movie_id, actor_id) 
                VALUES (:movie_id, (SELECT actor_id FROM Actors WHERE actor_name = :actor))
            ");
            $stmt->execute([':movie_id' => $movie_id, ':actor' => $actor]);
        }

        $db->commit();
        echo json_encode(["status" => "success", "message" => "Movie updated successfully!"]);
    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
