<?php

echo '<header class="nav-gaming">';

echo '<div class="nav-inner-container">';


echo '<div class="nav-item">';
echo '<a href="Accueil.php" class="nav-logo-link" id="dynamic-logo-link">';
echo '<img src="IMG/GameVerse_Logo.png" alt="Logo de la boutique" class="logo" id="dynamic-logo">';
echo '</a>';
echo '<a href="Accueil.php" class="nav-title-link">';
echo '<h1>Game<span class="neon-text">Verse</span></h1>';
echo '</a>';
echo '</div>'; // Fin de .nav-item

echo '<button class="mobile-menu-btn" id="hamburger-btn" aria-label="Menu principal">';
echo '<i class="fas fa-bars"></i>';
echo '</button>';

// --- Conteneur Central pour les Liens de Navigation & Actions de Recherche ---

echo '<div class="nav-center-content" id="nav-center-content">';

echo '<nav class="main-links">';
echo '<a href="PHP/produit.php">Produits</a>';
echo '<a href="PHP/Contact.php">Contact</a>';
echo '<a href="PHP/panier.php"><i class="fas fa-shopping-cart"></i> Panier</a>';
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


// --- (Connexion Dropdown) ---
echo '<div class="auth-container">';
// J'ai mis à jour l'icône du bouton de connexion pour un meilleur esthétisme
echo '<button class="auth-btn neon-button" id="auth-btn"><i class="fas fa-sign-in-alt"></i> Connexion</button>';
echo '<div class="auth-menu" id="auth-menu">';
echo '<a href="connexion.php">Se connecter</a>';
echo '<a href="inscription.php">S\'inscrire</a>';
echo '</div>';
echo '</div>'; // Fin de .auth-container

echo '</div>'; // --- FIN CONTENEUR INTERNE ---

echo '</header>'; // Fin du Header


// --- Le POP-UP 
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
