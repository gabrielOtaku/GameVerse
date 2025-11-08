<?php
// TP-Final-Gabriel Tom Sevrin/include/head.inc.php

// Démarrer la session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- Variables de session pour l'état de connexion ---
$is_logged_in = isset($_SESSION['idUsager']);
$current_user_prenom = $is_logged_in ? (htmlspecialchars($_SESSION['prenom'] ?? 'Pilote')) : '';

// Gestion de la photo de profil (Base64)
$photo_src = '';
if ($is_logged_in) {
    $photo_data_b64 = $_SESSION['photoData_b64'] ?? null;
    $photo_type = $_SESSION['photoType'] ?? null;

    if ($photo_data_b64 && $photo_type) {
        // Source de l'image à partir des données Base64 (pour les images Blob)
        $photo_src = 'data:image/' . htmlspecialchars($photo_type) . ';base64,' . $photo_data_b64;
    } else {
        // Chemin corrigé : depuis la racine (IMG/default_profile.png)
        $photo_src = 'IMG/default_profile.png';
    }
}

// Style CSS pour l'avatar et le bouton de profil (ajusté pour le problème de CSS)
// NOTE: Ce style est inclus directement dans le <head> de la page HTML
echo '<style>
    .profile-avatar {
        width: 30px; /* Légèrement réduit pour être plus compact */
        height: 30px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 8px;
        border: 2px solid #00ffcc; /* Utilise la couleur néon-cyan */
        box-shadow: 0 0 5px #00ffcc;
    }
    .auth-profile-btn {
        display: flex;
        align-items: center;
        background: none;
        border: none;
        color: white;
        padding: 4px 12px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
    }
    .auth-profile-btn:hover {
        background: rgba(0, 255, 204, 0.1);
    }
</style>';


echo '<header class="nav-gaming">';

echo '<div class="nav-inner-container">';

// ---(Logo et Titre) ---
echo '<div class="nav-item">';
// Chemin corrigé : Accueil.php
echo '<a href="Accueil.php" class="nav-logo-link" id="dynamic-logo-link">';
// Chemin corrigé : IMG/GameVerse_Logo.png
echo '<img src="IMG/GameVerse_Logo.png" alt="Logo de la boutique" class="logo" id="dynamic-logo">';
echo '</a>';
// Chemin corrigé : Accueil.php
echo '<a href="Accueil.php" class="nav-title-link">';
echo '<h1>Game<span class="neon-text">Verse</span></h1>';
echo '</a>';
echo '</div>'; // Fin de .nav-item

// --- Bouton Hamburger ---
echo '<button class="mobile-menu-btn" id="hamburger-btn" aria-label="Menu principal">';
echo '<i class="fas fa-bars"></i>';
echo '</button>';

// --- Conteneur Central pour les Liens de Navigation & Actions de Recherche ---
echo '<div class="nav-center-content" id="nav-center-content">';

// --- Liens de navigation ---
echo '<nav class="main-links">';
// Chemins corrigés (vers le dossier PHP/)
echo '<a class="nav-link-transition" href="PHP/produit.php">Produits</a>';
echo '<a class="nav-link-transition" href="PHP/Contact.php">Contact</a>';
echo '<a class="nav-link-transition" href="PHP/panier.php"><i class="fas fa-shopping-cart"></i> Panier</a>';
echo '</nav>';


// --- Actions de Recherche et Paramètres ---
echo '<div class="nav-actions-minor">';
echo '<div class="search-container">';
echo '<button class="search-btn" id="search-btn" aria-label="Recherche"><i class="fas fa-search"></i></button>';
echo '<input type="text" class="search-input" placeholder="Rechercher..." id="search-input">';
// --- AJOUT POUR L'AUTOCOMPLETION (FIX 3) ---
echo '<div id="autocomplete-results" class="autocomplete-results"></div>';
// ------------------------------------------
echo '</div>';


// Bouton pour ouvrir le pop-up des paramètres
echo '<button class="settings-btn" id="settings-btn" aria-label="Ouvrir les paramètres"><i class="fas fa-cog"></i></button>';

echo '</div>'; // Fin de .nav-actions-minor

echo '</div>'; // Fin de .nav-center-content


// ---(Connexion Dropdown) ---
echo '<div class="auth-container">';

if ($is_logged_in) {
    echo '<button class="auth-profile-btn" id="auth-btn">';

    // Affichage de la photo de profil
    if ($photo_src) {
        echo '<img src="' . $photo_src . '" alt="Photo de profil de ' . $current_user_prenom . '" class="profile-avatar">';
    } else {
        echo '<i class="fas fa-user-circle profile-avatar"></i>';
    }

    echo '<span class="font-orbitron text-neon-cyan mr-1">' . $current_user_prenom . '</span>';
    echo '<i class="fas fa-chevron-down text-xs ml-1"></i>';
    echo '</button>';


    echo '<div class="auth-menu" id="auth-menu">';
    echo '<a class="nav-link-transition" href="PHP/profil.php">Mon Profil</a>';
    echo '<a class="nav-link-transition" href="PHP/Deconnexion.php">Déconnexion</a>';
    echo '</div>';
} else {
    // AFFICHAGE DU BOUTON DE CONNEXION/INSCRIPTION STANDARD
    echo '<button class="auth-btn neon-button" id="auth-btn"><i class="fas fa-sign-in-alt"></i> Connexion</button>';
    echo '<div class="auth-menu" id="auth-menu">';
    echo '<a class="nav-link-transition" href="PHP/seConnecter.php">Se connecter</a>';
    echo '<a class="nav-link-transition" href="PHP/Inscription.php">S\'inscrire</a>';
    echo '</div>';
}

