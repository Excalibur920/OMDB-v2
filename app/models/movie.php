<?php
require_once "../../config/database.php";
class Movie {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllMovies() {
        return $this->db->query("SELECT * FROM Movies")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMovieById($id) {
        $stmt = $this->db->prepare("
            SELECT 
            m.*, 
            u.username, 
            s.prompt,
            GROUP_CONCAT(DISTINCT g.genre_name ORDER BY g.genre_name ASC) AS genres,
            GROUP_CONCAT(DISTINCT a.actor_name ORDER BY a.actor_name ASC) AS actors
        FROM Movies m
        LEFT JOIN Searches s ON s.prompt = m.title
        LEFT JOIN Users u ON u.user_id = s.user_id
        LEFT JOIN MovieGenres mg ON mg.movie_id = m.movie_id
        LEFT JOIN Genres g ON g.genre_id = mg.genre_id
        LEFT JOIN MovieActors ma ON ma.movie_id = m.movie_id
        LEFT JOIN Actors a ON a.actor_id = ma.actor_id
        WHERE m.movie_id = :movie_id
        GROUP BY m.movie_id, u.username, s.prompt
        ");
        $stmt->execute([':movie_id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getMovieByTitle($title) {
        $stmt = $this->db->prepare("
            SELECT m.*, 
                   GROUP_CONCAT(DISTINCT g.genre_name) AS genres,
                   GROUP_CONCAT(DISTINCT a.actor_name) AS actors
            FROM Movies m
            LEFT JOIN MovieGenres mg ON m.movie_id = mg.movie_id
            LEFT JOIN Genres g ON mg.genre_id = g.genre_id
            LEFT JOIN MovieActors ma ON m.movie_id = ma.movie_id
            LEFT JOIN Actors a ON ma.actor_id = a.actor_id
            WHERE m.title = ?
            GROUP BY m.movie_id
        ");
        $stmt->execute([$title]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveMovieFromOMDB($movieData) {
        try {
            $this->db->beginTransaction();
    
            // Insert movie (ignore duplicates based on title & year)
            $stmt = $this->db->prepare("
                INSERT INTO Movies (title, year, rated, released, runtime, plot, language, poster_url, imdb_rating)
                VALUES (:title, :year, :rated, :released, :runtime, :plot, :language, :poster_url, :imdb_rating)
                ON DUPLICATE KEY UPDATE movie_id = LAST_INSERT_ID(movie_id)
            ");
            $stmt->execute([
                ':title'       => $movieData['Title'],
                ':year'        => $movieData['Year'],
                ':rated'       => $movieData['Rated'],
                ':released'    => $movieData['Released'],
                ':runtime'     => $movieData['Runtime'],
                ':plot'        => $movieData['Plot'],
                ':language'    => $movieData['Language'],
                ':poster_url'  => $movieData['Poster'],
                ':imdb_rating' => $movieData['imdbRating']
            ]);
            $movie_id = $this->db->lastInsertId();
    
            // Insert Genres
            $genres = explode(", ", $movieData['Genre']);
            foreach ($genres as $genre) {
                $stmt = $this->db->prepare("INSERT IGNORE INTO Genres (genre_name) VALUES (:genre)");
                $stmt->execute([':genre' => $genre]);
    
                $stmt = $this->db->prepare("
                    INSERT IGNORE INTO MovieGenres (movie_id, genre_id) 
                    VALUES (:movie_id, (SELECT genre_id FROM Genres WHERE genre_name = :genre))
                ");
                $stmt->execute([':movie_id' => $movie_id, ':genre' => $genre]);
            }
    
            // Insert Actors
            $actors = explode(", ", $movieData['Actors']);
            foreach ($actors as $actor) {
                $stmt = $this->db->prepare("INSERT IGNORE INTO Actors (actor_name) VALUES (:actor)");
                $stmt->execute([':actor' => $actor]);
    
                $stmt = $this->db->prepare("
                    INSERT IGNORE INTO MovieActors (movie_id, actor_id) 
                    VALUES (:movie_id, (SELECT actor_id FROM Actors WHERE actor_name = :actor))
                ");
                $stmt->execute([':movie_id' => $movie_id, ':actor' => $actor]);
            }
    
            $this->db->commit();
            return ["status" => "success", "message" => "Movie saved successfully!"];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }
    

    public function addGenres($movie_id, $genres) {
        foreach ($genres as $genre) {
            $stmt = $this->db->prepare("INSERT INTO MovieGenres (movie_id, genre_id) 
                                        VALUES (?, (SELECT genre_id FROM Genres WHERE genre_name = ?))");
            $stmt->execute([$movie_id, $genre]);
        }
    }

    public function addActors($movie_id, $actors) {
        foreach ($actors as $actor) {
            $stmt = $this->db->prepare("INSERT INTO MovieActors (movie_id, actor_id) 
                                        VALUES (?, (SELECT actor_id FROM Actors WHERE actor_name = ?))");
            $stmt->execute([$movie_id, $actor]);
        }
    }
    public function deleteMovie($id) {
        $stmt = $this->db->prepare("DELETE FROM Movies WHERE movie_id = ?");
        return $stmt->execute([$id]);
    }    
}

?>