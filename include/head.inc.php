<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION['idUsager']);

echo '<header class="nav-gaming">';

echo '<div class="nav-inner-container">';

// ---(Logo et Titre) ---
echo '<div class="nav-item">';
echo '<a href="Accueil.php" class="nav-logo-link" id="dynamic-logo-link">';
echo '<img src="IMG/GameVerse_Logo.png" alt="Logo de la boutique" class="logo" id="dynamic-logo">';
echo '</a>';
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
echo '<a class="nav-link-transition" href="PHP/produit.php">Produits</a>';
echo '<a class="nav-link-transition" href="PHP/Contact.php">Contact</a>';

//  Bouton d'activation de l'Overlay du Panier
echo '<button class="nav-link-transition cart-trigger" id="cart-btn-overlay" aria-label="Ouvrir le panier">';
echo '<i class="fas fa-shopping-cart"></i> Panier';
echo '<span class="cart-badge hidden" id="cart-count-badge">0</span>';
echo '</button>';

echo '</nav>';


// --- Actions de Recherche et Paramètres ---
echo '<div class="nav-actions-minor">';
// Conteneur d'autocomplétion
echo '<div class="search-container">';
echo '<button class="search-btn" id="search-btn" aria-label="Recherche"><i class="fas fa-search"></i></button>';
echo '<input type="text" class="search-input" placeholder="Rechercher..." id="search-input">';
echo '<div id="autocomplete-results" class="autocomplete-results"></div>';
echo '</div>';


// Bouton pour ouvrir le pop-up des paramètres
echo '<button class="settings-btn" id="settings-btn" aria-label="Ouvrir les paramètres"><i class="fas fa-cog"></i></button>';

echo '</div>'; // Fin de .nav-actions-minor

echo '</div>'; // Fin de .nav-center-content


// ---(Connexion Dropdown) --- 
echo '<div class="auth-container">';
if ($is_logged_in) {
    // Affichage de l'avatar/bouton de profil
    echo '<button class="auth-btn neon-button logged-in-btn" id="auth-btn">';

    // Utilisation de la photo encodée en base64 pour l'affichage
    if (isset($_SESSION['photoData_b64']) && !empty($_SESSION['photoData_b64'])) {
        $mime = htmlspecialchars($_SESSION['photoType'] ?? 'image/jpeg');
        $base64Image = $_SESSION['photoData_b64'];
        echo '<img src="data:' . $mime . ';base64,' . $base64Image . '" alt="Profil" class="profile-avatar">';
    } else {
        echo '<i class="fas fa-user"></i>';
    }
    // Afficher le prénom dans le bouton
    echo htmlspecialchars($_SESSION['prenom']) . '</button>';

    // Menu de déconnexion
    echo '<div class="auth-menu" id="auth-menu">';
    echo '<a class="nav-link-transition" href="PHP/profil.php">Mon Profil <i class="fas fa-user-circle"></i></a>';
    echo '<a class="nav-link-transition" href="PHP/deconnexion.php">Déconnexion <i class="fas fa-sign-out-alt"></i></a>';
    echo '</div>';
} else {
    // État déconnecté : Affiche le bouton Connexion
    echo '<button class="auth-btn neon-button" id="auth-btn"><i class="fas fa-sign-in-alt"></i> Connexion</button>';
    echo '<div class="auth-menu" id="auth-menu">';
    echo '<a class="nav-link-transition" href="PHP/seConnecter.php">Se connecter</a>';
    echo '<a class="nav-link-transition" href="PHP/Inscription.php">S\'inscrire</a>';
    echo '</div>';
}
echo '</div>'; // Fin de .auth-container

echo '</div>'; // --- FIN CONTENEUR INTERNE ---

echo '</header>'; // Fin du Header


// --- POP-UP DE PARAMÈTRES  ---
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


echo '<div id="cart-overlay-bg" class="cart-overlay-bg hidden">';
echo '<div id="cart-overlay" class="cart-overlay">';
echo '<header class="cart-header-overlay">';
echo '<h2><i class="fas fa-shopping-cart text-neon-cyan mr-2"></i> Résumé Panier</h2>';
echo '<button class="close-cart-btn" id="close-cart-btn" aria-label="Fermer le panier"><i class="fas fa-times"></i></button>';
echo '</header>';
echo '<div class="cart-content-overlay" id="cart-content-overlay">';
echo '<p class="empty-cart-msg">Chargement du Panier...</p>';
echo '</div>';
echo '<footer class="cart-footer-overlay">';
echo '<p class="text-white text-lg font-orbitron mb-2">Total Provisoire: <span id="cart-total-overlay">$0.00</span></p>';
echo '<a href="PHP/panier.php" class="btn-neon-full mb-3" id="go-to-cart-btn"><i class="fas fa-eye mr-2"></i> Voir le Panier</a>';
echo '<a href="PHP/paiement.php" class="btn-neon-full pulse hidden" id="go-to-checkout-btn"><i class="fas fa-credit-card mr-2"></i> Payer la Commande</a>';
echo '</footer>';
echo '</div>';
echo '</div>';


// =========================================================
// === NEXUS AI CHAT BUBBLE (INCHANGÉ) ===
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
