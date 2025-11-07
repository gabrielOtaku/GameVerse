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

<body>
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

            <section class="mt-8 mb-12">
                <div id="main-carousel-container" class="relative">
                    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">

                        <div class="carousel-inner shadow-2xl rounded-xl border border-neon-cyan/50 overflow-hidden"
                            style="height: 700px; /* Augmenté à 700px pour plus d'esthétisme */">

                            <div class="carousel-item active" data-neon-color="#007bff"> <img src="IMG/ps5.png" class="d-block w-full h-full object-cover" alt="Exlusivite PS5">
                            </div>
                            <div class="carousel-item" data-neon-color="#dc3545">
                                <img src="IMG/xbox.png" class="d-block w-full h-full object-cover" alt="Nouvelle Xbox Series X">
                            </div>
                            <div class="carousel-item" data-neon-color="#ffc107">
                                <img src="IMG/Gaming.jpg" class="d-block w-full h-full object-cover" alt="Nos Jeux phrares">
                            </div>
                            <div class="carousel-item" data-neon-color="#6f42c1">
                                <img src="IMG/carrousel.jpg" class="d-block w-full h-full object-cover" alt="L'exclusiivite">
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

        </main>
    </section>


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