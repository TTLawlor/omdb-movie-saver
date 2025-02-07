
USE movie_db;

-- Users Table (For storing usernames)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL
);

-- Movies Table (Stores unique movie details)
CREATE TABLE movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imdbID VARCHAR(50) UNIQUE NOT NULL,
    title VARCHAR(255) NOT NULL,
    year VARCHAR(10),
    rated VARCHAR(20),
    released DATE,
    runtime VARCHAR(20),
    plot TEXT,
    language VARCHAR(255),
    poster BLOB,
    imdbRating DECIMAL(3,1)
);

-- Saved Movies Table (Links users to movies)
CREATE TABLE saved_movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    movie_id INT NOT NULL,
    saved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
);

-- Genres Table (Stores movie genres)
CREATE TABLE genres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT NOT NULL,
    genre_name VARCHAR(255),
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
);

-- Actors Table (Stores movie actors)
CREATE TABLE actors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT NOT NULL,
    actor_name VARCHAR(255),
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
);
