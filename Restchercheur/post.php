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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->table)) {
        switch ($data->table) {
            case 'chercheur':
                $NC = htmlspecialchars(strip_tags($data->NC));
                $NOM = htmlspecialchars(strip_tags($data->NOM));
                $PRENOM = htmlspecialchars(strip_tags($data->PRENOM));
                $NE = htmlspecialchars(strip_tags($data->NE));

                $sqlQuery = "INSERT INTO chercheur (NC, NOM, PRENOM, NE) VALUES (:NC, :NOM, :PRENOM, :NE)";
                $stmt = $conn->prepare($sqlQuery);

                $stmt->bindParam(":NC", $NC);
                $stmt->bindParam(":NOM", $NOM);
                $stmt->bindParam(":PRENOM", $PRENOM);
                $stmt->bindParam(":NE", $NE);

                break;

            case 'equipe':
                $NE = htmlspecialchars(strip_tags($data->NE));
                $NOM = htmlspecialchars(strip_tags($data->NOM));

                $sqlQuery = "INSERT INTO equipe (NE, NOM) VALUES (:NE, :NOM)";
                $stmt = $conn->prepare($sqlQuery);

                $stmt->bindParam(":NE", $NE);
                $stmt->bindParam(":NOM", $NOM);

                break;

            case 'projet':
                $NP = htmlspecialchars(strip_tags($data->NP));
                $NOM = htmlspecialchars(strip_tags($data->NOM));
                $BUDGET = intval($data->BUDGET);
                $NE = htmlspecialchars(strip_tags($data->NE));

                $sqlQuery = "INSERT INTO projet (NP, NOM, BUDGET, NE) VALUES (:NP, :NOM, :BUDGET, :NE)";
                $stmt = $conn->prepare($sqlQuery);

                $stmt->bindParam(":NP", $NP);
                $stmt->bindParam(":NOM", $NOM);
                $stmt->bindParam(":BUDGET", $BUDGET);
                $stmt->bindParam(":NE", $NE);

                break;

            case 'aff':
                $NP = htmlspecialchars(strip_tags($data->NP));
                $NC = htmlspecialchars(strip_tags($data->NC));
                $ANNEE = htmlspecialchars(strip_tags($data->ANNEE));

                $sqlQuery = "INSERT INTO aff (NP, NC, ANNEE) VALUES (:NP, :NC, :ANNEE)";
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
            echo json_encode(array("message" => "Insertion Success dans la table: $data->table."));
        } else {
            echo json_encode(array("message" => "Echec de l'insertion dans la table: $data->table."));
        }
    } else {
        echo "Nom de la table manquant dans les donnees.";
    }
} else {
    echo "Methode de requete invalide.";
}

?>
