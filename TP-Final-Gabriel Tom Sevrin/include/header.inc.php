<?php

echo '<header class="nav-gaming">';

echo '<div class="nav-inner-container">';

// ---(Logo et Titre) ---
echo '<div class="nav-item">';
echo '<a href="../Accueil.php" class="nav-logo-link" id="dynamic-logo-link">';
echo '<img src="../IMG/GameVerse_Logo.png" alt="Logo de la boutique" class="logo" id="dynamic-logo">';
echo '</a>';
echo '<a href="../Accueil.php" class="nav-title-link">';
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
echo '<a class="nav-link-transition" href="produit.php">Produits</a>';
echo '<a class="nav-link-transition" href="Contact.php">Contact</a>';
echo '<a class="nav-link-transition" href="panier.php"><i class="fas fa-shopping-cart"></i> Panier</a>';
echo '</nav>';


// --- Actions de Recherche et Paramètres ---
echo '<div class="nav-actions-minor">';
echo '<div class="search-container">';
echo '<button class="search-btn" id="search-btn" aria-label="Recherche"><i class="fas fa-search"></i></button>';
echo '<input type="text" class="search-input" placeholder="Rechercher..." id="search-input">';
echo '</div>';


// Bouton pour ouvrir le pop-up des paramètres
echo '<button class="settings-btn" id="settings-btn" aria-label="Ouvrir les paramètres"><i class="fas fa-cog"></i></button>';

echo '</div>'; // Fin de .nav-actions-minor

echo '</div>'; // Fin de .nav-center-content


// ---(Connexion Dropdown) ---
echo '<div class="auth-container">';
echo '<button class="auth-btn neon-button" id="auth-btn"><i class="fas fa-sign-in-alt"></i> Connexion</button>';
echo '<div class="auth-menu" id="auth-menu">';
echo '<a class="nav-link-transition" href="seConnexion.php">Se connecter</a>';
echo '<a class="nav-link-transition" href="Inscription.php">S\'inscrire</a>';
echo '</div>';
echo '</div>'; // Fin de .auth-container

echo '</div>'; // --- FIN CONTENEUR INTERNE ---

echo '</header>'; // Fin du Header


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

// Conteneur du Chatbox (Masqué par défaut)
echo '<div id="nexus-chatbox" class="nexus-chatbox hidden">';

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