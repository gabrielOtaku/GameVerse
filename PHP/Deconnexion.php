<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Réinitialiser les variables de session
$_SESSION = array();

// 3. Si le cookie de session existe, le détruire pour l'invalider
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 4. Détruire la session
session_destroy();

// 5. Rediriger l'utilisateur vers la page d'accueil (à la racine)
header("Location: ../Accueil.php");
exit();
