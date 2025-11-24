<?php
session_start();
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['idUsager'])) {
    $response['message'] = 'Utilisateur non connecté.';
    echo json_encode($response);
    exit();
}

$current_user_id = $_SESSION['idUsager'];

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productId']) && isset($_POST['quantity'])) {
    $productId = intval($_POST['productId']);
    $quantity = intval($_POST['quantity']);

    if ($productId <= 0 || $quantity <= 0) {
        $response['message'] = "Données invalides.";
        echo json_encode($response);
        $conn->close();
        exit();
    }

    // 1. Vérifier si le produit est déjà dans le panier
    $stmt = $conn->prepare("SELECT id, quantite FROM panier WHERE idUsager = ? AND id_produit = ?");
    $stmt->bind_param("si", $current_user_id, $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Le produit existe, met à jour la quantité
        $panier_item = $result->fetch_assoc();
        $new_quantity = $panier_item['quantite'] + $quantity;

        $update_stmt = $conn->prepare("UPDATE panier SET quantite = ? WHERE id = ?");
        $update_stmt->bind_param("ii", $new_quantity, $panier_item['id']);
        if ($update_stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Quantité mise à jour dans le panier.';
        } else {
            $response['message'] = 'Erreur lors de la mise à jour : ' . $conn->error;
        }
    } else {
        // Le produit n'existe pas, l'ajouter
        $insert_stmt = $conn->prepare("INSERT INTO panier (idUsager, id_produit, quantite) VALUES (?, ?, ?)");
        $insert_stmt->bind_param("sii", $current_user_id, $productId, $quantity);
        if ($insert_stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Produit ajouté au panier.';
        } else {
            $response['message'] = 'Erreur lors de l\'insertion : ' . $conn->error;
        }
    }
} else {
    $response['message'] = 'Requête invalide.';
}

$conn->close();
echo json_encode($response);
