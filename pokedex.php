<?php
$pageTitle = "Pokedex Guide - Pokemon World";
// API Source: https://pokeapi.co/api/v2/
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --pk-yellow: #ffcb05;
            --pk-blue: #2a75bb;
            --pk-dark-blue: #3c5aa6;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fdfdfd;
        }
        .navbar { background-color: var(--pk-yellow); }
        .guide-header {
            background: linear-gradient(135deg, var(--pk-blue) 0%, var(--pk-dark-blue) 100%);
            padding: 60px 0;
            color: white;
            text-align: center;
        }
        .fact-card {
            background: white;
            border-left: 5px solid var(--pk-yellow);
            transition: transform 0.3s ease;
        }
        .fact-card:hover { transform: scale(1.02); }
        footer { background: var(--pk-dark-blue); color: white; padding: 20px 0; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-extrabold text-primary" href="index.html">
                <img src="https://upload.wikimedia.org/wikipedia/commons/9/98/International_Pok%C3%A9mon_logo.svg" alt="Logo" height="30" class="me-2">
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto text-center">
                    <li class="nav-item"><a class="nav-link fw-bold text-primary" href="index.html">Home</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold text-primary" href="collection.php">Collection</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold text-primary border-bottom border-primary border-2" href="pokedex.php">Pokedex Guide</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="guide-header shadow-sm">
        <div class="container">
            <h1 class="display-5 fw-bold">Pokedex Battle Guide</h1>
            <p class="lead opacity-75">Pahami kelemahan lawan dan kuasai setiap pertarungan!</p>
        </div>
    </header>

    <main class="container py-5">
        <div class="row g-4">
            
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <h3 class="fw-bold text-primary mb-4">Type Effectiveness Table</h3>
                    <p class="text-muted small mb-4">Gunakan panduan ini untuk mengetahui tipe mana yang memberikan damage lebih besar (2x) atau lebih kecil (0.5x).</p>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tipe</th>
                                    <th>Kuat Melawan (2x)</th>
                                    <th>Lemah Terhadap (0.5x)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-danger rounded-pill px-3 py-2">Fire</span></td>
                                    <td>Grass, Ice, Bug, Steel</td>
                                    <td>Water, Ground, Rock</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary rounded-pill px-3 py-2">Water</span></td>
                                    <td>Fire, Ground, Rock</td>
                                    <td>Grass, Electric</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success rounded-pill px-3 py-2">Grass</span></td>
                                    <td>Water, Ground, Rock</td>
                                    <td>Fire, Ice, Poison, Flying, Bug</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-warning text-dark rounded-pill px-3 py-2">Electric</span></td>
                                    <td>Water, Flying</td>
                                    <td>Ground, Grass, Dragon</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-info text-dark rounded-pill px-3 py-2">Ice</span></td>
                                    <td>Grass, Ground, Flying, Dragon</td>
                                    <td>Fire, Water, Ice, Steel</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 fact-card d-flex flex-column">
                    <h4 class="fw-bold text-primary mb-3">💡 Pokemon Lore</h4>
                    <p class="text-muted small mb-4">Fakta unik dari Pokedex resmi:</p>
                    
                    <div id="pokemon-fact-content" class="text-center my-auto">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>

                    <button class="btn btn-primary w-100 rounded-pill mt-4 fw-bold shadow-sm" onclick="fetchRandomLore()">
                        Acak Pokemon Lain
                    </button>
                </div>
            </div>

        </div>
    </main>

    <footer class="text-center mt-5">
        <div class="container">
            <p class="mb-0 small">© <?php echo date("Y"); ?> Pokemon World — Powered by PokeAPI</p>
        </div>
    </footer>

    <script>
        async function fetchRandomLore() {
            const content = document.getElementById('pokemon-fact-content');
            content.innerHTML = '<div class="spinner-border text-primary" role="status"></div>';
            
            const randomId = Math.floor(Math.random() * 151) + 1;
            
            try {
                // Endpoint PokeAPI v2
                const response = await fetch(`https://pokeapi.co/api/v2/pokemon-species/${randomId}/`);
                const data = await response.json();
                
                const pokeResponse = await fetch(`https://pokeapi.co/api/v2/pokemon/${randomId}/`);
                const pokeData = await pokeResponse.json();
                
                const loreEntry = data.flavor_text_entries.find(entry => entry.language.name === 'en');
                const cleanLore = loreEntry ? loreEntry.flavor_text.replace(/[\f\n\r]/g, ' ') : "No data available.";

                content.innerHTML = `
                    <img src="${pokeData.sprites.other['official-artwork'].front_default}" class="img-fluid mb-3" style="width: 120px;">
                    <h5 class="fw-bold text-capitalize text-primary mb-2">${data.name}</h5>
                    <p class="fst-italic text-secondary small">"${cleanLore}"</p>
                `;
            } catch (error) {
                content.innerHTML = '<p class="text-danger">Gagal memuat data lore.</p>';
            }
        }

        document.addEventListener('DOMContentLoaded', fetchRandomLore);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>