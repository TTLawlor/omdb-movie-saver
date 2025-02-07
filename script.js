const movieSearched = document.getElementById('searchedTitle');
const movieList = document.getElementById('movie-list');


async function loadMoviesTitle(movieTitle){
    const url  = `https://www.omdbapi.com/?s=${movieTitle}&apikey=a8b5a357`;
    const res = await fetch(`${url}`);
    const data = await res.json();
    if(data.Search) {
        loadMoviesId(data.Search, false);
    } else {
        //display no movies matching message
        console.log('No movies found matching your search.');
        movieList.innerHTML = 'No movies found matching your search.';
    }
}


function findMovies() {
    let movieTitle = movieSearched.value;
    if(movieTitle) {
        loadMoviesTitle(movieTitle);
    } else {
        //display no movies matching message
        console.log('No movies found matching your search.');
        movieList.innerHTML = 'No movies found matching your search.';
    }
}

function findUserMovies() {
    const username = document.getElementById('searchedUsername').value;
    if (username) {
        fetch('find_user_movies.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ username: username }),
        })
        .then(response => {
            console.log(response); // Log the raw response
            return response.json(); // Get raw response text
        })
        .then(data => {
            if (Array.isArray(data)) {
                // Pass the movies data to loadMoviesId
                loadMoviesId(data, true);
            } else {
                console.error('Invalid data format');
            }
        })
        .catch(error => {
            console.error('Error fetching user movies:', error);
            movieList.innerHTML = 'No users found matching your search.';
        });
    }
}

async function loadMoviesId(movies, userSearched) {
    const movieList = document.getElementById('movie-list');
    movieList.innerHTML = "";  // Clear the movie list before displaying new movies
    const addedMovies = new Set();  // Set to track added movies by imdbID
    for (const movie of movies) {
        const url = `https://www.omdbapi.com/?i=${movie.imdbID}&apikey=a8b5a357`;
        const res = await fetch(url);
        const movieData = await res.json();
        
         // Check if movie data was successfully fetched
         if (movieData.Response === "True" && !addedMovies.has(movie.imdbID)) {
            displayMovieList(movieData, userSearched);
            addedMovies.add(movie.imdbID);  // Mark this movie as added
        } else {
            console.error(`Error fetching data for IMDb ID ${movie.imdbID}: ${movieData.Error}`);
        }
    }
}

function displayMovieList(movie, userSearched) {
    var movieContainer = document.createElement("div");
    movieContainer.dataset.id = movie.imdbID;
    movieContainer.classList.add('movie-container');
    movieContainer.innerHTML = `
        <div class="card">
            <h2 class="movie-title">${movie.Title}</h2>
            <div class="card-content">
                <div class="movie-details">
                    <ul class="details">
                        <li class="year">Year: ${movie.Year}</li>
                        <li class="rating">Rating: ${movie.imdbRating}</li>
                        <li class="genre">Genre: ${movie.Genre}</li>
                        <li class="imdb">IMDb Rating: ${movie.imdbRating}</li>
                    </ul>
                </div>
                <div class="movie-plot">
                    <p class="plot">${movie.Plot}</p>
                </div>
                ${userSearched ? '<button class="delete-movie-btn">Delete Movie</button>' : ''}
            </div>
        </div>`;
    
    movieList.appendChild(movieContainer);
    movieClickHandler(movieContainer);
    
    // Add the event listener for the delete button
    if (userSearched) {
        const deleteButton = movieContainer.querySelector('.delete-movie-btn');
        deleteButton.addEventListener('click', (event) => {
            event.stopPropagation(); // Stop the click event from bubbling up to movieContainer
            deleteMovie(movie, movieContainer);      // Call the deleteMovie function here
        });
    }
}

