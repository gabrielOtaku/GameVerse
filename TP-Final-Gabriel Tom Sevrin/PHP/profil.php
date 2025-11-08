<?php
// TP-Final-Gabriel Tom Sevrin/PHP/profil.php

// Démarrer la session et inclure le header
session_start();

// Vérification de la connexion
if (!isset($_SESSION['idUsager'])) {
    // Si l'utilisateur n'est pas connecté, le rediriger vers la page de connexion ou l'accueil
    header("Location: seConnecter.php");
    exit();
}

// -----------------------------------------------------------------------------
// 🛠️ Récupération des données de l'utilisateur depuis la session
// -----------------------------------------------------------------------------
$idUsager = htmlspecialchars($_SESSION['idUsager']);
$prenom = htmlspecialchars($_SESSION['prenom'] ?? 'Non défini');
$nom = htmlspecialchars($_SESSION['nom'] ?? 'Non défini');
$dateNaiss = htmlspecialchars($_SESSION['dateNaiss'] ?? 'Non définie');
$telephone = htmlspecialchars($_SESSION['telephone'] ?? 'Non défini');

// Gestion de la photo de profil (chemin relatif pour cette page)
$photo_src = '';
$photo_data_b64 = $_SESSION['photoData_b64'] ?? null;
$photo_type = $_SESSION['photoType'] ?? null;

if ($photo_data_b64 && $photo_type) {
    $photo_src = 'data:image/' . htmlspecialchars($photo_type) . ';base64,' . $photo_data_b64;
} else {
    // Chemin corrigé pour l'inclusion depuis le dossier PHP/
    $photo_src = '../IMG/default_profile.png';
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - GameVerse</title>
    <link rel="icon" type="image/png" href="../IMG/GameVerse_Logo.png" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'neon-cyan': '#00ffcc',
                        'dark-bg': '#000000',
                        'card-bg': '#1a1a1a',
                    },
                    fontFamily: {
                        orbitron: ['Orbitron', 'sans-serif'],
                        'open-sans': ['Open Sans', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/footer.css">
    <link rel="stylesheet" href="../CSS/profil.css">
</head>

<body class="bg-dark-bg">
    <div id="page-preloader">
        <div class="loader-content">
            <img src="../IMG/GameVerse_Logo.png" alt="Logo de chargement" class="loading-logo">
            <div class="loading-bar">
                <div class="loading-progress"></div>
            </div>
            <p class="loading-text">Chargement de la Matrice...</p>
        </div>
    </div>

    <header>
        <?php include('../include/header.inc.php'); ?>
    </header>

    <section class="main-content min-h-screen pt-24 flex items-center justify-center">
        <main class="profil-container">

            <div class="profile-card">
                <h2 class="font-orbitron text-neon-cyan uppercase tracking-wider">
                    <i class="fas fa-user-cog mr-2"></i> Console Pilote
                </h2>
                <p class="text-gray-400 mb-6">
                    Bienvenue dans votre espace personnel, <?php echo $prenom; ?>.
                </p>

                <div class="profile-avatar-section">
                    <img src="<?php echo $photo_src; ?>" alt="Photo de profil" class="profile-main-avatar">
                    <button class="edit-photo-btn neon-btn-small" aria-label="Modifier la photo de profil">
                        <i class="fas fa-camera"></i> Modifier
                    </button>
                </div>

                <form id="profile-form" action="#" method="POST" class="mt-8">

                    <div class="input-field-profile">
                        <label for="courriel"><i class="fas fa-at"></i> Courriel (Identifiant)</label>
                        <input type="email" id="courriel" name="courriel" class="item-readonly"
                            value="<?php echo $idUsager; ?>" readonly>
                    </div>

                    <div class="input-field-profile">
                        <label for="prenom"><i class="fas fa-signature"></i> Prénom</label>
                        <input type="text" id="prenom" name="prenom" value="<?php echo $prenom; ?>" disabled>
                    </div>

                    <div class="input-field-profile">
                        <label for="nom"><i class="fas fa-user"></i> Nom de Famille</label>
                        <input type="text" id="nom" name="nom" value="<?php echo $nom; ?>" disabled>
                    </div>

                    <div class="input-field-profile">
                        <label for="date_naissance"><i class="fas fa-calendar-alt"></i> Date de Naissance</label>
                        <input type="date" id="date_naissance" name="date_naissance"
                            value="<?php echo $dateNaiss; ?>" disabled>
                    </div>

                    <div class="input-field-profile">
                        <label for="telephone"><i class="fas fa-phone-alt"></i> Téléphone</label>
                        <input type="tel" id="telephone" name="telephone"
                            value="<?php echo $telephone; ?>" disabled>
                    </div>

                    <div class="profile-actions">
                        <button type="button" id="edit-profile-btn" class="neon-btn edit-btn">
                            <i class="fas fa-pencil-alt mr-2"></i> Éditer le Profil
                        </button>
                        <button type="submit" id="save-profile-btn" class="neon-btn save-btn hidden" disabled>
                            <i class="fas fa-save mr-2"></i> Enregistrer
                        </button>
                        <button type="button" id="cancel-edit-btn" class="neon-btn-secondary hidden">
                            <i class="fas fa-times-circle mr-2"></i> Annuler
                        </button>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="#" id="change-password-link" class="text-neon-cyan hover:text-white transition duration-300 text-sm">
                            <i class="fas fa-lock"></i> Changer le mot de passe
                        </a>
                    </div>
                </form>

            </div>
        </main>
    </section>

    <footer>
        <?php include('../include/footer.inc.php'); ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../JS/monJS.js"></script>
</body>

</html>