<?php
// Include the database connection and helper functions
include 'conn.php';

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["username"]) || !isset($data["imdbID"])) {
    echo json_encode(["message" => "Invalid data."]);
    exit;
}

$username = $data["username"];
$imdbID = $data["imdbID"];
$title = $data["title"];
$year = $data["year"];
$rated = $data["rated"];
$released = $data["released"];
$runtime = $data["runtime"];
$plot = $data["plot"];
$language = $data["language"];
$poster = $data["poster"];
$imdbRating = $data["imdbRating"];
$genres = $data["genres"]; // Array of genres
$actors = $data["actors"]; // Array of actors

// Get or create user ID
$user_id = getUserIdByUsername($username);

if ($user_id === false) {
    $insert_user = "INSERT INTO users (username) VALUES (?)";
    $stmt = $conn->prepare($insert_user);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user_id = $stmt->insert_id;
}

// Check if movie already exists
$movie_row = getMovieByImdbID($imdbID);
if (!$movie_row) {
    // Insert new movie
    $insert_movie = "INSERT INTO movies (imdbID, title, year, rated, released, runtime, plot, language, poster, imdbRating)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_movie);
    $stmt->bind_param("sssssssssd", $imdbID, $title, $year, $rated, $released, $runtime, $plot, $language, $poster, $imdbRating);
    $stmt->execute();
    $movie_id = $stmt->insert_id;
} else {
    $movie_id = $movie_row["id"];
}

// Insert genres
$insert_genre = "INSERT INTO genres (movie_id, genre_name) VALUES (?, ?)";
$stmt = $conn->prepare($insert_genre);
foreach ($genres as $genre) {
    $stmt->bind_param("is", $movie_id, $genre);
    $stmt->execute();
}

// Insert actors
$insert_actor = "INSERT INTO actors (movie_id, actor_name) VALUES (?, ?)";
$stmt = $conn->prepare($insert_actor);
foreach ($actors as $actor) {
    $stmt->bind_param("is", $movie_id, $actor);
    $stmt->execute();
}

// Check if the user already saved this movie
$saved_movie_check = "SELECT id FROM saved_movies WHERE user_id = ? AND movie_id = ?";
$stmt = $conn->prepare($saved_movie_check);
$stmt->bind_param("ii", $user_id, $movie_id);
$stmt->execute();
$saved_result = $stmt->get_result();

if ($saved_result->num_rows == 0) {
    // Save movie for the user
    $insert_saved_movie = "INSERT INTO saved_movies (user_id, movie_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_saved_movie);
    $stmt->bind_param("ii", $user_id, $movie_id);
    $stmt->execute();
    echo json_encode(["message" => "Movie saved successfully!"]);
} else {
    echo json_encode(["message" => "Movie already saved."]);
}

$stmt->close();
$conn->close();
?>
