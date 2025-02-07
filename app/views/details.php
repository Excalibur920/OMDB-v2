<!DOCTYPE html>
<?php

	require_once "../../config/database.php";
	require_once "../controllers/movieController.php";
	$movie_id = $_GET['movie_id'];
	$movie = $movieModel->getMovieById($movie_id);

?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

$(document).ready(function () {
    $(".save").click(function() {
    	
        var movie_id = document.getElementById("dex").value;
        var movtitle = document.getElementById("title").value;
        var plot = document.getElementById("plot").value;
        var genres = document.getElementById("genre").value;
        var actors = document.getElementById("actors").value;  

        if (confirm("Are you sure you want to make these changes?")) {
            $.ajax({
                type: "POST",
                url: "../controllers/updController.php",
                data: {dex: movie_id, movtitle: movtitle, plot: plot, genre: genres, actors: actors},
                dataType: "json",
                success: function (response) {
                    if (response.status === "success") {
                        alert("Movie updated successfully!");
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function () {
                    alert("An error occurred while updating the movie.");
                }
            });
        }
    });
});

</script>
<html>
	<head>
		  <link rel="stylesheet" href="../../stylesheet.css">
	</head>
    <div class="row" style="padding:30px;">
        <div class="rcolumn" style="background-color:#8c9ec0;padding: 30px;">
            <form id="updateForm" class="updateForm">
                <input type="hidden" id="dex" name="dex" value="<?php echo $movie["movie_id"] ?>">
                    <?php
                        echo "<pre>";
                        echo "Submitted by: " . $movie["username"];
                        echo "<br>";
                        echo "Prompt: " . $movie["prompt"];
                        echo "<br>";
                        echo "Title: <input type='text' class='form-control' id='title' name='movtitle' value='" . $movie["title"] . "'>";
                        echo "<br>";
                        echo "Year of Release: " . $movie["year"];
                        echo "<br>";
                        echo "Rating: " . $movie["rated"];
                        echo "<br>";
                        echo "Released on: " . $movie["released"];
                        echo "<br>";
                        echo "Runtime: " . $movie["runtime"];
                        echo "<br>";
                        echo "Genre: <input type='text' class='form-control' id='genre' name='genre' value='" . $movie["genres"] . "'>";
                        echo "<br>";
                        echo "Actors: <input type='text' class='form-control' id='actors' name='actors' value='" . $movie["actors"] . "'>";
                        echo "<br>";
                        echo "<div>Summary: </div> <textarea class='form-control' id='plot' name='plot' style='height: 100px;width: 500px;'>" . $movie["plot"] . "</textarea>";
                        echo "<br>";
                        echo"Languages: " . $movie["language"];
                        echo "<br>";
                        echo"ImDB Rating: " . $movie["imdb_rating"];
                        echo "</pre>";
                    ?>
                <button type="button" class="save" id="save">Save Changes</button>
            </form>
        </div>
        <div class="rcolumn">
            <div class="img-container">
                <img src=<?php print_r($movie["poster_url"]) ?>>
            </div>
        </div>
    </div>

    <div class="footer">
	      <a href="./history.php" class="button">View History</a>
        <a href="./search.php" class="button">Search</a>
    </div>
</html>



