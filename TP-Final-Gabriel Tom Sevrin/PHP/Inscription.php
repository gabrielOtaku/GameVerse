<?php
// TP-Final-Gabriel Tom Sevrin/PHP/Inscription.php

session_start();

// =========================================================
// === PARTIE PHP : TRAITEMENT DU FORMULAIRE ET BASE DE DONNÉES ===
// =========================================================

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

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register'])) {

        $courriel = filter_var($_POST['courriel'] ?? '', FILTER_VALIDATE_EMAIL);
        $mot_de_passe = $_POST['mot_de_passe'] ?? '';
        $prenom = htmlspecialchars(trim($_POST['prenom'] ?? ''));
        $nom = htmlspecialchars(trim($_POST['nom'] ?? ''));
        $date_naissance = $_POST['date_naissance'] ?? '';
        $cellulaire = preg_replace('/[^0-9]/', '', $_POST['cellulaire'] ?? '');

        if (!$courriel) $response['errors']['courriel'] = "Courriel invalide.";

        // MODIFIÉ: Retrait du minimum de 8 caractères. Max 64 maintenu.
        if (strlen($mot_de_passe) > 64) {
            $response['errors']['mot_de_passe'] = "Le mot de passe est trop long (max 64 caractères).";
        }
        // Ajout d'une vérification si le champ est vide (malgré le 'required' HTML)
        if (empty($mot_de_passe)) {
            $response['errors']['mot_de_passe'] = "Le mot de passe est requis.";
        }

        if (empty($prenom)) $response['errors']['prenom'] = "Le prénom est requis.";
        if (empty($nom)) $response['errors']['nom'] = "Le nom est requis.";
        if (!preg_match("/^\d{10}$/", $cellulaire)) $response['errors']['cellulaire'] = "Format cellulaire invalide (10 chiffres).";

        if (!empty($date_naissance)) {
            $date_diff = date_diff(date_create($date_naissance), date_create('now'));
            if ($date_diff->y < 13) {
                $response['errors']['date_naissance'] = "Vous devez avoir au moins 13 ans pour vous inscrire.";
            }
        } else {
            $response['errors']['date_naissance'] = "La date de naissance est requise.";
        }

        $photo_data = null;
        $photo_ext = null;

        if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['photo_profil'];

            if ($file['size'] > 65536) {
                $response['errors']['photo_profil'] = "La photo de profil ne doit pas dépasser 64 KB.";
            } else {
                $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                if (in_array($mime_type, $allowed_mimes)) {
                    $photo_data = file_get_contents($file['tmp_name']);
                    $photo_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                } else {
                    $response['errors']['photo_profil'] = "Type de fichier non supporté (JPEG, PNG, WebP uniquement).";
                }
            }
        }

        if (empty($response['errors'])) {
            // MODIFIÉ: Le mot de passe est stocké en clair
            $mot_de_passe_clair = $mot_de_passe;

            $sql = "INSERT INTO usager (idUsager, password, prenom, nom, dateNaiss, telephone, photoType, photoData) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            if ($photo_data !== null) {
                // Utilisation du mot de passe en clair
                $stmt->bind_param("sssssssb", $courriel, $mot_de_passe_clair, $prenom, $nom, $date_naissance, $cellulaire, $photo_ext, $photo_data);

                $stmt->send_long_data(7, $photo_data);
            } else {
                $photo_null = null;
                $ext_null = null;

                // Utilisation du mot de passe en clair
                $stmt->bind_param("ssssssss", $courriel, $mot_de_passe_clair, $prenom, $nom, $date_naissance, $cellulaire, $ext_null, $photo_null);
            }

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "Inscription réussie ! Vous allez être redirigé vers la page de connexion.";

                // AJOUTÉ: Préparer la photo pour l'affichage en session après le login
                if ($photo_data) {
                    $_SESSION['photoData_b64'] = base64_encode($photo_data);
                    $_SESSION['photoType'] = $photo_ext;
                }
            } else {
                if ($conn->errno == 1062) {
                    $response['errors']['courriel'] = "Ce courriel est déjà utilisé.";
                } else {
                    $response['errors']['general'] = "Erreur SQL : " . $stmt->error;
                }
            }
            $stmt->close();
        }
    }
} catch (Exception $e) {
    $response['success'] = false;
    $response['errors']['server'] = $e->getMessage();
} finally {
    if ($conn) {
        $conn->close();
    }
}

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
    <title>Inscription - GameVerse</title>
    <link rel="icon" type="image/png" href="../IMG/GameVerse_Logo.png" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/footer.css">
    <link rel="stylesheet" href="../CSS/Inscription.css">

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
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>


</head>

