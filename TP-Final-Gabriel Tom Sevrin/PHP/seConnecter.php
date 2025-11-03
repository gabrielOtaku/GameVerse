<?php

session_start();
// -----------------------------------------------------------------------------
// 🛠️ Configuration de la connexion à la base de données
// -----------------------------------------------------------------------------
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gameraddict";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// -----------------------------------------------------------------------------
// 🔍 Récupération des données depuis la base de données
// -----------------------------------------------------------------------------
$products_from_db = [];

$sql = "SELECT idUsager, password, nom, prenom, photoData FROM usager ORDER BY idUsager ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products_from_db[] = [
            'identifiant' => $row['idUsager'],
            'prenom'      => $row['prenom'],
            'nom'         => $row['nom'],
            'password'  => $row['password'],
            'photo'       => $row['photoData']
        ];
    }
}

$products = $products_from_db;


$erreurP = "";
$erreurM = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT idUsager, prenom, nom, photoData, password FROM usager WHERE idUsager = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if ($password === $row['password']) {
            $_SESSION['idUsager'] = $row['idUsager'];
            $_SESSION['prenom'] = $row['prenom'];
            $_SESSION['nom'] = $row['nom'];
            $_SESSION['photo'] = $row['photoData'];
            header("Location: Accueil.php");
            exit();
        } else {
            $erreurP = "Mot de passe incorrect.";
        }
    } else {
        $erreurM = "adresse email introuvable.";
    }

    $stmt->close();
}

$conn->close();


?>

<style>
    form {
        color: #000000;
    }
</style>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TP Final - Gabriel Tom Sevrin</title>
    <link rel="icon" type="image/png" href="../IMG/GameVerse_Logo.png" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Open+Sans:wght@400;600&family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


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

    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/Produit.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/footer.css">

</head>

<body class="bg-dark-bg">


    <Header>
        <?php include('../include/header.inc.php'); ?>
    </Header>

    <section class="main-content min-h-screen pt-24 pb-8 bg-dark-bg">
        <main class="container mx-auto px-4 max-w-7xl">
            <h2 class="text-5xl font-orbitron text-center text-neon-cyan mb-6 uppercase tracking-widest text-shadow-neon">
                Se Connecter
            </h2>
            <form method="POST" action="">

                <div class="mb-3">
                    <label for="email">Courriel</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="password">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary auth-btn neon-button">Se connecter</button>

            </form>

            <div class="text-red-500 mt-4">
                <?php
                if (!empty($erreurP)) echo "<p>$erreurP</p>";
                if (!empty($erreurM)) echo "<p>$erreurM</p>";
                ?>
            </div>

        </main>
    </section>

    <footer>
        <?php include('include/footer.inc.php'); ?>
    </footer>

</body>
