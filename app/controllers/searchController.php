<?php
     
    require_once '../../config/database.php';
    require_once '../models/search.php';
    require_once '../models/movie.php';
    require_once '../models/user.php';
    
    $searchModel = new Search($db);
    $movieModel = new Movie($db);
    $userModel = new User($db);
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $_SESSION['username'] = $username;
        $prompt = $_POST['prompt'];

        // Ensure user exists
        $user = $userModel->getUserByUsername($username);
        if (!$user) {
            $userModel->createUser($username);
        }
    
        $user_id = $user['user_id'];
        $_SESSION['user_id'] = $user_id;
    
        // Check if movie exists in the database
        $existingMovie = $movieModel->getMovieByTitle($prompt);
        if ($existingMovie) {
            header("Location: ../views/details.php?movie_id=" . $existingMovie['movie_id']);
            exit;
        }
    
        // Otherwise, fetch from OMDb API
        $apiKey = "90cef035";
        $apiUrl = "http://www.omdbapi.com/?t=" . urlencode($prompt) . "&apikey=" . $apiKey;
        $response = file_get_contents($apiUrl);
        $movieData = json_decode($response, true);
    
        if ($movieData['Response'] == 'True') {
            // Log search
            $searchModel->createSearch($user_id, $prompt);
    
            // Save to database
            $result = $movieModel->saveMovieFromOMDB($movieData);
            print_r($result);
    
            header("Location: ../views/results.php?title=" . $movieData['Title']);
            exit;
        } else {
            echo "Movie not found!";
        }
    }
    
?>