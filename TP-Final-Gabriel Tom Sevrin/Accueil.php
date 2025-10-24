<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Open+Sans:wght@400;600&family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!--CSS-->
    <link rel="stylesheet" href="CSS/monCSS.css">
    <!--Creer un fichhier Accueil.CSS pour styliser la page d'accueil (*Ne pas oublier de le mettre dans e dossier CSS pour l'organisation)-->
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <title>TP Final - Gabriel Tom Sevrin</title>
    <!--Icone Onglet-->
    <link rel="icon" type="image/png" href="IMG/GameVerse_Logo.png" />
</head>

<body>

    <Header>
        <?php include('include/head.inc.php'); ?>
    </Header>

    <section class=" main-content ">
        <main>

            <!-- Contenu principal de la page -->
            <!-- Titre de la page -->

            <!--Carroussel qu'on va styliser avec du css et du js-->
            <div id="carouselExampleIndicators" class="carousel slide">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="IMG/ps5.png" class="d-block w-100" alt="Exlusivite PS5">
                    </div>
                    <div class="carousel-item">
                        <img src="IMG/xbox.png" class="d-block w-100" alt="Nouvelle Xbox Series X">
                    </div>
                    <div class="carousel-item">
                        <img src="IMG/Gaming.jpg" class="d-block w-100" alt="Nos Jeux phrares">
                    </div>
                    <div class="carousel-item">
                        <img src="IMG/carrousel.jpg" class="d-block w-100" alt="L'exclusiivite">
                    </div>
                </div>

                <!-- Icône de navigation Gauche et droit -->
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            <!-- Initialisation d'un canva 3D, Il sera l'affiche de la page *utiliser la balise canva-->


        </main>
    </section>


    <footer>
        <?php include('include/footer.inc.php'); ?>
    </footer>

    <!--Bootstrap JS et Three.js-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/examples/js/controls/OrbitControls.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/examples/js   /loaders/GLTFLoader.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/examples/js/loaders/DRACOLoader.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/examples/js   /loaders/RGBELoader.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/examples/js   /pmrem/PMREMGenerator.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/examples/js   /pmrem/PMREMCubeUVPacker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/examples/js/pmrem/PMREMCubeUVPacker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/examples/js   /pmrem/PMREMGenerator.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/examples/js   /pmrem/PMREMCubeUVPacker.min.js"></script>
    <script src="JS/monJS.js"></script>

    <!--Script pour l'effet 3D des icônes-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"
        integrity="sha512-wC/cunGGDjXSl9OHUH0RuqSyW4YNLlsPwhcLxwWW1CR4OeC2E1xpcdZz2DeQkEmums41laI+eGMw95IJ15SS3g=="

        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>