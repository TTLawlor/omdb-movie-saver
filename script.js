// const movieSearched = document.getElementById("searchedTitle");


async function searchMovies(movieTitle){
    const url  = `https://www.omdbapi.com/?s=${movieTitle}&apikey=a8b5a357`;
    const res = await fetch(`${url}`);
    const data = await res.json();
    console.log(data.Search);
    
}

searchMovies('lord of the rings');
