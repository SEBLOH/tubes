<?php
    $siteName = "Pokemon World";
    $pageTitle = "Koleksi Lengkap";
    $year = date("Y");
    $currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle; ?> - <?= $siteName; ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --pk-yellow: #ffcb05;
            --pk-blue: #2a75bb;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar { background-color: var(--pk-yellow); }
        
        .pokemon-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .pokemon-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        .type-badge {
            font-size: 0.8rem;
            text-transform: uppercase;
            font-weight: 600;
        }
        .search-container {
            margin-top: -30px;
            z-index: 10;
            position: relative;
        }
        .header-bg {
            background: linear-gradient(135deg, var(--pk-blue) 0%, #3c5aa6 100%);
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-warning sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-extrabold text-primary" href="index.php">
            <img src="https://upload.wikimedia.org/wikipedia/commons/9/98/International_Pok%C3%A9mon_logo.svg" alt="Logo" height="30" class="me-2">
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto text-center">
                <li class="nav-item">
                    <a class="nav-link text-primary fw-bold <?= ($currentPage == 'index.php') ? 'border-bottom border-primary border-2' : '' ?>" href="index.html">
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-primary fw-bold <?= ($currentPage == 'collection.php') ? 'border-bottom border-primary border-2' : '' ?>" href="collection.php">
                        Collection
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-primary fw-bold <?= ($currentPage == 'pokedex.php') ? 'border-bottom border-primary border-2' : '' ?>" href="pokedex.php">
                        Pokedex Guide
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<header class="header-bg">
    <div class="text-center">
        <h1 class="fw-bold">Pokedex Collection</h1>
        <p>Cari dan temukan Pokémon favoritmu!</p>
    </div>
</header>

<div class="container search-container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="input-group input-group-lg shadow-sm">
                <input type="text"
                       id="searchInput"
                       class="form-control border-0 rounded-start-pill ps-4"
                       placeholder="Cari nama Pokemon (contoh: Pikachu)..."
                       value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                <button class="btn btn-warning rounded-end-pill px-4 fw-bold text-primary" onclick="searchPokemon()">
                    Cari
                </button>
            </div>
        </div>
    </div>
</div>

<main class="container py-5">
    <div id="pokemon-grid" class="row g-4">
        <div class="text-center w-100 py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Mengambil data dari PokeAPI...</p>
        </div>
    </div>

    <div class="text-center mt-5">
        <button id="loadMore" class="btn btn-outline-primary btn-lg rounded-pill px-5">
            Load More Pokemon
        </button>
    </div>
</main>

<footer class="bg-dark text-white text-center py-4 mt-5">
    <p class="mb-0 small">
        © <?= $year; ?> <?= $siteName; ?> | Powered by PokeAPI
    </p>
</footer>

<script>
    const pokemonGrid = document.getElementById('pokemon-grid');
    const searchInput = document.getElementById('searchInput');
    let offset = 0;
    const limit = 12;

    async function fetchPokemons() {
        try {
            const response = await fetch(`https://pokeapi.co/api/v2/pokemon?limit=${limit}&offset=${offset}`);
            const data = await response.json();

            if(offset === 0) pokemonGrid.innerHTML = '';

            for (let pokemon of data.results) {
                const pokeDetail = await fetch(pokemon.url);
                const pokeData = await pokeDetail.json();
                renderPokemonCard(pokeData);
            }
            offset += limit;
        } catch (err) {
            console.error("Gagal load pokemon", err);
        }
    }

    function renderPokemonCard(pokemon) {
        const card = document.createElement('div');
        card.className = 'col-6 col-md-4 col-lg-3';
        card.innerHTML = `
            <div class="card pokemon-card shadow-sm h-100 p-3 text-center">
                <img src="${pokemon.sprites.other['official-artwork'].front_default}" 
                     class="img-fluid mx-auto" style="width:120px;" alt="${pokemon.name}">
                <p class="text-muted small mb-1">#${String(pokemon.id).padStart(3, '0')}</p>
                <h5 class="fw-bold text-capitalize mb-2">${pokemon.name}</h5>
                <div class="d-flex justify-content-center gap-1">
                    ${pokemon.types.map(t => `<span class="badge type-badge bg-primary opacity-75">${t.type.name}</span>`).join('')}
                </div>
            </div>
        `;
        pokemonGrid.appendChild(card);
    }

    async function searchPokemon() {
        const query = searchInput.value.toLowerCase().trim();
        if (!query) {
            offset = 0;
            document.getElementById('loadMore').style.display = 'inline-block';
            fetchPokemons();
            return;
        }

        pokemonGrid.innerHTML = `
            <div class="text-center w-100 py-5">
                <div class="spinner-border text-primary"></div>
            </div>
        `;

        try {
            const response = await fetch(`https://pokeapi.co/api/v2/pokemon/${query}`);
            if (!response.ok) throw new Error();
            const data = await response.json();
            pokemonGrid.innerHTML = '';
            renderPokemonCard(data);
            document.getElementById('loadMore').style.display = 'none';
        } catch {
            pokemonGrid.innerHTML = `
                <div class="text-center w-100 py-5">
                    <p class="text-danger">Pokemon tidak ditemukan! Coba nama lain.</p>
                </div>
            `;
        }
    }

    document.getElementById('loadMore').addEventListener('click', fetchPokemons);
    fetchPokemons();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
