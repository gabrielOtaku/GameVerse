<?php
$titre = "Paiement - GameVerse";
$pageCSS = "paiement.css";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gameraddict";

session_start();

// Vérification de la connexion
if (!isset($_SESSION['idUsager'])) {
    header("Location: seConnecter.php?redirect=paiement.php");
    exit();
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$current_user_id = $_SESSION['idUsager'];

const TAUX_TVS = 0.05;
const TAUX_TVQ = 0.09975;

// Récupération des articles du panier
$sql = "SELECT p.quantite, pr.nomProduit, pr.prix, pr.imageNom
        FROM panier p 
        JOIN produit pr ON p.id_produit = pr.idProduit 
        WHERE p.idUsager = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
$articles = $result->fetch_all(MYSQLI_ASSOC);

if (count($articles) === 0) {
    // Rediriger si le panier est vide
    header("Location: panier.php?empty=true");
    exit();
}

$sous_total = 0;
foreach ($articles as $item) {
    // Correction pour la quantité 
    $qty = isset($item['quantite']) && is_numeric($item['quantite']) ? (int)$item['quantite'] : 1;
    $sous_total += $item['prix'] * $qty;
}

// Calcul des taxes
$tvs = $sous_total * TAUX_TVS;
$tvq = $sous_total * TAUX_TVQ;
$sous_total_taxes_incluses = $sous_total + $tvs + $tvq;

// Frais de livraison
$frais_normal = 10.00;
$frais_rapide = 25.00;
$frais_livraison = $frais_normal;
$total_final = $sous_total_taxes_incluses + $frais_livraison;

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
    <link rel="stylesheet" href="../CSS/<?php echo $pageCSS; ?>">

    <title><?php echo $titre; ?></title>
    <link rel="icon" type="image/png" href="../IMG/GameVerse_Logo.png" />
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
    <?php require_once '../include/header.inc.php'; ?>

    <main class="flex flex-col items-center justify-start min-h-screen pt-24 pb-8 bg-dark-bg">

        <h1 class="text-3xl font-orbitron text-neon-cyan mb-10 border-b-2 border-neon-pink pb-2">Finaliser votre Commande</h1>

        <div class="payment-container grid grid-cols-1 lg:grid-cols-2 gap-8 w-full max-w-7xl px-4">

            <div class="payment-form bg-card-bg p-8 rounded-xl shadow-2xl border border-neon-cyan/30">
                <h2 class="text-2xl font-orbitron text-neon-pink mb-6">Informations de Paiement</h2>

                <form action="traitement_commande.php" method="POST" id="payment-form">

                    <div class="form-group mb-6">
                        <label class="text-neon-cyan mb-2">Mode de Paiement:</label>
                        <div class="payment-methods flex justify-around gap-4 text-white">
                            <label for="credit-card" class="flex-1 text-center cursor-pointer p-4 rounded-lg transition-all duration-300 border border-gray-600">
                                <input type="radio" id="credit-card" name="payment_method" value="credit_card" class="hidden" required checked>
                                <span class="block text-xl mb-1"><i class="fas fa-credit-card mr-1"></i></span>
                                <span>Crédit/Mastercard</span>
                            </label>
                            <label for="paypal" class="flex-1 text-center cursor-pointer p-4 rounded-lg transition-all duration-300 border border-gray-600">
                                <input type="radio" id="paypal" name="payment_method" value="paypal" class="hidden" required>
                                <span class="block text-xl mb-1"><i class="fab fa-paypal mr-1"></i></span>
                                <span>PayPal</span>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="email_facture">Adresse Courriel (Facture):</label>
                            <input type="email" id="email_facture" name="email_facture" value="<?php echo htmlspecialchars($_SESSION['idUsager'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="adresse">Adresse d'Habitation:</label>
                            <input type="text" id="adresse" name="adresse" placeholder="123 Rue de la Cyberpunk, Montréal" required>
                        </div>
                    </div>

                    <div id="credit-card-details">
                        <h3 class="text-black font-orbitron text-lg mt-6 mb-4 border-b border-gray-700 pb-2">Détails de la Carte</h3>
                        <div class="form-group">
                            <label for="card_number">Numéro de Carte:</label>
                            <input type="text" id="card_number" name="card_number" placeholder="XXXX XXXX XXXX XXXX" pattern="\d{16}" title="16 chiffres" required>
                        </div>
                        <div class="grid grid-cols-3 gap-4 mt-4">
                            <div class="form-group">
                                <label for="expiry">Date d'Expiration:</label>
                                <input type="text" id="expiry" name="expiry" placeholder="MM/AA" pattern="\d{2}/\d{2}" title="MM/AA" required>
                            </div>
                            <div class="form-group">
                                <label for="cvv">CVV:</label>
                                <input type="text" id="cvv" name="cvv" placeholder="XXX" pattern="\d{3,4}" title="3 ou 4 chiffres" required>
                            </div>
                            <div class="form-group">
                                <label for="card_name">Nom sur la carte:</label>
                                <input type="text" id="card_name" name="card_name" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-6">
                        <label for="delivery_type">Option de Livraison:</label>
                        <select id="delivery_type" name="delivery_type" required>
                            <option value="normal" data-frais="<?php echo $frais_normal; ?>">Normal ($<?php echo number_format($frais_normal, 2); ?>)</option>
                            <option value="rapide" data-frais="<?php echo $frais_rapide; ?>">Rapide ($<?php echo number_format($frais_rapide, 2); ?>)</option>
                        </select>
                    </div>

                    <input type="hidden" name="sous_total" id="hidden_sous_total" value="<?php echo $sous_total; ?>">
                    <input type="hidden" name="tvs" id="hidden_tvs" value="<?php echo $tvs; ?>">
                    <input type="hidden" name="tvq" id="hidden_tvq" value="<?php echo $tvq; ?>">
                    <input type="hidden" name="total_taxes_incluses" id="hidden_total_taxes_incluses" value="<?php echo $sous_total_taxes_incluses; ?>">
                    <input type="hidden" name="frais_livraison_final" id="hidden_frais_livraison" value="<?php echo $frais_normal; ?>">
                    <input type="hidden" name="total_final" id="hidden_total_final" value="<?php echo $total_final; ?>">

                    <button type="submit" class="btn-neon-full mt-6 pulse text-xl" id="submit-payment-btn">
                        <i class="fas fa-satellite-dish mr-2"></i> Payer $<?php echo number_format($total_final, 2); ?> CAD
                    </button>
                </form>
            </div>

            <div class="payment-summary bg-card-bg p-8 rounded-xl shadow-2xl border border-neon-cyan/30 h-fit sticky top-24">
                <h2 class="text-2xl font-orbitron text-white mb-4 flex justify-between items-center">
                    Résumé de la Commande
                    <button id="open-summary-popup-btn" class="text-neon-cyan hover:text-neon-pink text-lg lg:hidden"><i class="fas fa-eye"></i></button>
                </h2>

                <div id="summary-content-main">
                    <ul class="text-gray-300 border-b border-gray-700 pb-3 mb-3">
                        <?php foreach ($articles as $item): ?>
                            <li class="summary-item flex justify-between py-1">
                                <span><?php echo htmlspecialchars($item['nomProduit']) . ' x ' . $item['quantite']; ?></span>
                                <span>$<?php echo number_format($item['prix'] * $item['quantite'], 2); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="summary-item text-white flex justify-between mt-4">
                        <span>Sous-total HT:</span>
                        <span>$<?php echo number_format($sous_total, 2); ?> CAD</span>
                    </div>
                    <div class="summary-item text-white flex justify-between">
                        <span class="text-sm text-gray-400">TVS (<?php echo TAUX_TVS * 100; ?>%):</span>
                        <span>$<?php echo number_format($tvs, 2); ?> CAD</span>
                    </div>
                    <div class="summary-item text-white flex justify-between">
                        <span class="text-sm text-gray-400">TVQ (<?php echo TAUX_TVQ * 100; ?>%):</span>
                        <span>$<?php echo number_format($tvq, 2); ?> CAD</span>
                    </div>

                    <div class="summary-item text-white flex justify-between mt-4">
                        <span>Frais de Livraison (<span id="type-livraison-main">Normal</span>):</span>
                        <span id="frais-livraison-main">$<?php echo number_format($frais_normal, 2); ?> CAD</span>
                    </div>

                    <div class="summary-total mt-5 pt-3 border-t border-neon-pink/50 text-3xl font-orbitron flex justify-between">
                        <span class="text-neon-pink">Total Final:</span>
                        <span id="total-final-main" class="text-neon-cyan">$<?php echo number_format($total_final, 2); ?> CAD</span>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <div id="summary-popup-overlay">
        <div class="summary-popup">
            <h2 class="text-2xl font-orbitron text-neon-cyan mb-4 flex justify-between items-center">
                Résumé
                <button id="close-summary-popup-btn" class="text-neon-pink hover:text-white text-2xl"><i class="fas fa-times"></i></button>
            </h2>

            <div id="popup-product-list" class="space-y-4 py-4 border-b border-gray-700/50">
                <?php foreach ($articles as $item): ?>
                    <div class="summary-item-card">
                        <img src="../Media/Media/<?php echo htmlspecialchars($item['imageNom']); ?>" alt="<?php echo htmlspecialchars($item['nomProduit']); ?>" class="w-12 h-12 object-cover rounded-md border border-neon-cyan/50 mr-3">
                        <div class="details">
                            <span class="font-bold text-white"><?php echo htmlspecialchars($item['nomProduit']); ?></span>
                            <span class="text-sm text-neon-cyan/80"><?php echo $item['quantite']; ?> x $<?php echo number_format($item['prix'], 2); ?></span>
                        </div>
                        <span class="text-neon-pink font-bold">$<?php echo number_format($item['prix'] * $item['quantite'], 2); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-white text-right space-y-1 mt-4">
                <p>Sous-total HT: $<?php echo number_format($sous_total, 2); ?> CAD</p>
                <p class="text-sm text-gray-400">Taxes: $<?php echo number_format($tvs + $tvq, 2); ?> CAD</p>
                <p>Livraison (<span id="type-livraison-popup">Normal</span>): <span id="frais-livraison-popup">$<?php echo number_format($frais_normal, 2); ?> CAD</span></p>
            </div>

            <div class="mt-4 pt-4 border-t border-neon-pink/50 text-3xl font-orbitron flex justify-between">
                <span class="text-neon-pink">Total:</span>
                <span id="total-final-popup" class="text-neon-cyan">$<?php echo number_format($total_final, 2); ?> CAD</span>
            </div>

            <button id="pay-from-popup-btn" class="btn-neon-full mt-6 pulse text-xl w-full">
                <i class="fas fa-satellite-dish mr-2"></i> Payer $<?php echo number_format($total_final, 2); ?> CAD
            </button>

        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deliverySelect = document.getElementById('delivery_type');
            const fraisSpanMain = document.getElementById('frais-livraison-main');
            const typeSpanMain = document.getElementById('type-livraison-main');
            const totalSpanMain = document.getElementById('total-final-main');
            const totalSpanPopup = document.getElementById('total-final-popup');
            const fraisSpanPopup = document.getElementById('frais-livraison-popup');
            const typeSpanPopup = document.getElementById('type-livraison-popup');
            const submitPaymentBtn = document.getElementById('submit-payment-btn');
            const payFromPopupBtn = document.getElementById('pay-from-popup-btn');

            const subTotal = parseFloat(<?php echo $sous_total; ?>);
            const tvs = parseFloat(<?php echo $tvs; ?>);
            const tvq = parseFloat(<?php echo $tvq; ?>);
            const subTotalTaxesIncl = subTotal + tvs + tvq;

            const hiddenFraisInput = document.getElementById('hidden_frais_livraison');
            const hiddenTotalFinalInput = document.getElementById('hidden_total_final');
            const cardDetailsDiv = document.getElementById('credit-card-details');
            const creditCardRadio = document.getElementById('credit-card');
            const paypalRadio = document.getElementById('paypal');

            const summaryOverlay = document.getElementById('summary-popup-overlay');
            const openSummaryBtn = document.getElementById('open-summary-popup-btn');
            const closeSummaryBtn = document.getElementById('close-summary-popup-btn');

            function updateTotals() {
                const selectedOption = deliverySelect.options[deliverySelect.selectedIndex];
                const fraisLivraison = parseFloat(selectedOption.getAttribute('data-frais'));
                const typeLivraison = selectedOption.text.split(' ')[0];
                const totalFinal = subTotalTaxesIncl + fraisLivraison;

                // Met à jour les interfaces
                fraisSpanMain.textContent = `$${fraisLivraison.toFixed(2)} CAD`;
                typeSpanMain.textContent = typeLivraison;
                totalSpanMain.textContent = `$${totalFinal.toFixed(2)} CAD`;

                fraisSpanPopup.textContent = `$${fraisLivraison.toFixed(2)} CAD`;
                typeSpanPopup.textContent = typeLivraison;
                totalSpanPopup.textContent = `$${totalFinal.toFixed(2)} CAD`;

                // Met à jour les champs cachés pour la soumission
                hiddenFraisInput.value = fraisLivraison.toFixed(2);
                hiddenTotalFinalInput.value = totalFinal.toFixed(2);

                // Met à jour le texte des boutons de soumission
                const buttonText = `Payer $${totalFinal.toFixed(2)} CAD`;
                submitPaymentBtn.innerHTML = `<i class="fas fa-satellite-dish mr-2"></i> ${buttonText}`;
                payFromPopupBtn.innerHTML = `<i class="fas fa-satellite-dish mr-2"></i> ${buttonText}`;
            }

            function toggleCardDetails() {
                const isCreditCard = creditCardRadio.checked;
                cardDetailsDiv.style.display = isCreditCard ? 'block' : 'none';

                // Rend les champs de carte obligatoires/optionnels
                const requiredFields = cardDetailsDiv.querySelectorAll('input');
                requiredFields.forEach(input => {
                    input.required = isCreditCard;
                });

                document.querySelector('label[for="credit-card"]').classList.toggle('selected-payment', isCreditCard);
                document.querySelector('label[for="paypal"]').classList.toggle('selected-payment', !isCreditCard);
            }

            // --- Logique du Popup de Résumé ---
            if (openSummaryBtn && closeSummaryBtn && summaryOverlay) {
                openSummaryBtn.addEventListener('click', () => {
                    summaryOverlay.classList.add('show');
                });
                closeSummaryBtn.addEventListener('click', () => {
                    summaryOverlay.classList.remove('show');
                });
                summaryOverlay.addEventListener('click', (e) => {
                    if (e.target === summaryOverlay) {
                        summaryOverlay.classList.remove('show');
                    }
                });
                payFromPopupBtn.addEventListener('click', () => {
                    document.getElementById('payment-form').submit();
                });
            }

            // --- Initialisation et Listeners ---
            deliverySelect.addEventListener('change', updateTotals);
            creditCardRadio.addEventListener('change', toggleCardDetails);
            paypalRadio.addEventListener('change', toggleCardDetails);

            updateTotals();
            toggleCardDetails();
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