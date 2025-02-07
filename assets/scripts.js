document.addEventListener("DOMContentLoaded", function () {
    loadMovies();

    document.getElementById("add-movie-form").addEventListener("submit", function (e) {
        e.preventDefault();
        addMovie();
    });
});

function loadMovies() {
    fetch('/controllers/MovieController.php')
        .then(response => response.json())
        .then(movies => {
            let movieList = document.getElementById("movie-list");
            movieList.innerHTML = "";
            movies.forEach(movie => {
                let li = document.createElement("li");
                li.innerHTML = `${movie.title} (${movie.year}) 
                    <button onclick="deleteMovie(${movie.movie_id})">Delete</button>`;
                movieList.appendChild(li);
            });
        });
}

function addMovie() {
    let movieData = {
        title: document.getElementById("title").value,
        year: document.getElementById("year").value,
        rated: document.getElementById("rated").value,
        released: document.getElementById("released").value,
        runtime: document.getElementById("runtime").value,
        plot: document.getElementById("plot").value,
        language: document.getElementById("language").value,
        poster: document.getElementById("poster").value,
        imdbrating: document.getElementById("imdbrating").value
    };

    fetch('/controllers/MovieController.php', {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(movieData)
    }).then(response => response.json())
      .then(data => {
          alert(data.message);
          loadMovies();
      });
}

function deleteMovie(id) {
    fetch('/controllers/MovieController.php', {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: id })
    }).then(response => response.json())
      .then(data => {
          alert(data.message);
          loadMovies();
      });
}
