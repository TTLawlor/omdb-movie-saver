<?php
// Include the database connection
include 'conn.php';

header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["imdbID"])) {
    echo json_encode(["success" => false, "message" => "Invalid data."]);
    exit;
}

$imdbID = $data["imdbID"];

// Get the movie ID from the IMDb ID
$query = "SELECT id FROM movies WHERE imdbID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $imdbID);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();

if (!$movie) {
    echo json_encode(["success" => false, "message" => "Movie not found."]);
    $stmt->close();
    $conn->close();
    exit;
}

$movieId = $movie["id"];

// Remove the movie from the saved_movies table
$deleteQuery = "DELETE FROM saved_movies WHERE movie_id = ?";
$deleteStmt = $conn->prepare($deleteQuery);
$deleteStmt->bind_param("i", $movieId);
$deleteStmt->execute();

if ($deleteStmt->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "Movie deleted successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to delete movie."]);
}

$deleteStmt->close();
$stmt->close();
$conn->close();
?>
