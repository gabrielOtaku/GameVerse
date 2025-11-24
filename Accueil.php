<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Open+Sans:wght@400;600&family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/Accueil.css">
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/footer.css">

    <title>TP Final - Gabriel Tom Sevrin</title>
    <link rel="icon" type="image/png" href="IMG/GameVerse_Logo.png" />

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
</head>

<body class="bg-dark-bg">
    <div id="page-preloader">
        <div class="loader-content">
            <img src="IMG/GameVerse_Logo.png" alt="Logo de chargement" class="loading-logo">
            <div class="loading-bar">
                <div class="loading-progress"></div>
            </div>
            <p class="loading-text">Chargement de la Matrice...</p>
        </div>
    </div>

    <Header>
        <?php include('include/head.inc.php'); ?>
    </Header>

    <section class="main-content min-h-screen pt-24 pb-8 bg-dark-bg">
        <main class="container mx-auto px-4 max-w-7xl">

            <div class="scrolling-banner bg-neon-cyan/10 border-y-2 border-neon-cyan/50 py-3 mb-12 shadow-neon-lg">
                <div class="scrolling-text text-xl md:text-2xl font-orbitron font-bold text-neon-cyan whitespace-nowrap">
                    <span class="text-neon-pink">⭐ Note moyenne : 4.8/5 (Basée sur 1200 avis) | </span>
                    <?php
                    $reviews = [
                        "💬 Le meilleur service client ! - Alice",
                        "🎮 Livraison ultra-rapide, produit parfait ! - Bob",
                        "✨ Site incroyable et prix compétitifs ! - Eve",
                        "🤖 Nexus AI est très utile ! - Charlie",
                        "💻 J'ai trouvé mon GPU ! - David"
                    ];
                    $review_string = ' | ' . implode(' | ', $reviews) . ' | ';
                    echo str_repeat($review_string, 4);
                    ?>
                </div>
            </div>


            <section class="mt-8 mb-12">
                <div id="main-carousel-container" class="relative">
                    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">

                        <div class="carousel-inner shadow-2xl rounded-xl border border-neon-cyan/50 overflow-hidden"
                            style="height: 700px;">

                            <div class="carousel-item active" data-bs-interval="5000" data-neon-color="#007bff">
                                <img src="IMG/ps5.png" class="d-block w-full h-full object-cover" alt="Exclusivite PS5">
                                <div class="carousel-overlay bg-black/60 p-5">
                                    <h3 class="text-3xl font-orbitron text-neon-cyan mb-2">Playstation 5</h3>
                                    <p class="text-white text-lg font-open-sans">Découvrez l'avenir du jeu avec la console next-gen. Stock limité, commandez la vôtre maintenant !</p>
                                </div>
                            </div>
                            <div class="carousel-item" data-bs-interval="5000" data-neon-color="#dc3545">
                                <img src="IMG/xbox.png" class="d-block w-full h-full object-cover" alt="Nouvelle Xbox Series X">
                                <div class="carousel-overlay bg-black/60 p-5">
                                    <h3 class="text-3xl font-orbitron text-neon-cyan mb-2">Xbox Series X</h3>
                                    <p class="text-white text-lg font-open-sans">La puissance brute est entre vos mains. Des performances 4K et un catalogue de jeux exceptionnel.</p>
                                </div>
                            </div>
                            <div class="carousel-item" data-bs-interval="5000" data-neon-color="#ffc107">
                                <img src="IMG/Gaming.jpg" class="d-block w-full h-full object-cover" alt="Nos Jeux phares">
                                <div class="carousel-overlay bg-black/60 p-5">
                                    <h3 class="text-3xl font-orbitron text-neon-cyan mb-2">Les Jeux du Moment</h3>
                                    <p class="text-white text-lg font-open-sans">Plongez dans nos titres phares avec des réductions exclusives pour nos membres premium.</p>
                                </div>
                            </div>
                            <div class="carousel-item" data-bs-interval="5000" data-neon-color="#6f42c1">
                                <img src="IMG/carrousel.jpg" class="d-block w-full h-full object-cover" alt="L'exclusiivite">
                                <div class="carousel-overlay bg-black/60 p-5">
                                    <h3 class="text-3xl font-orbitron text-neon-cyan mb-2">Accès Anticipé</h3>
                                    <p class="text-white text-lg font-open-sans">Précommandez et jouez avant tout le monde ! Des éditions collectors limitées disponibles.</p>
                                </div>
                            </div>

                        </div>

                        <div id="carousel-thumbnails"
                            class="absolute bottom-4 left-1/2 transform -translate-x-1/2 hidden lg:flex flex-row gap-3 z-10 p-2 rounded-xl bg-dark-bg/50 backdrop-blur-sm">

                            <img src="IMG/ps5.png" class="thumbnail-item active" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" alt="Miniature 1">
                            <img src="IMG/xbox.png" class="thumbnail-item" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" alt="Miniature 2">
                            <img src="IMG/Gaming.jpg" class="thumbnail-item" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" alt="Miniature 3">
                            <img src="IMG/carrousel.jpg" class="thumbnail-item" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" alt="Miniature 4">

                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>

                    </div>
                </div>
            </section>

            <section class="my-12 p-8 bg-card-bg rounded-xl shadow-2xl border border-neon-cyan/30">
                <h2 class="text-4xl font-orbitron font-bold text-center text-neon-cyan mb-6 border-b-2 border-neon-cyan/50 pb-3">
                    L'Histoire de GameVerse
                </h2>
                <div class="text-lg text-gray-300 font-open-sans space-y-4">
                    <p>
                        Bienvenue chez **GameVerse**, votre portail vers les mondes numériques. Fondé sur la passion du jeu vidéo et de la technologie, notre magasin est devenu une référence grâce à l'expertise et à la vision de ses fondateurs.
                    </p>
                    <p>
                        Notre équipe de direction est composée de :
                    </p>
                    <ul class="list-disc list-inside ml-4 text-white">
                        <li>
                            <strong class="text-neon-cyan">Gabriel HERVE</strong> : **Président-Directeur Général (PDG)**. Visionnaire et stratège, Gabriel est l'âme de GameVerse. Il s'assure que l'entreprise reste à la pointe de l'innovation et offre une expérience client inégalée.
                        </li>
                        <li>
                            <strong class="text-neon-cyan">Tom Rujaco</strong> : **Directeur Informatique et Technologie**. Tom est notre génie technique. Il est responsable de la plateforme en ligne, de la sécurité des données et de l'intégration des dernières technologies pour une navigation fluide et immersive.
                        </li>
                        <li>
                            <strong class="text-neon-cyan">Sevrin Merlotti</strong> : **Manager des Ventes**. Expert en relation client, Sevrin pilote nos stratégies commerciales. Son objectif est de vous apporter les meilleurs produits aux meilleurs prix, tout en garantissant un service après-vente de qualité.
                        </li>
                    </ul>
                    <p>
                        Ensemble, nous nous engageons à construire non seulement un magasin, mais une véritable **communauté** pour tous les passionnés de jeux vidéo.
                    </p>
                </div>
            </section>

            <section class="my-12 p-8 bg-card-bg rounded-xl shadow-2xl border border-neon-pink/30">
                <h2 class="text-4xl font-orbitron font-bold text-center text-neon-pink mb-6 border-b-2 border-neon-pink/50 pb-3">
                    Nos Partenaires Officiels
                </h2>
                <div class="flex flex-wrap justify-center items-center gap-8 md:gap-12 py-4">
                    <div class="partner-logo text-center text-gray-300 hover:text-white transition duration-300 transform hover:scale-105">
                        <i class="fas fa-gamepad text-6xl text-neon-cyan mb-2"></i>
                        <p class="font-orbitron text-sm">GameOne</p>
                    </div>
                    <div class="partner-logo text-center text-gray-300 hover:text-white transition duration-300 transform hover:scale-105">
                        <i class="fas fa-tower-broadcast text-6xl text-neon-pink mb-2"></i>
                        <p class="font-orbitron text-sm">JapanExpo Paris</p>
                    </div>
                    <div class="partner-logo text-center text-gray-300 hover:text-white transition duration-300 transform hover:scale-105">
                        <i class="fas fa-chess-rook text-6xl text-neon-cyan mb-2"></i>
                        <p class="font-orbitron text-sm">Château Frontenac</p>
                    </div>
                    <div class="partner-logo text-center text-gray-300 hover:text-white transition duration-300 transform hover:scale-105">
                        <i class="fas fa-globe-europe text-6xl text-neon-pink mb-2"></i>
                        <p class="font-orbitron text-sm">Gamescom Cologne</p>
                    </div>
                    <div class="partner-logo text-center text-gray-300 hover:text-white transition duration-300 transform hover:scale-105">
                        <i class="fas fa-compact-disc text-6xl text-neon-cyan mb-2"></i>
                        <p class="font-orbitron text-sm">E3 Los Angeles</p>
                    </div>
                    <div class="partner-logo text-center text-gray-300 hover:text-white transition duration-300 transform hover:scale-105">
                        <i class="fas fa-house-laptop text-6xl text-neon-pink mb-2"></i>
                        <p class="font-orbitron text-sm">Tokyo Game Show</p>
                    </div>
                </div>
            </section>
        </main>
    </section>

    <div id="side-cart" class="fixed top-0 right-0 h-full w-80 bg-card-bg shadow-2xl z-50 transform translate-x-full transition-transform duration-500 border-l border-neon-cyan/50">
        <div class="flex justify-between items-center p-4 border-b border-neon-cyan/50">
            <h3 class="text-2xl font-orbitron text-neon-cyan">Mon Panier 🛒</h3>
            <button id="close-side-cart-btn" class="text-neon-cyan hover:text-white transition-colors duration-300">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="cart-content" class="p-4 overflow-y-auto h-[calc(100vh-160px)] space-y-4">
            <p class="text-gray-400 text-center mt-10">Chargement du panier...</p>
        </div>
        <div class="absolute bottom-0 w-full p-4 border-t border-neon-cyan/50 bg-card-bg/95 backdrop-blur-sm">
            <div class="flex justify-between font-orbitron text-xl mb-3">
                <span class="text-white">Total:</span>
                <span id="cart-total" class="text-neon-cyan">$0.00</span>
            </div>
            <a href="PHP/paiement.php" class="block w-full text-center py-3 rounded-lg bg-neon-cyan text-dark-bg font-bold hover:bg-white transition-colors duration-300">
                Passer à la Caisse
            </a>
        </div>
    </div>

    <footer>
        <?php include('include/footer.inc.php'); ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
        integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
    <script src="JS/monJS.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"
        integrity="sha512-wC/cunGGDjXSl9OHUH0RuqSyW4YNLlsPwhcLxwWW1CR4OeC2E1xpcdZz2DeQkEmums41laI+eGMw95IJ15SS3g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>