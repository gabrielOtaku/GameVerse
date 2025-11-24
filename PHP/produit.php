<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gameraddict";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

$sql = "SELECT idProduit, nomProduit, description, prix, imageNom, console, typeProduit FROM produit ORDER BY idProduit ASC";
$result = $conn->query($sql);

$products_from_db = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $platforms_array = explode(', ', $row['console']);
        // Simuler le stock 
        $stock = rand(0, 50);

        $products_from_db[] = [
            'id'          => $row['idProduit'],
            'name'        => $row['nomProduit'],
            'platforms'   => $platforms_array,
            'description' => $row['description'],
            'price'       => $row['prix'],
            'image'       => str_replace('../Media/', 'Media/', $row['imageNom']),
            'quantite'    => $stock,
            'type'        => $row['typeProduit']
        ];
    }
}

$products = $products_from_db;

$conn->close();

$products_json = json_encode($products);


function getPlatformIcon(string $platform): string
{
    $platform = trim($platform);

    $icons = [
        'PC' => 'fa-desktop',
        'PS5' => 'fa-playstation',
        'Xbox Series X' => 'fa-xbox',
        'Nintendo Switch' => 'fa-gamepad',
        'Xbox Series S' => 'fa-xbox',
        'VR' => 'fa-vr-cardboard'
    ];
    $iconClass = $icons[$platform] ?? 'fa-dice';
    $prefix = (in_array($platform, ['PS5', 'Xbox Series X', 'Xbox Series S'])) ? 'fab' : 'fas';
    return "<i class='{$prefix} {$iconClass}'></i>";
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TP Final - Gabriel Tom Sevrin</title>
    <link rel="icon" type="image/png" href="../IMG/GameVerse_Logo.png" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Open+Sans:wght@400;600&family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'neon-cyan': '#00ffcc',
                        'neon-pink': '#ff00aa',
                        'dark-bg': '#0a0a0a',
                        'card-bg': '#1e1e1e',
                        'error-red': '#ff4444',
                        'success-green': '#00ff7f',
                    },
                    fontFamily: {
                        orbitron: ['Orbitron', 'sans-serif'],
                        'open-sans': ['Open Sans', 'sans-serif'],
                        mono: ['Roboto Mono', 'monospace'],
                    },
                }
            }
        }
    </script>

    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/Produit.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/footer.css">

</head>

