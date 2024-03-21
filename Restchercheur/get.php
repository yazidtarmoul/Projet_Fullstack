<?php

$host = "localhost";
$db = "recherche";
$user = "root";
$pass = "";
$conn = null;
try {
    $conn = new PDO("mysql:host=" . $host . ";dbname=" . $db, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed";
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['table'])) {
        $table = $_GET['table'];

        switch ($table) {
            case 'chercheur':
                $sqlQuery = "SELECT * FROM chercheur";
                break;
            case 'equipe':
                $sqlQuery = "SELECT * FROM equipe";
                break;
            case 'projet':
                $sqlQuery = "SELECT * FROM projet";
                break;
            case 'aff':
                $sqlQuery = "SELECT * FROM aff";
                break;
            default:
                echo "Nom de table invalide";
                exit;
        }

        $stmt = $conn->query($sqlQuery);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results);
    } else {
        echo "Nom de table manquant";
    }
} else {
    echo "la methode de la requete est invalide";
}

?>
