<?php
// Configuration de la connexion à la base de données (si nécessaire)
// Laissez ce bloc de connexion si vous prévoyez d'intégrer l'envoi du formulaire à une BD
/*
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gameraddict";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}
$conn->close();
*/
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - GameVerse</title>
    <link rel="icon" type="image/png" href="../IMG/GameVerse_Logo.png" />

    <!-- Polices -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'neon-cyan': '#00ffcc',
                        'dark-bg': '#000000',
                        'card-bg': '#1a1a1a',
                    },
                    fontFamily: {
                        orbitron: ['Orbitron', 'sans-serif'],
                        'open-sans': ['Open Sans', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- CSS du Site -->
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/footer.css">
    <link rel="stylesheet" href="../CSS/Contact.css">

</head>

<body class="bg-dark-bg">
    <!-- Écran de Chargement (Preloader) -->
    <div id="page-preloader">
        <div class="loader-content">
            <!-- Logo du site (optionnel, si vous voulez le voir au centre) -->
            <img src="../IMG/GameVerse_Logo.png" alt="Logo de chargement" class="loading-logo">
            <!-- L'animation visuelle de chargement -->
            <div class="loading-bar">
                <div class="loading-progress"></div>
            </div>
            <p class="loading-text">Chargement de la Matrice...</p>
        </div>
    </div>

    <header>
        <?php include('../include/header.inc.php'); ?>
    </header>

    <section class="main-content min-h-screen">
        <main>
            <form action="#" method="POST" id="contact-form">

                <h2 class="font-orbitron">
                    <i class="fas fa-headset mr-2"></i> Contactez la Matrice
                </h2>
                <p class="text-gray-400 mb-8">
                    Votre requête sera traitée par notre IA de support. Veuillez remplir les champs ci-dessous.
                </p>

                <!-- SECTION 1: Nom et Sujet -->
                <div class="input-box">
                    <div class="field input-field">
                        <input type="text" id="nom" name="nom" class="item" placeholder="Nom et Prénom" required>
                        <div class="error-txt">Le nom ne peut être vide</div>
                    </div>
                    <div class="field input-field">
                        <input type="text" id="sujet" name="sujet" class="item" placeholder="Sujet de votre requête" required>
                        <div class="error-txt">Le sujet ne peut être vide</div>
                    </div>
                </div>

                <!-- SECTION 2: Courriel et Cellulaire -->
                <div class="input-box">
                    <div class="field input-field">
                        <input type="email" id="courriel" name="courriel" class="item" placeholder="Courriel (ex: user@matrix.com)" required>
                        <div class="error-txt">Format de courriel invalide</div>
                    </div>
                    <div class="field input-field">
                        <input type="tel" id="cellulaire" name="cellulaire" class="item" placeholder="Cellulaire (Format: 514-555-5555)" pattern="[0-9]{3}[-\s]?[0-9]{3}[-\s]?[0-9]{4}" title="Format requis: 514-555-5555" required>
                        <div class="error-txt">Format de cellulaire invalide (ex: 514-555-5555)</div>
                    </div>
                </div>

                <!-- SECTION 3: Commentaire -->
                <div class="field textarea-field">
                    <textarea id="commentaire" name="commentaire" class="item" placeholder="Votre message ou commentaire..." required></textarea>
                    <div class="error-txt">Le commentaire ne peut être vide</div>
                </div>

                <!-- BOUTONS D'ACTION -->
                <div class="input-box submit-actions">
                    <!-- Bouton Envoyer -->
                    <button type="submit" class="submitBtn neon-btn" name="send">
                        <i class="fas fa-paper-plane mr-2"></i> Envoyer
                    </button>
                    <!-- Bouton Effacer/Réinitialiser -->
                    <button type="reset" class="resetBtn neon-btn" name="reset">
                        <i class="fas fa-trash-alt mr-2"></i> Effacer
                    </button>
                </div>
            </form>
        </main>
    </section>

    <!-- Scripts -->

    <script>
        document.querySelector('.resetBtn').addEventListener('click', () => {
            const form = document.getElementById('contact-form');
            form.querySelectorAll('.field.error').forEach(el => el.classList.remove('error'));
        });
    </script>

    <footer>
        <?php include('../include/footer.inc.php'); ?>
    </footer>
    <!--Bootstrap JS et Three.js-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../JS/monJS.js"></script>

    <!--Script pour l'effet 3D des icônes-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"
        integrity="sha512-wC/cunGGDjXSl9OHUH0RuqSyW4YNLlsPwhcLxwWW1CR4OeC2E1xpcdZz2DeQkEmums41laI+eGMw95IJ15SS3g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>