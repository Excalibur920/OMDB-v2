<?php
require_once '../../config/database.php';
require_once '../models/Movie.php';

    file_put_contents("debug_log.txt", "Received: " . print_r($_POST,true) . " | " . print_r($_GET,true)  ."\n", FILE_APPEND); 


$movieModel = new Movie($db);

//if ($_SERVER['REQUEST_METHOD'] == 'GET') {
//    if (isset($_GET['id'])) {
//        echo json_encode($movieModel->getMovieById($_GET['user_id']));
//    } else {
//        echo json_encode($movieModel->getAllMovies());
//    }
//    exit;
//}

//if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//    $data = json_decode(file_get_contents("php://input"), true);
//    $movie_id = $movieModel->createMovie($data);
//
//    if ($movie_id) {
//        $movieModel->addGenres($movie_id, $data['genres']);
//        $movieModel->addActors($movie_id, $data['actors']);
//        echo json_encode(["message" => "Movie added successfully"]);
//    } else {
//        echo json_encode(["message" => "Error adding movie"]);
//    }
//    exit;
//}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_movie'])) {
    $movieModel->updateMovie($_POST);
    header("Location: ../views/details.php?id=" . $_POST['movie_id']);
    exit;
}

    //echo "Debug movieController: " . $_SERVER['REQUEST_METHOD'] . " | Get:" . print_r($_GET,true) . " | Post:" . print_r($_POST,true); 


?>