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
    <link rel="stylesheet" href="../CSS/monCSS.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/footer.css">
    <title>TP Final - Gabriel Tom Sevrin</title>
    <!--Icone Onglet-->
    <link rel="icon" type="image/png" href="../IMG/GameVerse_Logo.png" />

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
</head>

<body>
    <header>
        <?php include('../include/header.inc.php'); ?>
    </header>


    <section class="main-content min-h-screen flex items-center justify-center bg-dark-bg" id="panier">
        <main class="w-full">
            <div class="max-w-4xl mx-auto p-4 md:p-8 bg-card-bg shadow-2xl rounded-xl border border-neon-cyan/50 transform hover:scale-[1.01] transition duration-300">

                <div class="text-center">
                    <i class="fas fa-lock text-7xl text-neon-cyan mb-6 mx-auto block neon-text-animated"></i>

                    <p class="text-3xl md:text-5xl font-orbitron font-bold text-neon-cyan neon-text-animated uppercase tracking-widest leading-snug">
                        Accès Restreint
                    </p>
                    <p class="text-lg md:text-xl font-open-sans text-gray-300 mt-4">
                        Veuillez vous connecter pour accéder à votre panier.
                    </p>
                </div>

                <div class="text-center mt-8">
                    <a href="#auth-btn" class="inline-block px-8 py-3 text-lg font-bold font-orbitron text-dark-bg bg-neon-cyan rounded-full transition duration-300 ease-in-out hover:bg-white hover:shadow-neon-cyan/50 hover:shadow-lg transform hover:-translate-y-0.5">
                        Se Connecter Maintenant
                    </a>
                </div>

            </div>
        </main>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"></script>

    <script src="../JS/monJS.js"></script>


    <footer>
        <?php include('../include/footer.inc.php'); ?>
    </footer>
</body>

</html>