echo '</div>'; // Fin de .auth-container

echo '</div>'; // --- FIN CONTENEUR INTERNE ---

echo '</header>'; // Fin du Header1


// --- POP-UP DE BIENVENUE (pour après le login) ---
if ($is_logged_in && isset($_SESSION['new_login']) && $_SESSION['new_login'] === true) {
    echo '<div id="welcome-popup" class="popup-overlay success-popup show">';
    echo '<div class="popup-content">';
    echo '<i class="fas fa-robot text-neon-cyan text-6xl mb-4"></i>';
    echo '<h3 class="text-2xl font-orbitron mb-2 text-neon-cyan">ACCÈS AUTORISÉ</h3>';
    echo '<p class="text-gray-300 mb-6">Bienvenue, ' . $current_user_prenom . ' ! Heureux de vous revoir dans la Matrice de GameVerse.</p>';
    echo '<button id="welcome-close-btn" class="py-2 px-4 bg-neon-cyan text-dark-bg font-bold rounded hover:bg-white transition">Entrer</button>';
    echo '</div>';
    echo '</div>';
    unset($_SESSION['new_login']);
}


// --- POP-UP DE PARAMÈTRES (Inchangé) ---
echo '<div class="settings-popup-overlay" id="settings-popup-overlay" class="hidden">';
echo '<div class="settings-popup">';
echo '<button class="close-popup-btn" id="close-popup-btn" aria-label="Fermer la fenêtre"><i class="fas fa-times"></i></button>';
echo '<h2><i class="fas fa-wrench"></i> Paramètres du Site</h2>';
echo '<div class="setting-section">';
echo '<h3>Langue de l\'Interface</h3>';
echo '<div class="lang-switch-container">';
echo '<button class="lang-switch-btn fr active" data-lang="fr">FR <i class="fas fa-flag"></i></button>';
echo '<button class="lang-switch-btn en" data-lang="en">EN <i class="fas fa-globe"></i></button>';
echo '</div>';
echo '</div>';
echo '<div class="setting-section">';
echo '<h3>Mode d\'Affichage</h3>';
echo '<div class="theme-switch-container">';
echo '<button class="theme-switch-btn light active" data-theme="light">Light <i class="fas fa-sun"></i></button>';
echo '<button class="theme-switch-btn dark" data-theme="dark">Dark <i class="fas fa-moon"></i></button>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';

echo '<div id="success-message" class="success-message hidden">🎉 Configuration enregistrée !</div>';


// =========================================================
// === NEXUS AI CHAT BUBBLE (AJOUTÉ À TOUTES LES PAGES) ===
// =========================================================
echo '<div id="nexus-ai-bubble" class="chat-bubble-container">';
// Bouton flottant pour ouvrir
echo '<button id="nexus-toggle-btn" class="nexus-toggle-btn">';
echo '<i class="fas fa-robot"></i>';
echo '</button>';

// Conteneur du Chatbox 
echo '<div id="nexus-chatbox" class="nexus-chatbox">';

// Header du Chat
echo '<header class="nexus-chat-header">';
echo '<div class="flex items-center space-x-2">';
echo '<i class="fas fa-robot text-xl text-neon-cyan neon-glow"></i>';
echo '<h2 class="text-lg font-orbitron uppercase">Nexus AI</h2>';
echo '</div>';
echo '<button id="nexus-close-btn" class="nexus-close-btn" aria-label="Fermer le chat"><i class="fas fa-times"></i></button>';
echo '</header>';

// Zone de Chat (Historique)
echo '<main id="chat-area-bubble" class="chat-area-bubble">';
// Message de Bienvenue Initial
echo '<div class="flex justify-start">';
echo '<div class="chat-bubble ai-bubble">';
echo '<p class="text-xs text-neon-cyan font-orbitron mb-1">Nexus AI</p>';
echo '<p class="text-sm text-gray-200">Bienvenue. Je suis Nexus, votre assistant GameVerse. Posez-moi une question !</p>';
echo '</div>';
echo '</div>';
echo '</main>';

// Zone de Saisie et Loader
echo '<footer class="nexus-chat-footer">';
echo '<div id="loader-bubble" class="loader-bubble hidden">';
echo '<div class="flex items-center space-x-2">';
echo '<i class="fas fa-microchip text-sm text-neon-cyan"></i>';
echo '<p class="text-gray-400 text-xs">Nexus en ligne<span class="blinking-dot">.</span><span class="blinking-dot" style="animation-delay: 0.2s;">.</span><span class="blinking-dot" style="animation-delay: 0.4s;">.</span></p>';
echo '</div>';
echo '</div>';

echo '<div class="flex space-x-2 items-center">';
echo '<input type="text" id="user-input-bubble" placeholder="Message..." class="nexus-input">';
echo '<button id="send-btn-bubble" class="nexus-send-btn" aria-label="Envoyer">';
echo '<i class="fas fa-paper-plane"></i>';
echo '</button>';
echo '</div>';
echo '</footer>';

echo '</div>'; // Fin de #nexus-chatbox
echo '</div>'; // Fin de #nexus-ai-bubble