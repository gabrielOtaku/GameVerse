<?php
// TP-Final-Gabriel Tom Sevrin/PHP/seConnecter.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: text/html; charset=utf-8');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gameraddict";

$response = ['success' => false, 'message' => '', 'errors' => []];
$conn = null;

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Erreur de connexion BDD: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {

        $courriel = filter_var($_POST['courriel'] ?? '', FILTER_VALIDATE_EMAIL);
        $mot_de_passe_saisi = $_POST['mot_de_passe'] ?? '';

        if (!$courriel) {
            $response['errors']['courriel'] = "Courriel invalide.";
        }
        if (empty($mot_de_passe_saisi)) {
            $response['errors']['mot_de_passe'] = "Le mot de passe est requis.";
        }

        if (empty($response['errors'])) {

            // Requête pour récupérer toutes les données de l'utilisateur
            $sql = "SELECT idUsager, password, prenom, nom, photoType, photoData FROM usager WHERE idUsager = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $courriel);
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                // MODIFIÉ: Vérification du mot de passe en CLAIR (Non sécurisé)
                if ($user['password'] === $mot_de_passe_saisi) {

                    // Connexion réussie, initialisation de la session
                    session_regenerate_id(true);
                    $_SESSION['idUsager'] = $user['idUsager'];
                    $_SESSION['prenom'] = $user['prenom'];
                    $_SESSION['nom'] = $user['nom'];

                    // Gestion de la photo de profil
                    if ($user['photoData'] !== null && $user['photoType'] !== null) {
                        // Stocker le BLOB encodé en Base64 pour l'affichage dans le header sans nouvelle requête BDD
                        $_SESSION['photoData_b64'] = base64_encode($user['photoData']);
                        $_SESSION['photoType'] = $user['photoType'];
                    } else {
                        // S'assurer que les clés de photo sont propres si pas de photo
                        unset($_SESSION['photoData_b64']);
                        unset($_SESSION['photoType']);
                    }

                    // Marquer l'utilisateur comme nouvellement connecté pour le popup de bienvenue
                    $_SESSION['new_login'] = true;

                    $response['success'] = true;
                    // Redirection vers l'accueil (Accueil.php)
                    $response['redirect'] = "../Accueil.php?login=success";
                    $response['message'] = "Connexion réussie ! Redirection en cours...";
                } else {
                    $response['errors']['general'] = "Courriel ou mot de passe incorrect.";
                }
            } else {
                $response['errors']['general'] = "Courriel ou mot de passe incorrect.";
            }
            $stmt->close();
        }
    }
} catch (Exception $e) {
    $response['success'] = false;
    $response['errors']['server'] = "Erreur du serveur : " . $e->getMessage();
} finally {
    if ($conn) {
        $conn->close();
    }
}

// Réponse AJAX
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    echo json_encode($response);
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - GameVerse</title>
    <link rel="icon" type="image/png" href="../IMG/GameVerse_Logo.png" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/footer.css">
    <link rel="stylesheet" href="../CSS/seConnecter.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'neon-cyan': '#00ffcc',
                        'dark-bg': '#000000',
                        'card-bg': '#1a1a1a',
                        'neon-purple': '#8a2be2',
                        'error-red': '#ff4444',
                        'success-green': '#00ff7f',
                    },
                    fontFamily: {
                        orbitron: ['Orbitron', 'sans-serif'],
                        'open-sans': ['Open Sans', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>

<body class="bg-dark-bg font-open-sans">

    <?php include('../include/header.inc.php'); ?>

    <main class="flex flex-col items-center justify-center min-h-screen pt-24 pb-8">
        <div class="bg-card-bg p-8 md:p-12 rounded-xl neon-border max-w-lg w-full">
            <h1 class="text-4xl font-orbitron text-neon-cyan mb-4 neon-glow text-center">
                <i class="fas fa-sign-in-alt mr-3"></i> Connexion
            </h1>
            <p class="text-gray-400 mb-8 text-center">
                Accédez à votre Matrice personnelle de GameVerse.
            </p>

            <form id="login-form" method="POST">
                <input type="hidden" name="login" value="1">

                <div class="mb-6 input-field">
                    <label for="courriel" class="block mb-2 text-sm font-medium text-gray-300">Courriel</label>
                    <input type="email" id="courriel" name="courriel" class="w-full p-3 rounded-md" required>
                    <div class="error-txt" data-error-for="courriel">Courriel invalide.</div>
                </div>

                <div class="mb-6 input-field">
                    <label for="mot_de_passe" class="block mb-2 text-sm font-medium text-gray-300">Mot de Passe</label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" class="w-full p-3 rounded-md" required>
                    <div class="error-txt" data-error-for="mot_de_passe">Mot de passe requis.</div>
                </div>

                <button type="submit" class="w-full py-3 bg-neon-cyan text-dark-bg font-orbitron uppercase tracking-widest rounded-md neon-button hover:bg-white transition">
                    <i class="fas fa-plug mr-2"></i> Se Connecter
                </button>

                <p class="text-center text-gray-400 mt-6">
                    Pas encore de compte? <a href="Inscription.php" class="text-neon-purple hover:text-neon-cyan transition duration-300">Inscrivez-vous ici.</a>
                </p>
            </form>

        </div>
    </main>

    <div id="popup-success" class="popup-overlay success-popup">
        <div class="popup-content">
            <i class="fas fa-check-circle text-success-green text-6xl mb-4"></i>
            <h3 class="text-2xl font-orbitron mb-2 text-success-green">CONNEXION RÉUSSIE</h3>
            <p id="success-message" class="text-gray-300 mb-6">Accès Matrice Accordé ! Redirection en cours...</p>
            <button id="success-close-btn" class="py-2 px-4 bg-success-green text-dark-bg font-bold rounded hover:bg-white transition">Continuer</button>
        </div>
    </div>

    <div id="popup-error" class="popup-overlay error-popup">
        <div class="popup-content">
            <i class="fas fa-exclamation-triangle text-error-red text-6xl mb-4"></i>
            <h3 class="text-2xl font-orbitron mb-2 text-error-red">ÉCHEC DE CONNEXION</h3>
            <div id="error-details" class="text-gray-300 text-left mb-6 p-3 bg-[#2a2a2a] border border-gray-600 rounded">
            </div>
            <button id="error-close-btn" class="py-2 px-4 bg-error-red text-white font-bold rounded hover:bg-white transition">Réessayer</button>
        </div>
    </div>

    <?php include('../include/footer.inc.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../JS/monJS.js"></script>

</body>

</html>