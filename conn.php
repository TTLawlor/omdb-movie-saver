<?php

// Database connection
$host = "localhost";
$user = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "movie_db";

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["message" => "Database connection failed: " . $conn->connect_error]));
}

// Function to get user ID from username
function getUserIdByUsername($username) {
    global $conn;
    $user_check_query = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($user_check_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user_result = $stmt->get_result();
    
    if ($user_result->num_rows == 0) {
        return false; // User not found
    }
    $user_row = $user_result->fetch_assoc();
    return $user_row['id'];
}

// Function to get movie details by IMDb ID
function getMovieByImdbID($imdbID) {
    global $conn;
    $movie_check_query = "SELECT id, title, year, imdbRating, plot FROM movies WHERE imdbID = ?";
    $stmt = $conn->prepare($movie_check_query);
    $stmt->bind_param("s", $imdbID);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
?>
