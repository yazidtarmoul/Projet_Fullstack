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
    if (isset($_GET['action'])) {
        $action = $_GET['action'];

        switch ($action) {
            case 'budgets':
                $sqlQuery = "SELECT DISTINCT budget FROM projet ORDER BY budget DESC";
                break;
            case 'budget_Between':
                $sqlQuery = "SELECT * FROM projet WHERE budget BETWEEN 400000 AND 900000";
                break;
            case 'Nom_equipes_chercheurs':
                $sqlQuery = "SELECT chercheur.NOM, equipe.NOM AS equipe_nom FROM chercheur INNER JOIN equipe ON chercheur.NE = equipe.NE";
                break;
            case 'equipes_projets':
                $sqlQuery = "SELECT equipe.NOM, COUNT(projet.NP) AS nb_projets FROM equipe LEFT JOIN projet ON equipe.NE = projet.NE GROUP BY equipe.NE";
                break;
            default:
                echo "Invalid action";
                exit;
        }

        $stmt = $conn->query($sqlQuery);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results);
    } else {
        echo "Missing action parameter";
    }
} else {
    echo "Invalid request method";
}

?>