<body class="bg-dark-bg">

    <div id="page-preloader">
        <div class="loader-content">
            <img src="../IMG/GameVerse_Logo.png" alt="Logo de chargement" class="loading-logo">
            <div class="loading-bar">
                <div class="loading-progress"></div>
            </div>
            <p class="loading-text">Chargement de la Matrice...</p>
        </div>
    </div>
    <header>
        <?php include('../include/header.inc.php'); ?>
    </header>

    <section class="main-content min-h-screen pt-24 pb-8 bg-dark-bg">
        <main class="container mx-auto px-4 max-w-7xl">
            <h2 class="text-5xl font-orbitron text-center text-neon-cyan mb-6 uppercase tracking-widest text-shadow-neon">
                Catalogue des Produits
            </h2>

            <nav id="category-tabs" class="flex justify-center flex-wrap gap-4 mb-8 font-orbitron">
                <button class="category-btn active" data-category="Tout">
                    <i class="fas fa-dice-d20 mr-2"></i> Tout Voir
                </button>
                <button class="category-btn" data-category="Exclusivité">
                    <i class="fas fa-star mr-2"></i> Exclusivités
                </button>
                <button class="category-btn" data-category="Jeu">
                    <i class="fas fa-gamepad mr-2"></i> Jeux
                </button>
                <button class="category-btn" data-category="Console">
                    <i class="fas fa-microchip mr-2"></i> Consoles
                </button>
            </nav>


            <div id="search-result-count" class="text-center text-lg font-orbitron text-neon-cyan mb-8">
                <?= count($products) ?> produits affichés.
            </div>

            <div id="product-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">

                <?php if (empty($products)): ?>
                    <p class="col-span-full text-center text-gray-400 text-xl font-orbitron py-10">
                        Aucun produit trouvé dans le catalogue.
                    </p>
                <?php else: ?>
                    <?php foreach ($products as $product) : ?>

                        <div class="product-card bg-card-bg border-2 border-neon-cyan/50 rounded-2xl p-6 shadow-xl 
                                             hover:shadow-neon-cyan/80 transition-shadow duration-300 flex flex-col h-full"
                            data-category-type="<?= htmlspecialchars($product['type']) ?>"
                            data-tilt
                            data-tilt-max="5"
                            data-tilt-speed="400"
                            data-tilt-perspective="1000"
                            data-tilt-glare="true"
                            data-tilt-max-glare="0.3">

                            <img src="../Media/<?= htmlspecialchars($product['image']) ?>"
                                alt="<?= htmlspecialchars($product['name']) ?>"
                                class="w-full h-56 object-cover object-top rounded-lg mb-4 border border-gray-700 neon-image-border">

                            <h3 class="product-title text-2xl font-orbitron text-neon-cyan mb-2 text-shadow-title">
                                <?= htmlspecialchars($product['name']) ?>
                            </h3>

                            <div class="platforms mb-4 flex flex-wrap gap-2">
                                <?php foreach ($product['platforms'] as $platform) : ?>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-neon-cyan/20 text-neon-cyan border border-neon-cyan/70 shadow-neon-tag">
                                        <?= getPlatformIcon($platform) ?>
                                        <?= htmlspecialchars($platform) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>

                            <p class="product-description text-gray-300 text-sm mb-4 line-clamp-3 font-open-sans flex-grow">
                                <?= htmlspecialchars($product['description']) ?>
                            </p>

                            <p class="product-price text-3xl font-orbitron text-white mb-4 mt-auto">
                                $<span class="text-4xl"><?= number_format($product['price'], 2) ?></span> <span class="text-neon-cyan text-base">CAD</span>
                            </p>

                            <div class="mb-4 text-sm font-open-sans">
                                <?php
                                $stock_class = ($product['quantite'] > 10) ? 'text-success-green' : (($product['quantite'] > 0) ? 'text-yellow-500' : 'text-error-red');
                                $stock_message = ($product['quantite'] > 0) ? "Stock: " . htmlspecialchars($product['quantite']) . " unités" : "Rupture de stock";
                                ?>
                                <p class="<?= $stock_class ?> font-bold">
                                    <i class="fas fa-boxes"></i> <?= $stock_message ?>
                                </p>
                            </div>

                            <div class="flex flex-col gap-3">
                                <div class="flex items-center justify-between">
                                    <label for="qty-<?= $product['id'] ?>" class="text-gray-400 font-open-sans text-sm">Quantité:</label>
                                    <input type="number"
                                        id="qty-<?= $product['id'] ?>"
                                        value="1"
                                        min="1"
                                        max="<?= $product['quantite'] ?>"
                                        class="quantity-input w-20 p-2 rounded bg-dark-bg border-2 border-neon-cyan/50 text-center text-white font-mono focus:outline-none focus:ring-2 focus:ring-neon-cyan transition-all">
                                </div>

                                <button class="add-to-cart-btn w-full py-3 rounded-full bg-neon-cyan text-dark-bg font-orbitron uppercase tracking-wider transition-all duration-300 hover:bg-white hover:shadow-lg shadow-neon-btn"
                                    data-product-id="<?= $product['id'] ?>"
                                    data-product-name="<?= htmlspecialchars($product['name']) ?>"
                                    data-product-image="../Media/<?= htmlspecialchars($product['image']) ?>"
                                    data-product-stock="<?= $product['quantite'] ?>"
                                    onclick="addToCart(this)">
                                    <i class="fas fa-cart-plus mr-2"></i> Ajouter au Panier
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </main>
    </section>

    <div id="custom-message-box">
        <div class="message-content-wrapper">
            <img id="message-image" src="../Media/<?= htmlspecialchars($products[count($products) - 1]['image']) ?>" alt="Image du produit" class="message-product-image">
            <div class="message-text-container">
                <p class="confirmation-message">Ajouté au panier !</p>
                <div class="flex items-center">
                    <i class="fas fa-check-circle message-icon"></i>
                    <span id="message-text"></span>
                </div>
            </div>
        </div>
    </div>


    <footer>
        <?php include('../include/footer.inc.php'); ?>
    </footer>

    <script>
        const ALL_PRODUCTS_DATA = <?= $products_json ?>;

        document.addEventListener('DOMContentLoaded', () => {
            // Logique de filtrage des produits
            const categoryButtons = document.querySelectorAll('.category-btn');
            const resultCountElement = document.getElementById('search-result-count');

            function filterProducts(category) {
                // Rendre la sélection des cartes locale et dynamique
                const productCards = document.querySelectorAll('.product-card');
                let visibleCount = 0;

                productCards.forEach(card => {
                    // Récupère la valeur de l'attribut data-category-type
                    const cardCategory = card.getAttribute('data-category-type');

                    // La logique de filtrage 
                    const isVisible = (category === 'Tout' || cardCategory === category);

                    if (isVisible) {
                        card.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        card.classList.add('hidden');
                    }
                });

                // Mise à jour du compteur de résultats
                if (resultCountElement) {
                    let categoryText;
                    if (category === 'Tout') {
                        categoryText = 'toutes catégories';
                    } else if (category === 'Exclusivité') {
                        categoryText = 'Exclusivités';
                    } else {
                        categoryText = category + (category.endsWith('s') ? '' : 's');
                    }

                    resultCountElement.textContent = `${visibleCount} produit(s) affiché(s) dans ${categoryText}.`;
                }
            }

            // Gestion des Clics 
            categoryButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    const category = e.currentTarget.getAttribute('data-category');

                    categoryButtons.forEach(btn => btn.classList.remove('active'));
                    e.currentTarget.classList.add('active');

                    filterProducts(category);

                    // Si la recherche est vide, on retire l'état actif des boutons
                    const searchInput = document.getElementById("search-input");
                    if (searchInput && searchInput.value.trim() !== "") {
                        window.performProductSearch();
                    }
                });
            });

            filterProducts('Tout');
            document.querySelector('.category-btn[data-category="Tout"]')?.classList.add('active');
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../JS/monJS.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"
        integrity="sha512-wC/cunGGDjXSl9OHUH0RuqSyW4YNLlsPwhcLxwWW1CR4OeC2E1xpcdZz2DeQkEmums41laI+eGMw95IJ15SS3g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>

</html>