<body class="bg-dark-bg font-open-sans">

    <?php include('../include/header.inc.php'); ?>

    <main class="flex flex-col items-center justify-start min-h-screen pt-24 pb-8">

        <div class="registration-container flex flex-col lg:flex-row items-center justify-center p-4 lg:p-10 w-full max-w-7xl mx-auto">

            <div class="lg:w-1/3 w-full p-4 flex justify-center items-center h-[400px] lg:h-full mb-8 lg:mb-0 max-h-[600px] lg:order-1">
                <model-viewer
                    id="bot-model"
                    class="w-full h-full object-contain lg:sticky lg:top-24"
                    src="../module/mon_petit_bot.glb"
                    alt="Nexus AI Bot de GameVerse"
                    ar
                    camera-controls
                    touch-action="pan-y"
                    shadow-intensity="1"
                    environment-image="neutral"
                    auto-rotate
                    exposure="1"
                    poster="../IMG/GameVerse_Logo.png"
                    animation-name="Salutation"
                    camera-orbit="0deg 90deg 1m"
                    disable-tap
                    disable-pan></model-viewer>
            </div>

            <div class="lg:w-2/3 w-full lg:pl-12 lg:order-2">
                <div class="bg-card-bg p-8 md:p-12 rounded-xl neon-border">
                    <h1 class="text-4xl font-orbitron text-neon-cyan mb-4 neon-glow">
                        <i class="fas fa-user-plus mr-3"></i> Création de Compte
                    </h1>
                    <p class="text-gray-400 mb-8">
                        Rejoignez la Matrice. Remplissez le formulaire ci-dessous pour accéder à GameVerse.
                    </p>

                    <form id="registration-form" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="register" value="1">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="input-field">
                                <label for="prenom" class="block mb-2 text-sm font-medium text-gray-300">Prénom</label>
                                <input type="text" id="prenom" name="prenom" class="w-full p-3 rounded-md" required>
                                <div class="error-txt" data-error-for="prenom">Le prénom est requis.</div>
                            </div>
                            <div class="input-field">
                                <label for="nom" class="block mb-2 text-sm font-medium text-gray-300">Nom</label>
                                <input type="text" id="nom" name="nom" class="w-full p-3 rounded-md" required>
                                <div class="error-txt" data-error-for="nom">Le nom est requis.</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="input-field">
                                <label for="courriel" class="block mb-2 text-sm font-medium text-gray-300">Courriel</label>
                                <input type="email" id="courriel" name="courriel" class="w-full p-3 rounded-md" required>
                                <div class="error-txt" data-error-for="courriel">Courriel invalide ou déjà utilisé.</div>
                            </div>
                            <div class="input-field">
                                <label for="date_naissance" class="block mb-2 text-sm font-medium text-gray-300">Date de Naissance</label>
                                <input type="date" id="date_naissance" name="date_naissance" class="w-full p-3 rounded-md text-gray-400" max="<?= date('Y-m-d', strtotime('-13 years')); ?>" required>
                                <div class="error-txt" data-error-for="date_naissance">Vous devez avoir au moins 13 ans.</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="input-field relative">
                                <label for="mot_de_passe" class="block mb-2 text-sm font-medium text-gray-300">Mot de Passe</label>
                                <input type="password" id="mot_de_passe" name="mot_de_passe" class="w-full p-3 rounded-md" required maxlength="64" autocomplete="new-password">
                                <div class="error-txt" data-error-for="mot_de_passe">Maximum 64 caractères requis.</div>
                                <button type="button" id="suggest-password-btn" class="absolute right-0 top-8 text-xs bg-neon-cyan text-dark-bg px-2 py-1 rounded-bl-md hover:bg-white transition">Suggestion</button>
                            </div>
                            <div class="input-field">
                                <label for="cellulaire" class="block mb-2 text-sm font-medium text-gray-300">Cellulaire (CAD)</label>
                                <input type="tel" id="cellulaire" name="cellulaire" class="w-full p-3 rounded-md" placeholder="Format: 514-555-5555" required pattern="[0-9]{3}[-\s]?[0-9]{3}[-\s]?[0-9]{4}" title="Format requis: 514-555-5555">
                                <div class="error-txt" data-error-for="cellulaire">Format invalide (10 chiffres requis).</div>
                            </div>
                        </div>

                        <div id="password-suggestion-display" class="text-sm font-open-sans text-neon-cyan mb-6 p-2 rounded-md bg-card-bg border border-neon-cyan/50 hidden cursor-pointer">
                        </div>



                        <div class="mb-8">
                            <div class="input-field">
                                <label for="photo_profil" class="block mb-2 text-sm font-medium text-gray-300">Photo de Profil (Max 64 KB)</label>
                                <input type="file" id="photo_profil" name="photo_profil" accept="image/jpeg, image/png, image/webp" class="w-full p-3 rounded-md border border-gray-700 hover:border-neon-cyan transition duration-300">
                                <div class="error-txt" data-error-for="photo_profil">Taille ou format de fichier incorrect.</div>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 bg-neon-purple text-white font-orbitron uppercase tracking-widest rounded-md neon-button">
                            <i class="fas fa-sign-in-alt mr-2"></i> S'inscrire à GameVerse
                        </button>
                    </form>

                </div>
            </div>

        </div>

    </main>

    <div id="popup-success" class="popup-overlay success-popup">
        <div class="popup-content">
            <i class="fas fa-check-circle text-success-green text-6xl mb-4"></i>
            <h3 class="text-2xl font-orbitron mb-2 text-success-green">CONNEXION ÉTABLIE</h3>
            <p id="success-message" class="text-gray-300 mb-6">Votre compte a été créé avec succès ! Vous allez être redirigé vers la page de connexion.</p>
            <button id="success-close-btn" class="py-2 px-4 bg-success-green text-dark-bg font-bold rounded hover:bg-white transition">Continuer</button>
        </div>
    </div>

    <div id="popup-error" class="popup-overlay error-popup">
        <div class="popup-content">
            <i class="fas fa-exclamation-triangle text-error-red text-6xl mb-4"></i>
            <h3 class="text-2xl font-orbitron mb-2 text-error-red">ERREUR DE TRANSMISSION</h3>
            <div id="error-details" class="text-gray-300 text-left mb-6 p-3 bg-[#2a2a2a] border border-gray-600 rounded">
            </div>
            <button id="error-close-btn" class="py-2 px-4 bg-error-red text-white font-bold rounded hover:bg-white transition">Corriger</button>
        </div>
    </div>


    <?php include('../include/footer.inc.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../JS/monJS.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"
        integrity="sha512-wC/cunGGDjXSl9OHUH0RuqSyW4YNLlsPwhcLxwWW1CR4OeC2E1xpcdZz2DeQkEmums41laI+eGMw95IJ15SS3g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>

</html>