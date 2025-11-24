<?php
$titre = "Mon Panier";
$pageCSS = "Panier.css";

// Configuration BDD
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gameraddict";

session_start();
$is_logged_in = isset($_SESSION['idUsager']);

// Connexion à la base de données
if ($is_logged_in) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Échec de la connexion à la base de données : " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
    $current_user_id = $_SESSION['idUsager'];

    // --- Gestion des actions (Update / Delete) ---
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'delete' && isset($_POST['id_panier'])) {
            $stmt = $conn->prepare("DELETE FROM panier WHERE id = ? AND idUsager = ?");
            $stmt->bind_param("is", $_POST['id_panier'], $current_user_id);
            $stmt->execute();
        } elseif ($_POST['action'] == 'update' && isset($_POST['id_panier'], $_POST['quantite'])) {
            $qty = intval($_POST['quantite']);
            if ($qty > 0) {
                $stmt = $conn->prepare("UPDATE panier SET quantite = ? WHERE id = ? AND idUsager = ?");
                $stmt->bind_param("iis", $qty, $_POST['id_panier'], $current_user_id);
                $stmt->execute();
            }
        }
        // Redirection pour éviter le renvoi du formulaire
        header("Location: panier.php");
        exit();
    }

    // Récupération des articles du panier
    $sql_fetch = "SELECT p.id as id_panier, p.quantite, pr.nomProduit, pr.prix, pr.imageNom 
                   FROM panier p 
                   JOIN produit pr ON p.id_produit = pr.idProduit 
                   WHERE p.idUsager = ?";
    $stmt_fetch = $conn->prepare($sql_fetch);
    $stmt_fetch->bind_param("s", $current_user_id);
    $stmt_fetch->execute();
    $articles = $stmt_fetch->get_result()->fetch_all(MYSQLI_ASSOC);
    $total_global = 0;

    foreach ($articles as $item) {
        $total_global += $item['prix'] * $item['quantite'];
    }

    $conn->close();
}
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

    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/footer.css">
    <link rel="stylesheet" href="../CSS/<?php echo $pageCSS; ?>">

    <title><?php echo $titre; ?></title>
    <link rel="icon" type="image/png" href="../IMG/GameVerse_Logo.png" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Configuration TailwindCSS (répétée pour indépendance de la page)
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
    <?php require_once '../include/header.inc.php'; ?>

    <main class="flex flex-col items-center justify-start min-h-screen pt-24 pb-8 bg-dark-bg panier-wrapper">
        <div class="glass-panel w-full max-w-4xl p-8">
            <h1 class="text-3xl font-orbitron text-neon-cyan mb-6 border-b-2 border-neon-cyan/50 pb-2 text-center">Votre Inventaire</h1>

            <?php if (!$is_logged_in): ?>
                <div class="restricted-access-box text-center p-10 bg-card-bg rounded-xl border border-neon-pink/50 shadow-neon-lg">
                    <i class="fas fa-lock text-6xl text-neon-pink mb-4 neon-glow-pink"></i>
                    <h2 class="text-2xl font-orbitron text-white mb-3">Accès Restreint</h2>
                    <p class="text-gray-400 mb-6">Pour voir et gérer votre panier, vous devez être connecté.</p>
                    <div class="flex flex-col md:flex-row justify-center gap-4">
                        <a href="seConnecter.php" class="btn-neon-full btn-neon-pink w-full md:w-auto"><i class="fas fa-sign-in-alt mr-2"></i> Se connecter</a>
                        <a href="Inscription.php" class="btn-neon-full btn-neon-cyan w-full md:w-auto"><i class="fas fa-user-plus mr-2"></i> S'inscrire</a>
                    </div>
                </div>

            <?php elseif (empty($articles)): ?>
                <p class="empty-msg text-center text-gray-400 p-10">
                    Votre panier est vide. <a href="produit.php" class="text-neon-cyan hover:underline">Allez jouer !</a>
                </p>
            <?php else: ?>
                <table class="table-panier w-full text-left border-collapse">
                    <thead>
                        <tr class="text-neon-cyan font-orbitron uppercase border-b border-neon-cyan/50">
                            <th class="p-3">Produit</th>
                            <th class="p-3">Prix Unitaire</th>
                            <th class="p-3 text-center">Quantité</th>
                            <th class="p-3 text-right">Total</th>
                            <th class="p-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($articles as $item): ?>
                            <tr class="border-b border-gray-700/50 hover:bg-card-bg/50 transition-colors duration-200 text-gray-300">
                                <td class="col-produit p-3 flex items-center space-x-3">
                                    <img src="../Media/Media/<?php echo htmlspecialchars($item['imageNom']); ?>" alt="Jeu" class="w-12 h-12 object-cover rounded-md border border-neon-cyan/30">
                                    <span><?php echo htmlspecialchars($item['nomProduit']); ?></span>
                                </td>
                                <td class="p-3">$<?php echo number_format($item['prix'], 2); ?></td>
                                <td class="p-3 text-center">
                                    <form method="POST" class="form-qty inline-block">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="id_panier" value="<?php echo $item['id_panier']; ?>">
                                        <input type="number" name="quantite" value="<?php echo $item['quantite']; ?>" min="1"
                                            onchange="this.form.submit()"
                                            class="w-16 text-center bg-dark-bg border border-neon-cyan/50 rounded-md text-white">
                                    </form>
                                </td>
                                <td class="p-3 text-right font-bold text-white">$<?php echo number_format($item['prix'] * $item['quantite'], 2); ?></td>
                                <td class="p-3 text-center">
                                    <form method="POST" class="inline-block">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_panier" value="<?php echo $item['id_panier']; ?>">
                                        <button type="submit" class="btn-delete text-neon-pink hover:text-red-700 transition-colors" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="panier-footer flex justify-between items-center mt-8 pt-4 border-t border-neon-pink/50">
                    <h3 class="text-2xl font-orbitron text-white">Total Provisoire: <span class="text-neon-cyan">$<?php echo number_format($total_global, 2); ?> CAD</span></h3>
                    <a href="paiement.php" class="btn-neon-full pulse">
                        <i class="fas fa-credit-card mr-2"></i> Procéder au Paiement
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <?php include('../include/footer.inc.php'); ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
        integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
    <script src="../JS/monJS.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"
        integrity="sha512-wC/cunGGDjXSl9OHUH0RuqSyW4YNLlsPwhcLxwWW1CR4OeC2E1xpcdZz2DeQkEmums41laI+eGMw95IJ15SS3g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>