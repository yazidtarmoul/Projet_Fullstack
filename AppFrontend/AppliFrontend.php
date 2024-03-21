<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats des requêtes</title>
    <link rel="stylesheet" href="./AppliFrontend.css">
</head>
<body>

<h1> Service REST API avec une application Front-end </h1>

<form action="" method="GET">
    <label for="action" class="lbl">Sélectionner une action :</label>
    <select name="action" id="action">
        <option value="chercheurs_plus_2_projets_30k_2018">Chercheurs avec plus de 2 projets et un budget total > 30k en 2018</option>
        <option value="chercheurs_meme_projet_que_VIEIRA_2018">Chercheurs ayant travaillé sur les mêmes projets que VIEIRA en 2018</option>
        <option value="projets_budget_superieur_2018">Projets dont le budget est supérieur au maximum en 2018</option>
        <option value="projets_chercheurs_BOUGUEROUA_WOLSKA">Projets auxquels ont participé BOUGUEROUA ou WOLSKA</option>
    </select>
    <button type="submit">Afficher les résultats</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    $host = "localhost";
    $db = "recherche";
    $user = "root";
    $pass = "";

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    $action = $_GET['action'];
    switch ($action) {
        case 'chercheurs_plus_2_projets_30k_2018':
            $sqlQuery = "SELECT chercheur.NOM, chercheur.PRENOM 
                         FROM chercheur 
                         INNER JOIN aff ON chercheur.NC = aff.NC 
                         INNER JOIN projet ON aff.NP = projet.NP 
                         WHERE aff.ANNEE = 2018 
                         GROUP BY chercheur.NC 
                         HAVING COUNT(DISTINCT projet.NP) > 2 
                         AND SUM(projet.BUDGET) > 30000;";
            break;
        case 'chercheurs_meme_projet_que_VIEIRA_2018':
            $sqlQuery = "SELECT chercheur.NOM, chercheur.PRENOM 
                         FROM chercheur 
                         INNER JOIN aff AS aff1 ON chercheur.NC = aff1.NC 
                         INNER JOIN projet AS projet1 ON aff1.NP = projet1.NP 
                         INNER JOIN aff AS aff2 ON projet1.NP = aff2.NP 
                         INNER JOIN chercheur AS chercheur_vieira ON aff2.NC = chercheur_vieira.NC 
                         WHERE chercheur_vieira.NOM = 'VIEIRA' 
                         AND aff1.ANNEE = 2018 
                         AND chercheur.NOM != 'VIEIRA'";
            break;
        case 'projets_budget_superieur_2018':
            $sqlQuery = "SELECT *
                            FROM projet
                            WHERE BUDGET > (
                                SELECT MAX(BUDGET)
                                FROM projet
                                WHERE NP IN (
                                    SELECT NP
                                    FROM aff
                                    WHERE ANNEE = 2018
                                )
                            )";
            break;
        case 'projets_chercheurs_BOUGUEROUA_WOLSKA':
            $sqlQuery = "SELECT DISTINCT projet.NOM 
                         FROM projet 
                         INNER JOIN aff AS aff1 ON projet.NP = aff1.NP 
                         INNER JOIN chercheur ON aff1.NC = chercheur.NC 
                         WHERE chercheur.NOM IN ('BOUGUEROUA', 'WOLSKA')";
            break;
        default:
            echo "Invalid action";
            exit;
    }

    $stmt = $conn->query($sqlQuery);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        echo "<h2>Résultats de l'action : " . str_replace("_", " ", $action) . "</h2>";
        echo "<ul>";
        foreach ($results as $result) {
            if (isset($result['NOM']) && isset($result['PRENOM'])) {
                echo "<li>{$result['NOM']} {$result['PRENOM']}</li>";
            } elseif (isset($result['NOM'])) {
                echo "<li>{$result['NOM']}</li>";
            }
        }
        echo "</ul>";
    } else {
        echo "<p>Aucun résultat trouvé.</p>";
    }
}
?>

</body>
</html>
