<?php
// TP-Final-Gabriel Tom Sevrin/PHP/get_cart_data.php
session_start();
header('Content-Type: application/json');

// Initialisation avec des valeurs vides/nulles
$response = ['success' => false, 'count' => 0, 'total' => 0.00, 'products' => []];

if (!isset($_SESSION['idUsager'])) {
    $response['success'] = true;
    echo json_encode($response);
    exit();
}

$current_user_id = $_SESSION['idUsager'];

// Configuration de la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gameraddict";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    // En cas d'échec de la connexion BDD
    echo json_encode($response);
    exit();
}
$conn->set_charset("utf8mb4");

// Requête pour obtenir les détails des produits du panier
$sql = "SELECT p.quantite, pr.nomProduit, pr.prix, pr.imageNom 
        FROM panier p 
        JOIN produit pr ON p.id_produit = pr.idProduit 
        WHERE p.idUsager = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_items_count = 0;
$total_amount = 0.00;
$products = [];

while ($row = $result->fetch_assoc()) {
    $total_items_count += $row['quantite'];
    $sub_total = floatval($row['prix']) * intval($row['quantite']);
    $total_amount += $sub_total;

    $products[] = [
        'nomProduit' => $row['nomProduit'],
        'prix' => number_format(floatval($row['prix']), 2, '.', ''),
        'quantite' => intval($row['quantite']),
        'imageNom' => $row['imageNom']
    ];
}

$response['success'] = true;
$response['count'] = $total_items_count;
$response['total'] = $total_amount;
$response['products'] = $products;

$conn->close();
echo json_encode($response);
