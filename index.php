<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
        <script defer src="script.js"></script>
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
                        <form action="">
                            <input type="text" id="searchedTitle" name="sTitle" placeholder="Search a movie...">
                            <input type="submit" value="search">
                        </form>
                    </div>
                </div>
            </section>
            <section class="movie">
                <div class="container">
                    <div class="content">
                        <div class="poster">
                            <img src="" alt="movie-poster">
                        </div>
                        <div class="movie-details">
                            <h2 class="movie-title">Title</h2>
                            <ul class="details">
                                <li class="year">Year: </li>
                                <li class="rating">Rating: </li>
                                <li class="released">Released: </li>
                                <li class="runtime">Runtime: </li>
                                <li class="genre">Genre: </li>
                                <li class="actors">Actors: </li>
                                <li class="language">Language: </li>
                                <li class="imdb">imdbRating :</li>
                            </ul>
                        </div>
                    </div>
                    <div class="extended">
                        <div class="content">
                            <div class="movie-plot">
                                <p class="plot">
                                    Movie plot
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <a target="_blank" href="https://icons8.com/icon/100010/documentary">movie camera</a> icon by <a target="_blank" href="https://icons8.com">Icons8</a>
    </body>
</html>