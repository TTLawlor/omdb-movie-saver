<?php
// Include the database connection and helper functions
include 'conn.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);


header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);
// Log the received data
error_log(print_r($data, true));
if (!isset($data["username"])) {
    echo json_encode(["message" => "Invalid data."]);
    exit;
}

$username = $data["username"];

// Get user ID
$user_id = getUserIdByUsername($username);


if ($user_id === false) {
    echo json_encode(["message" => "User not found."]);
    $stmt->close();
    $conn->close();
    exit;
}


if ($user_id === false) {
    echo json_encode(["message" => "User not found."]);
    $stmt->close();
    $conn->close();
    exit;
}

// Fetch saved movies for the user
$saved_movies_query = "
    SELECT movies.id, movies.imdbID, movies.title, movies.year, movies.imdbRating, movies.plot, genres.genre_name
    FROM saved_movies
    JOIN movies ON saved_movies.movie_id = movies.id
    JOIN genres ON movies.id = genres.movie_id
    WHERE saved_movies.user_id = ?
";

$stmt = $conn->prepare($saved_movies_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$movies_result = $stmt->get_result();

$movies = [];
while ($row = $movies_result->fetch_assoc()) {
    $movies[] = $row;
}
error_log(print_r($movies, true)); // Log movie data
echo json_encode($movies);

$stmt->close();
$conn->close();
?>
