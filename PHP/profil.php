<?php
$titre = "Mon Profil - GameVerse";
$pageCSS = "profil.css";

// Configuration BDD
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gameraddict";

session_start();

if (!isset($_SESSION['idUsager'])) {
    header("Location: seConnecter.php");
    exit();
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$current_user_id = $_SESSION['idUsager'];

// Récupération des données de l'utilisateur
$sql = "SELECT idUsager, prenom, nom, dateNaiss, telephone, photoType, photoData FROM usager WHERE idUsager = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Si l'utilisateur n'est pas trouvé 
    $conn->close();
    session_destroy();
    header("Location: seConnecter.php");
    exit();
}

$user = $result->fetch_assoc();

$avatar_src = '../IMG/default_avatar.png';
if (!empty($user['photoData']) && !empty($user['photoType'])) {
    $avatar_src = 'data:' . htmlspecialchars($user['photoType']) . ';base64,' . base64_encode($user['photoData']);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Open+Sans:wght@400;600&family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/footer.css">
    <link rel="stylesheet" href="../CSS/profil.css">
    <title><?php echo $titre; ?></title>
    <link rel="icon" type="image/png" href="../IMG/GameVerse_Logo.png" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'neon-cyan': '#00ffcc',
                        'dark-bg': '#0a0a0a',
                        'card-bg': '#1e1e1e',
                    },
                    fontFamily: {
                        orbitron: ['Orbitron', 'sans-serif'],
                        'open-sans': ['Open Sans', 'sans-serif'],
                        mono: ['Roboto Mono', 'monospace'],
                    },
                }
            }
        }
    </script>
</head>

<body class="bg-dark-bg">
    <Header>
        <?php include('../include/header.inc.php'); ?>
    </Header>

    <section class="main-content min-h-screen pt-24 pb-8 bg-dark-bg">
        <main class="container mx-auto px-4 max-w-4xl">

            <div class="profile-container bg-card-bg p-8 rounded-xl shadow-2xl border border-neon-cyan/50 mt-12">

                <div class="profile-header text-center mb-8">
                    <img src="<?php echo $avatar_src; ?>" alt="Photo de profil" class="profile-avatar-display mx-auto" id="profile-avatar-display">
                    <h1 class="text-4xl font-orbitron font-bold text-neon-cyan mt-4 border-b-2 border-neon-cyan/50 pb-3">
                        Profil de <span class="text-white"><?php echo htmlspecialchars($user['prenom']) . ' ' . htmlspecialchars($user['nom']); ?></span>
                    </h1>
                </div>

                <div class="profile-form">
                    <form id="profile-form" enctype="multipart/form-data" class="space-y-6">

                        <div class="photo-upload-group border p-4 rounded-lg border-neon-cyan/30 bg-dark-bg/50">
                            <label class="block text-white font-open-sans mb-3 text-lg">Changer de Photo de Profil:</label>
                            <input type="file" name="new_photo" id="new-photo" accept="image/*" class="hidden">
                            <label for="new-photo" class="file-label cursor-pointer transition-colors duration-300">
                                <i class="fas fa-camera mr-2"></i> Téléverser une Photo
                            </label>
                            <p class="text-xs text-gray-400 mt-2" id="file-name">Aucun fichier sélectionné.</p>
                        </div>

                        <div class="form-row grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="prenom" class="text-neon-cyan">Prénom:</label>
                                <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>" required class="form-input-neon">
                            </div>
                            <div class="form-group">
                                <label for="nom" class="text-neon-cyan">Nom:</label>
                                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required class="form-input-neon">
                            </div>
                        </div>

                        <div class="form-row grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="email" class="text-neon-cyan">Courriel (Identifiant):</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['idUsager']); ?>" readonly class="form-input-neon cursor-not-allowed opacity-70">
                            </div>
                            <div class="form-group">
                                <label for="telephone" class="text-neon-cyan">Téléphone:</label>
                                <input type="tel" id="telephone" name="telephone" value="<?php echo htmlspecialchars($user['telephone']); ?>" class="form-input-neon">
                            </div>
                        </div>

                        <div class="form-row grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="dateNaiss" class="text-neon-cyan">Date de Naissance:</label>
                                <input type="date" id="dateNaiss" name="dateNaiss" value="<?php echo htmlspecialchars($user['dateNaiss']); ?>" class="form-input-neon">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-neon-cyan">Nouveau Mot de Passe (Laisser vide pour ne pas changer):</label>
                                <input type="password" id="password" name="password" placeholder="********" class="form-input-neon">
                            </div>
                        </div>

                        <button type="submit" class="btn-neon-full w-full mt-8 pulse"><i class="fas fa-save mr-2"></i> Enregistrer les Modifications</button>
                    </form>

                    <div id="feedback-message" class="feedback-message text-center mt-4 opacity-0 transition-opacity duration-500"></div>
                </div>

            </div>
        </main>
    </section>

    <footer>
        <?php include('../include/footer.inc.php'); ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
    <script src="../JS/monJS.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('profile-form');
            const feedbackDiv = document.getElementById('feedback-message');
            const photoInput = document.getElementById('new-photo');
            const fileNameDisplay = document.getElementById('file-name');
            const avatarDisplay = document.getElementById('profile-avatar-display');

            // Prévisualisation du nom du fichier
            photoInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    fileNameDisplay.textContent = this.files[0].name;
                } else {
                    fileNameDisplay.textContent = 'Aucun fichier sélectionné.';
                }
            });

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Réinitialiser les messages
                feedbackDiv.classList.remove('feedback-success', 'feedback-error');
                feedbackDiv.style.opacity = '0';
                feedbackDiv.textContent = 'Traitement en cours...';

                const formData = new FormData(form);
                formData.append('action', 'update');

                fetch('update_profil.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        feedbackDiv.textContent = data.message;
                        if (data.success) {
                            feedbackDiv.classList.add('feedback-success');

                            // Mise à jour de l'avatar immédiatement si l'upload a réussi
                            if (data.new_photo_url) {
                                avatarDisplay.src = data.new_photo_url;
                                // On recharge la page pour mettre à jour l'entête et la session
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            }

                            document.getElementById('password').value = '';

                        } else {
                            feedbackDiv.classList.add('feedback-error');
                        }
                        feedbackDiv.style.opacity = '1';

                    })
                    .catch(error => {
                        feedbackDiv.textContent = 'Erreur réseau. Veuillez réessayer.';
                        feedbackDiv.classList.add('feedback-error');
                        feedbackDiv.style.opacity = '1';
                        console.error('Error:', error);
                    });
            });
        });
    </script>
</body>

</html>