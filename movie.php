<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="style.css?v=1">
        <script defer src="script.js" type="text/javascript"></script>
    </head>
    <body>
        <header id="nav">
            <div class="container">
                <a class="logo" href="index.php">
                    <img src="icons8-movie-camera-30.png" alt="logo">
                </a>
            </div>
        </header>
        <main>
            <section class="search">
                <div class="container">
                    <div class="content">
                    <form action="javascript:;" onsubmit="findMovies()">
                            <input type="text" id="searchedTitle" name="sTitle" placeholder="Search a movie...">
                            <input type="submit" value="search">
                        </form>
                    </div>
                </div>
            </section>
            <section class="movie" id="movie-expanded">
                
            </section>
            <section class="save">
                
                <div id="save-message"></div> <!-- This will show success/error messages -->
            </section>
        </main>
        <footer>
            <div class="container">
            <a target="_blank" href="https://icons8.com/icon/100010/documentary">movie camera</a> icon by <a target="_blank" href="https://icons8.com">Icons8</a>
            </div>
        </footer>
    </body>
    <script>
    
    document.addEventListener('DOMContentLoaded', function () {
    const movieExpand = document.getElementById('movie-expanded');
    const movieData = JSON.parse(localStorage.getItem('selectedMovie'));

    if (!movieData) {
        movieExpand.innerHTML = "<p>No movie data found.</p>";
        return;
    }

    displayMovie(movieData);

    // // Attach event listener to save button
    // document.getElementById('save-movie-btn').addEventListener('click', function () {
    //     saveMovie(movieData);
    // });
});

</script>
</html>