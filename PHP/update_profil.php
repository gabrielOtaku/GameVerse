<?php
session_start();
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Erreur inconnue.', 'new_photo_url' => null];

if (!isset($_SESSION['idUsager'])) {
    $response['message'] = 'Utilisateur non connecté.';
    echo json_encode($response);
    exit();
}

// Configuration BDD
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gameraddict";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    $response['message'] = "Échec de la connexion à la base de données.";
    echo json_encode($response);
    exit();
}
$conn->set_charset("utf8mb4");

$current_user_id = $_SESSION['idUsager'];

// Données reçues
$prenom = $_POST['prenom'] ?? '';
$nom = $_POST['nom'] ?? '';
$telephone = $_POST['telephone'] ?? '';
$dateNaiss = $_POST['dateNaiss'] ?? '';
$new_password = $_POST['password'] ?? '';

// Variables pour la photo
$photo_update_sql = "";
$photo_update_params = [];
$photo_data = null;
$photo_ext = null;

// GESTION DE LA PHOTO DE PROFIL
if (isset($_FILES['new_photo']) && $_FILES['new_photo']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['new_photo'];
    $max_size = 5 * 1024 * 1024; // 5 MB
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

    if ($file['size'] > $max_size) {
        $response['message'] = 'La taille du fichier excède 5 Mo.';
        $conn->close();
        echo json_encode($response);
        exit();
    }

    if (!in_array($file['type'], $allowed_types)) {
        $response['message'] = 'Seules les images JPEG, PNG et GIF sont autorisées.';
        $conn->close();
        echo json_encode($response);
        exit();
    }

    $photo_data = file_get_contents($file['tmp_name']);
    $photo_ext = $file['type'];

    // Prépare la partie de la requête pour la photo
    $photo_update_sql = ", photoType = ?, photoData = ?";
    $photo_update_params = [$photo_ext, $photo_data];
}


$fields_sql = "prenom = ?, nom = ?, telephone = ?, dateNaiss = ?";
$params_types = "ssss";
$params_values = [$prenom, $nom, $telephone, $dateNaiss];

// Ajout du mot de passe s'il est fourni
if (!empty($new_password)) {

    $password_claire = $new_password;

    $fields_sql .= ", password = ?";
    $params_types .= "s";
    $params_values[] = $password_claire;
}

// Ajout de la photo si uploadée
if (!empty($photo_update_sql)) {
    $fields_sql .= $photo_update_sql;
    $params_types .= "sb";
    $params_values = array_merge($params_values, $photo_update_params);
}

$sql = "UPDATE usager SET " . $fields_sql . " WHERE idUsager = ?";
$params_types .= "s";
$params_values[] = $current_user_id;

// PRÉPARATION ET EXÉCUTION
$stmt = $conn->prepare($sql);

if (!$stmt) {
    $response['message'] = 'Erreur de préparation de la requête: ' . $conn->error;
    $conn->close();
    echo json_encode($response);
    exit();
}

// Bind des paramètres
$stmt->bind_param($params_types, ...$params_values);

// Envoi du BLOB 
if ($photo_data !== null) {
    $blob_index = strrpos($params_types, 'b');
    if ($blob_index !== false) {
        $stmt->send_long_data($blob_index, $photo_data);
    }
}

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Profil mis à jour avec succès.';

    // Mise à jour des sessions pour la photo et les infos de base
    $_SESSION['prenom'] = $prenom;
    $_SESSION['nom'] = $nom;

    if ($photo_data !== null) {
        // Mise à jour de la session photo
        $_SESSION['photoData_b64'] = base64_encode($photo_data);
        $_SESSION['photoType'] = $photo_ext;
        $response['new_photo_url'] = 'data:' . $photo_ext . ';base64,' . base64_encode($photo_data);
    }
} else {
    $response['message'] = 'Erreur lors de la mise à jour: ' . $stmt->error;
}

$stmt->close();
$conn->close();
echo json_encode($response);