function movieClickHandler(movieContainer){
    movieContainer.addEventListener('click', async () => {
        console.log(movieContainer.dataset.id);
        var movieId = movieContainer.dataset.id;
        const url  = `https://www.omdbapi.com/?i=${movieId}&apikey=a8b5a357`;
        const res = await fetch(url);
        const movieData = await res.json();
        
        // Store movie data in localStorage
        localStorage.setItem('selectedMovie', JSON.stringify(movieData));

        // Navigate to new page
        document.location.href = "movie.php";
    });
}

function displayMovie(movie) {
    const movieExpand = document.getElementById('movie-expanded');
    movieExpand.innerHTML = "";
    var movieContainer = document.createElement("div");
    // console.log(movieContainer);
    movieContainer.classList.add('movie-container');
    if(movie.Poster != "N/A") {
        moviePoster = movie.Poster;
    }
    movieContainer.innerHTML = `
        <div class="content">
                        <div class="poster">
                            <img id="poster" src="" alt="movie-poster-not-found">
                        </div>
                        <div class="movie-details">
                            <h2 class="movie-title">${movie.Title}</h2>
                            <ul class="details">
                                <li class="year">Year: ${movie.Year}</li>
                                <li class="rating">Rating: ${movie.Rating}</li>
                                <li class="genre">Genre: ${movie.Genre}</li>
                                <li class="imdb">imdbRating: ${movie.imdbRating}</li>
                                <li class="released">Released: ${movie.Released}</li>
                                <li class="runtime">Runtime: ${movie.Runtime}</li>
                                <li class="actors">Actors: ${movie.Actors}</li>
                                <li class="language">Language: ${movie.Language}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="extended">
                        <div class="content">
                            <div class="movie-plot">
                                <p class="plot">
                                    ${movie.Plot}
                                </p>
                            </div>
                            <button class="save-movie-btn">Save Movie</button>
                        </div>
                    </div>`;
                movieExpand.appendChild(movieContainer);
                // movieSaveClickHandler();
                movieContainer.querySelector(".save-movie-btn").addEventListener("click", () => {
                    const username = prompt("Enter your username:");
                    if (username) {
                        saveMovie(movie, username);
                    } else {
                        alert("Username is required to save a movie.");
                    }
                });
}

function deleteMovie(movie, movieContainer) {
    console.log(`Deleting movie with ID: ${movie.imdbID}`);
    const deleteButton = movieContainer.querySelector('.delete-movie-btn');
    deleteButton.addEventListener('click', async () => {
        const confirmation = confirm(`Are you sure you want to delete ${movie.Title}?`);

        if (confirmation) {
            try {
                // Send a request to the server to delete the movie
                const response = await fetch('delete_movie.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        imdbID: movie.imdbID,  // Pass imdbID to identify the movie to delete
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Successfully deleted, remove the movie from the UI
                    movieContainer.remove();
                    alert('Movie deleted successfully!');
                } else {
                    alert('Failed to delete the movie. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while deleting the movie.');
            }
        }
    });
}

async function saveMovie(movie, username) {
    const movieData = {
        username: username,
        imdbID: movie.imdbID,
        title: movie.Title,
        year: movie.Year,
        rated: movie.Rated,
        released: movie.Released,
        runtime: movie.Runtime,
        plot: movie.Plot,
        language: movie.Language,
        poster: movie.Poster,
        imdbRating: movie.imdbRating,
        genres: movie.Genre ? movie.Genre.split(", ").map(g => g.trim()) : [],
        actors: movie.Actors ? movie.Actors.split(", ").map(a => a.trim()) : []
    };

    try {
        const response = await fetch("save_movie.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(movieData)
        });

        const text = await response.text(); // Get raw response text

        try {
            const data = JSON.parse(text); // Attempt to parse JSON
            alert(data.message);
        } catch (error) {
            console.error("Invalid JSON response:", text);
            alert("Unexpected response from server.");
        }
    } catch (error) {
        console.error("Error saving movie:", error);
        alert("Failed to save the movie.");
    }
}