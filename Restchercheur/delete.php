<?php

$host = 'localhost';
$dbname = 'recherche';
$user = 'root';
$password = '';
$conn = null;

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion reussie";
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->table)) {
        switch ($data->table) {
            case 'chercheur':
                $NC = htmlspecialchars(strip_tags($data->NC));

                $sqlQuery = "DELETE FROM chercheur WHERE NC = :NC";
                $stmt = $conn->prepare($sqlQuery);

                $stmt->bindParam(":NC", $NC);

                break;

            case 'equipe':
                $NE = htmlspecialchars(strip_tags($data->NE));

                $sqlQuery = "DELETE FROM equipe WHERE NE = :NE";
                $stmt = $conn->prepare($sqlQuery);

                $stmt->bindParam(":NE", $NE);

                break;

            case 'projet':
                $NP = htmlspecialchars(strip_tags($data->NP));

                $sqlQuery = "DELETE FROM projet WHERE NP = :NP";
                $stmt = $conn->prepare($sqlQuery);

                $stmt->bindParam(":NP", $NP);

                break;

            case 'aff':
                $NP = htmlspecialchars(strip_tags($data->NP));
                $NC = htmlspecialchars(strip_tags($data->NC));
                $ANNEE = htmlspecialchars(strip_tags($data->ANNEE));

                $sqlQuery = "DELETE FROM aff WHERE NP = :NP AND NC = :NC AND ANNEE = :ANNEE";
                $stmt = $conn->prepare($sqlQuery);

                $stmt->bindParam(":NP", $NP);
                $stmt->bindParam(":NC", $NC);
                $stmt->bindParam(":ANNEE", $ANNEE);

                break;

            default:
                echo "Table invalide";
                exit;
        }

        if ($stmt->execute()) {
            echo json_encode(array("message" => "Suppression Success de la table: $data->table."));
        } else {
            echo json_encode(array("message" => "Echec de la suppression "));
        }
    } else {
        echo "Nom de la table manquant dans les donnees.";
    }
} else {
    echo "Methode de requete invalide.";
}

?>
