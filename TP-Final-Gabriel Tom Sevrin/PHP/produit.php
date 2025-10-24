<?php

$products = [
    [
        'id' => 1,
        'name' => 'Cybernetic Dawn',
        'platforms' => ['PC', 'PS5'],
        'description' => 'Un RPG futuriste en monde ouvert où les choix moraux décident de l\'avenir de la cité néon.',
        'price' => 79.99,
        'image' => '../Media/Jeux1.png',
    ],
    [
        'id' => 2,
        'name' => 'Ancient Echoes',
        'platforms' => ['Xbox Series X', 'PC'],
        'description' => 'Un jeu d\'aventure et de mystère au cœur de ruines mayas, avec des énigmes complexes et des combats épiques.',
        'price' => 59.99,
        'image' => '../Media/Jeux2.png',
    ],
    [
        'id' => 3,
        'name' => 'Starfall Tactics',
        'platforms' => ['PC'],
        'description' => 'Jeu de stratégie spatiale en temps réel. Construisez votre flotte de combat pour conquérir la galaxie.',
        'price' => 44.50,
        'image' => '../Media/Jeux3.png',
    ],
    [
        'id' => 4,
        'name' => 'Mech Arena',
        'platforms' => ['PS5', 'Xbox Series X'],
        'description' => 'Contrôlez de puissants Mechs de combat dans des arènes de destruction, un must pour les fans de robotique.',
        'price' => 69.99,
        'image' => '../Media/Jeux4.png',
    ],
    [
        'id' => 5,
        'name' => 'Phantom Drift',
        'platforms' => ['PC', 'Nintendo Switch'],
        'description' => 'Un jeu de course anti-gravité ultra-rapide avec des tracés néons et une physique exigeante.',
        'price' => 39.99,
        'image' => '../Media/Jeux5.png',
    ],
];

/**
 * Fonction utilitaire pour obtenir l'icône Font Awesome pour une plateforme.
 */
function getPlatformIcon(string $platform): string
{
    $icons = [
        'PC' => 'fa-desktop',
        'PS5' => 'fa-playstation',
        'Xbox Series X' => 'fa-xbox',
        'Nintendo Switch' => 'fa-gamepad',
    ];
    // Retourne l'icône si elle existe, sinon retourne un icône de jeu générique
    $iconClass = $icons[$platform] ?? 'fa-dice';
    return "<i class='fab {$iconClass}'></i>";
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


    <!--Tailwind-->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'neon-cyan': '#00ffcc',
                        'dark-bg': '#0a0a0a',
                        'card-bg': '#1e1e1e',
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

    <link rel="stylesheet" href="../CSS/monCSS.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/footer.css">

</head>

<body class="bg-dark-bg">
    <header>
        <?php include('../include/header.inc.php'); ?>
    </header>

    <section class="main-content min-h-screen py-16">
        <main class="container mx-auto px-4 max-w-7xl">
            <h2 class="text-5xl font-orbitron text-center text-neon-cyan mb-6 uppercase tracking-widest text-shadow-neon">
                Catalogue des Jeux
            </h2>
            <p class="text-center text-gray-400 mb-12 font-open-sans max-w-2xl mx-auto">
                Explorez notre sélection de jeux futuristes, de blockbusters et de classiques remis au goût du jour.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">

                <?php foreach ($products as $product) : ?>

                    <div class="product-card bg-card-bg border-2 border-neon-cyan/50 rounded-2xl p-6 shadow-xl 
                                hover:shadow-neon-cyan/80 transition-shadow duration-300 flex flex-col h-full"
                        data-tilt
                        data-tilt-max="5"
                        data-tilt-speed="400"
                        data-tilt-perspective="1000"
                        data-tilt-glare="true"
                        data-tilt-max-glare="0.3">

                        <img src="<?= htmlspecialchars($product['image']) ?>"
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

                        <div class="flex flex-col gap-3">
                            <div class="flex items-center justify-between">
                                <label for="qty-<?= $product['id'] ?>" class="text-gray-400 font-open-sans text-sm">Quantité:</label>
                                <input type="number"
                                    id="qty-<?= $product['id'] ?>"
                                    value="1"
                                    min="1"
                                    class="quantity-input w-20 p-2 rounded bg-dark-bg border-2 border-neon-cyan/50 text-center text-white font-mono focus:outline-none focus:ring-2 focus:ring-neon-cyan transition-all">
                            </div>

                            <button class="add-to-cart-btn w-full py-3 rounded-full bg-neon-cyan text-dark-bg font-orbitron uppercase tracking-wider transition-all duration-300 hover:bg-white hover:shadow-lg shadow-neon-btn"
                                data-product-id="<?= $product['id'] ?>"
                                data-product-name="<?= htmlspecialchars($product['name']) ?>"
                                onclick="addToCart(this)">
                                <i class="fas fa-cart-plus mr-2"></i> Ajouter au Panier
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </main>
    </section>

    <div id="custom-message-box" class="fixed p-4 rounded-lg shadow-2xl bg-card-bg border-2 border-neon-cyan/80 pointer-events-none transition-all duration-300 opacity-0 z-50 transform translate-x-1/2 right-1/2 bottom-10">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-neon-cyan text-xl mr-3"></i>
            <span id="message-text" class="text-white font-open-sans"></span>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"></script>

    <script src="../JS/monJS.js"></script>


    <footer>
        <?php include('../include/footer.inc.php'); ?>
    </footer>
</body>

</html>