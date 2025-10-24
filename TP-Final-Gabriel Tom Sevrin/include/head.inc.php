<?php


echo '<header class="nav-gaming">';

// --- Logo et Titre ---
echo '<div class="nav-item">';
echo '<a href="Accueil.php" class="nav-logo-link" id="dynamic-logo-link">';
echo '<img src="IMG/GameVerse_Logo.png" alt="Logo de la boutique" class="logo" id="dynamic-logo">';
echo '</a>';
echo '<a href="Accueil.php" class="nav-title-link">';
echo '<h1>Game<span class="neon-text">Verse</span></h1>';
echo '</a>';
echo '</div>';


// --- Liens de navigation ---
echo '<nav class="main-links">';
echo '<a href="PHP/produit.php">Produits</a>';
echo '<a href="PHP/Contact.php">Contact</a>';
echo '<a href="PHP/panier.php"><i class="fas fa-shopping-cart"></i> Panier</a>';
echo '</nav>';


// --- Boutons d\'interaction (recherche, paramètres, connexion) ---
echo '<div class="nav-actions">';
// Barre de recherche
echo '<div class="search-container">';
echo '<input type="text" class="search-input" placeholder="Rechercher..." id="search-input">';
echo '<button class="search-btn" id="search-btn" aria-label="Recherche"><i class="fas fa-search"></i></button>';
echo '</div>';


// Bouton pour ouvrir le pop-up des paramètres
echo '<button class="settings-btn" id="settings-btn" aria-label="Ouvrir les paramètres"><i class="fas fa-cog"></i></button>';

// Bouton de connexion/déconnexion et menu déroulant 
echo '<div class="auth-container">';
echo '<button class="auth-btn" id="auth-btn"><i class="fas fa-user-circle"></i> Connexion</button>';
echo '<div class="auth-menu" id="auth-menu">';
echo '<a href="connexion.php">Se connecter</a>';
echo '<a href="inscription.php">S\'inscrire</a>';
echo '</div>';
echo '</div>';

echo '</div>'; // Fin de .nav-actions

echo '</header>'; // Fin du Header


// --- POP-UP DE PARAMÈTRES (Masqué par défaut via CSS) ---
echo '<div class="settings-popup-overlay" id="settings-popup-overlay">';
echo '<div class="settings-popup">';
echo '<button class="close-popup-btn" id="close-popup-btn" aria-label="Fermer la fenêtre"><i class="fas fa-times"></i></button>';
echo '<h2><i class="fas fa-wrench"></i> Paramètres du Site</h2>';

// Section choix de la langue
echo '<div class="setting-section">';
echo '<h3>Langue de l\'Interface</h3>';
echo '<div class="lang-switch-container">';
// Remplacement des <img> par du texte/icônes pour utiliser le CSS toggle switch pur
echo '<button class="lang-switch-btn fr active" data-lang="fr">FR <i class="fas fa-flag"></i></button>';
echo '<button class="lang-switch-btn en" data-lang="en">EN <i class="fas fa-globe"></i></button>';
echo '</div>';
echo '</div>';

// Section choix du thème
echo '<div class="setting-section">';
echo '<h3>Mode d\'Affichage</h3>';
echo '<div class="theme-switch-container">';
echo '<button class="theme-switch-btn light active" data-theme="light">Light <i class="fas fa-sun"></i></button>';
echo '<button class="theme-switch-btn dark" data-theme="dark">Dark <i class="fas fa-moon"></i></button>';
echo '</div>';
echo '</div>';

// Message de succès 
echo '</div>'; // Fin de .settings-popup
echo '</div>'; // Fin de .settings-popup-overlay

echo '<div id="success-message" class="success-message">🎉 Configuration enregistrée !</div>'; // Message fixe

// Font Awesome (Doit être chargé une seule fois, idéalement dans le <head>)
echo '<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>';
