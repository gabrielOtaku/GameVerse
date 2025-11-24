<?php
$titre = "Commande Traitée - GameVerse";
$pageCSS = "paiement.css";

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gameraddict";

// Vérification de la connexion et de la méthode POST
if (!isset($_SESSION['idUsager']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: panier.php");
    exit();
}

// Fonction d'envoi d'email
function send_confirmation_email_structure($to, $subject, $body_html)
{
    // --- Configuration d'en-têtes pour HTML ---
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: GameVerse <ne-pas-repondre@gameverse.com>" . "\r\n";

    // Tentative d'envoi de l'email
    return mail($to, $subject, $body_html, $headers);
}


// Récupération des données postées
$mode_paiement = htmlspecialchars($_POST['payment_method'] ?? 'Inconnu');
$email_facture = htmlspecialchars($_POST['email_facture'] ?? $_SESSION['idUsager'] ?? 'N/A');
$adresse = htmlspecialchars($_POST['adresse'] ?? 'N/A');
$delivery_type = htmlspecialchars($_POST['delivery_type'] ?? 'normal');

// Récupération des totaux 
$sous_total = floatval($_POST['sous_total'] ?? 0);
$tvs = floatval($_POST['tvs'] ?? 0);
$tvq = floatval($_POST['tvq'] ?? 0);
$frais_livraison = floatval($_POST['frais_livraison_final'] ?? 0);
$montant_total = floatval($_POST['total_final'] ?? 0);

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$current_user_id = $_SESSION['idUsager'];
$transaction_success = false;
$commande_id = null;
$error_message = '';
$articles = []; // Pour les détails de l'email
$sujet = "Confirmation de Commande GameVerse";


// --- DÉBUT DE LA TRANSACTION BDD ---
$conn->begin_transaction();

try {
    // 1. Création de la commande
    $sql_commande = "INSERT INTO commande (idUsager, montant_total, mode_paiement, adresse_livraison, type_livraison) 
                     VALUES (?, ?, ?, ?, ?)";
    $stmt_commande = $conn->prepare($sql_commande);
    $stmt_commande->bind_param("sdsss", $current_user_id, $montant_total, $mode_paiement, $adresse, $delivery_type);

    if (!$stmt_commande->execute()) {
        throw new Exception("Erreur d'insertion commande: " . $stmt_commande->error);
    }
    $commande_id = $conn->insert_id;

    if (!$commande_id) {
        throw new Exception("Erreur lors de la création de la commande (ID manquant).");
    }

    // 2. Déplacement des articles du panier vers detail_commande
    $sql_panier = "SELECT id_produit, p.quantite, pr.prix, pr.nomProduit FROM panier p JOIN produit pr ON p.id_produit = pr.idProduit WHERE idUsager = ?";
    $stmt_panier = $conn->prepare($sql_panier);
    $stmt_panier->bind_param("s", $current_user_id);
    $stmt_panier->execute();
    $result_panier = $stmt_panier->get_result();
    $articles = $result_panier->fetch_all(MYSQLI_ASSOC);

    if (empty($articles)) {
        throw new Exception("Le panier est vide lors de la validation. Transaction annulée.");
    }

    $sql_detail = "INSERT INTO detail_commande (id_commande, id_produit, quantite, prix_unitaire) VALUES (?, ?, ?, ?)";
    $stmt_detail = $conn->prepare($sql_detail);

    foreach ($articles as $item) {
        $prix_unitaire = $item['prix'];
        $id_produit = $item['id_produit'];
        $quantite = $item['quantite'];

        $stmt_detail->bind_param("iidd", $commande_id, $id_produit, $quantite, $prix_unitaire);
        if (!$stmt_detail->execute()) {
            throw new Exception("Erreur lors de l'insertion d'un détail de commande.");
        }
    }

    // 3. Vidage du panier
    $sql_vider_panier = "DELETE FROM panier WHERE idUsager = ?";
    $stmt_vider = $conn->prepare($sql_vider_panier);
    $stmt_vider->bind_param("s", $current_user_id);
    $stmt_vider->execute();

    // 4. Validation de la transaction
    $conn->commit();
    $transaction_success = true;

    // --- PRÉPARATION DU CORPS DE L'EMAIL RÉEL ---
    $corps_email_html = "
        <div style='background: #1e1e1e; color: #e0f7ff; padding: 20px; border: 2px solid #00ffcc; border-radius: 8px; font-family: sans-serif;'>
            <h2 style='color: #00ffcc; font-family: \"Orbitron\", sans-serif;'>Confirmation de Commande #{$commande_id}</h2>
            <p>Bonjour {$email_facture} (Client GameVerse),</p>
            <p>Merci d'avoir passé commande chez GameVerse. Voici un résumé de votre achat :</p>
            <hr style='border-color: #ff00aa; margin: 15px 0;'>
            
            <table style='width: 100%; border-collapse: collapse; margin-bottom: 15px;'>
                <tr><th style='color: #00ffcc; text-align: left; padding: 8px 0;'>Produit</th><th style='color: #00ffcc; padding: 8px 0;'>Qté</th><th style='color: #00ffcc; text-align: right; padding: 8px 0;'>Prix Total</th></tr>";

    foreach ($articles as $item) {
        $corps_email_html .= "
            <tr>
                <td style='padding: 5px 0;'>{$item['nomProduit']}</td>
                <td style='text-align: center; padding: 5px 0;'>{$item['quantite']}</td>
                <td style='text-align: right; padding: 5px 0;'>$" . number_format($item['prix'] * $item['quantite'], 2) . "</td>
            </tr>";
    }

    $corps_email_html .= "
            </table>
            
            <hr style='border-color: #ff00aa; margin: 15px 0;'>
            <p style='text-align: right;'>Sous-total HT: $" . number_format($sous_total, 2) . " CAD</p>
            <p style='text-align: right;'>Taxes (TVQ/TVS): $" . number_format($tvs + $tvq, 2) . " CAD</p>
            <p style='text-align: right;'>Livraison ({$delivery_type}): $" . number_format($frais_livraison, 2) . " CAD</p>
            <h3 style='color: #00ffcc; text-align: right; font-family: \"Orbitron\", sans-serif;'>TOTAL FINAL: $" . number_format($montant_total, 2) . " CAD</h3>
            
            <hr style='border-color: #ff00aa; margin: 15px 0;'>
            <p>Adresse de livraison: {$adresse}</p>
            <p style='font-size: 0.9em; margin-top: 20px;'>Ce courriel de confirmation de votre demande fait office de preuve d'achat. Vous recevrez un courriel de suivi dès l'expédition.</p>
        </div>";

    // Envoi de l'email
    send_confirmation_email_structure($email_facture, "Commande GameVerse #{$commande_id} - Confirmation de Demande", $corps_email_html);
} catch (Exception $e) {
    // Annulation de la transaction en cas d'erreur
    $conn->rollback();
    $transaction_success = false;
    $error_message = $e->getMessage();
}

$conn->close();

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
    <link rel="stylesheet" href="../CSS/paiement.css">
    <link rel="stylesheet" href="../CSS/traitement_commande.css">

    <title><?php echo $titre; ?></title>
    <link rel="icon" type="image/png" href="../IMG/GameVerse_Logo.png" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Configuration TailwindCSS 
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'neon-cyan': '#00ffcc',
                        'neon-pink': '#ff00aa',
                        'dark-bg': '#0a0a0a',
                        'card-bg': '#1e1e1e',
                        'success-green': '#1aff66',
                        'error-red': '#ff3366',
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

    <main class="flex flex-col items-center justify-start min-h-screen pt-24 pb-8 bg-dark-bg">
        <div class="glass-panel w-full max-w-4xl p-8 text-center bg-card-bg rounded-xl border border-neon-cyan/30 shadow-2xl">

            <?php if ($transaction_success): ?>
                <h1 class="text-4xl font-orbitron text-neon-cyan mb-4"><i class="fas fa-check-circle mr-2"></i> COMMANDE ENVOYÉE</h1>
                <p class="text-white text-lg mb-6">Votre commande (ID #<?php echo $commande_id; ?>) a été traitée avec succès !</p>
                <p class="text-gray-300 text-sm mb-8">Vous recevrez tous les détails à l'adresse: **<?php echo $email_facture; ?>**.</p>

                <a href="../Accueil.php" class="btn-neon-full btn-neon-cyan mt-4 mr-2 w-auto inline-block"><i class="fas fa-home mr-2"></i> Retour à l'Accueil</a>

                <div class="mt-10 p-4 bg-dark-bg border border-neon-cyan/50 text-left text-sm rounded-lg">
                    <h3 class="font-bold text-neon-cyan text-base mb-2">Email de Confirmation </h3>
                    <p class="text-gray-400">À: <?php echo $email_facture; ?></p>
                    <p class="text-gray-400">Sujet: Confirmation de Commande GameVerse #<?php echo $commande_id; ?> - Confirmation de Demande</p>
                    <hr class="my-2 border-neon-cyan/30">
                    <div class="text-gray-200 text-xs"><?php echo $corps_email_html; ?></div>
                </div>

            <?php else: ?>
                <h1 class="text-4xl font-orbitron text-neon-pink mb-4"><i class="fas fa-times-circle mr-2"></i> ÉCHEC DU PAIEMENT</h1>
                <p class="text-white text-lg mb-6">Une erreur s'est produite lors du traitement de votre commande. Aucun montant n'a été débité.</p>
                <p class="text-sm text-gray-400 mb-8">Détail technique: <?php echo htmlspecialchars($error_message); ?></p>
                <a href="paiement.php" class="btn-neon-full btn-neon-pink mt-4 w-auto inline-block"><i class="fas fa-redo-alt mr-2"></i> Réessayer le Paiement</a>
            <?php endif; ?>
        </div>
    </main>

    <?php if ($transaction_success): ?>
        <div id="success-popup" class="popup-overlay-custom">
            <div class="popup-content-custom">
                <i class="fas fa-handshake success-icon text-6xl mb-4"></i>
                <h3 class="text-2xl font-orbitron text-neon-cyan mb-3">PAIEMENT RÉUSSI</h3>
                <p class="text-gray-300 mb-6">Vous recevrez un courriel de confirmation de votre demande à **<?php echo htmlspecialchars($email_facture); ?>**.</p>

                <div class="flex flex-wrap justify-center gap-2">
                    <a href="https://mail.google.com/mail/u/0/#inbox" target="_blank" class="mail-link gmail-link">
                        <i class="fas fa-envelope mr-2"></i> Ouvrir Gmail
                    </a>
                    <a href="https://outlook.live.com/mail/0/inbox" target="_blank" class="mail-link outlook-link">
                        <i class="fas fa-envelope mr-2"></i> Ouvrir Outlook
                    </a>
                    <a href="https://mail.yahoo.com" target="_blank" class="mail-link yahoo-link">
                        <i class="fas fa-envelope mr-2"></i> Ouvrir Yahoo Mail
                    </a>
                </div>

                <button id="popup-close-btn" class="btn-neon-full btn-neon-pink mt-6 w-full max-w-xs mx-auto">
                    <i class="fas fa-rocket mr-2"></i> Poursuivre
                </button>
            </div>
        </div>
    <?php endif; ?>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const popup = document.getElementById('success-popup');
            const closeBtn = document.getElementById('popup-close-btn');
            const transactionSuccess = <?php echo $transaction_success ? 'true' : 'false'; ?>;
            const homePath = "../Accueil.php";

            if (transactionSuccess && popup && closeBtn) {
                setTimeout(() => {
                    popup.classList.add('show');
                }, 100);

                closeBtn.addEventListener('click', function() {
                    popup.classList.remove('show');
                    setTimeout(() => {}, 400);
                });
            }

            //  fermeture par la touche ESC
            document.addEventListener('keydown', (e) => {
                if (e.key === "Escape" && popup && popup.classList.contains('show')) {
                    closeBtn.click();
                }
            });

        });
    </script>
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