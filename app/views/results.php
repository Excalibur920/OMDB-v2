<?php
    require_once "../controllers/movieController.php";
?>

<!DOCTYPE html>

<html>
    <head>
		<link rel="stylesheet" href="../../stylesheet.css">
    </head>
</html>

<?php
$title = $_GET['title'];

$result = $movieModel->getMovieByTitle($title);

?>

<html>
    <div class="row" style="padding:30px;">
        <div class="rcolumn" style="background-color:#8c9ec0;padding: 30px;">
            <?php
                echo "<pre>";
                echo "Title: " . $result["title"];
                echo "<br>";
                echo "Year of Release: " . $result["year"];
                echo "<br>";
                echo "Rating: " . $result["rated"];
                echo "<br>";
                echo "Released on: " . $result["released"];
                echo "<br>";
                echo "Runtime: " . $result["runtime"];
                echo "<br>";
                echo "Genre: " . $result["genres"];
                echo "<br>";
                echo "Actors: " . $result["actors"];
                echo "<br>";
                echo "<textarea readonly class='form-control' name='plot' style='height: 100px;width: 500px;'>" . $result["plot"] . "</textarea>";
                echo "<br>";
                echo "Languages: " . $result["language"];
                echo "<br>";
                echo "ImDB Rating: " . $result["imdb_rating"];
                echo "</pre>";
            ?>
        </div>
        <div class="rcolumn">
            <div class="img-container">
                <img src=<?php echo $result["poster_url"] ?>>
            </div>
        </div>
    </div>

    <div class="footer">
	    <a href="./history.php" class="button">View History</a>
        <a href="./search.php" class="button">Search</a>
    </div>
</html>
