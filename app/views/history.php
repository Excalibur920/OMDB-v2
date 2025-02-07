<?php
    require_once "../controllers/movieController.php";
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $(".delete-movie").click(function() {
        let movieId = $(this).data("movie-id");
        console.log("Deleting movie ID:", movieId);
        // let row = $("#row-" + movieId);

        if (confirm("Are you sure you want to delete this movie?")) {


                
					$.ajax({
						type: "POST",
						url: "../controllers/delController.php",
						data: {
							movie_id: movieId,
							test_id: 'test'
						},
						cache: false,
						success: function(data) {
							alert(data);
						},
						error: function(xhr, status, error) {
							console.error(xhr);
						}
					});                

        
        }
    });
});
</script>

<!DOCTYPE html>
<?php
    $results= $movieModel->getallMovies();
?>
<html>
	<head>
		<link rel="stylesheet" href="../../stylesheet.css">
	</head>
</html>

<html>
<table>
    <?php
        echo "<tr>";
        echo "<th>Title</th>";
        echo "<th>Release Year</th>";     
        echo "<th>Summary</th>";                
        echo "<th>ImDB Rating</th>";                               
        echo "<th></th>";                                        
        echo "<tr>";
    ?>
    <tbody>
    <?php
        foreach($results as $result) {
            echo "<tr>";
            echo "<pre><td><form action='./details.php?movie_id=" . $result['movie_id'] . "' method='post'><button type='submit' name='title' value='" . $result['title'] . "' class='btn-link'>" . $result['title'] . "</button></form></td>";
            echo "<td>" . $result['year'] . "</td>";
            echo "<td>" . $result['plot'] . "</td>";
            echo "<td>" . $result['imdb_rating'] . "</td>";
            echo "<td>";
            echo "<button class='delete-movie' data-movie-id=" . $result['movie_id'] . ">Delete</button>";
            echo "</tr>";
        }
    ?>
    </tbody>
</table>

<div class="footer">
        <a href="./search.php" class="button">Search</a>
    </div>
</html>