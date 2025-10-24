<?php

/*********************************
 ******FOOTER DU SITE*************
 *********************************/

// Démarre la section du footer avec un ID pour le style
echo '<footer id="gaming-footer">';

// Informations sur le copyright et l'année dynamique
echo '<div class="footer-section">';
echo '<p>&copy; ' . date('Y') . ' Gabriel Tom SEVRIN. Tous droits réservés.</p>';
echo '</div>';

// Liens vers les politiques (confidentialité, termes de service)
echo '<div class="footer-section">';
echo '<a href="privacy_policy.php">Politique de confidentialité</a> | <a href="terms_of_service.php">Conditions d\'utilisation</a>';
echo '</div>';

// Section des liens vers les réseaux sociaux
echo '<div class="footer-section social-links-container">';
echo '</div>';

// Affichage de la date et de l'heure en temps réel
date_default_timezone_set('America/Montreal');
echo '<div class="footer-section">';
echo '<p>Date et heure actuelle : <span id="current-datetime">' . date('Y-m-d H:i:s') . '</span></p>';
echo '</div>';

// Ferme la balise du footer
echo '</footer>';
