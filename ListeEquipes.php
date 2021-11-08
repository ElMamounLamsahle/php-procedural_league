<?php
    require_once("required/FonctionsDB.php");
    // Initialisation des variables par des valeurs par défaut
    $critereTri = "nom";
    $sensTri = "ASC";
    $carctereTriASC = "<small>▼</small>";
    $carctereTriDESC = "<small>▲</small>";
    $carctereTriNom = "";
    $carctereTriVille = "";
    // Initialisation de la valeur de la variable $sensTriNom car elle est utilisée dans le lien de l'entête du tableau
    $sensTriNom = $_GET["sensTriNom"] ?? $sensTri;
    // Initialisation de la valeur de la variable $sensTriVille car elle est utilisée dans le lien de l'entête du tableau
    $sensTriVille = $_GET["sensTriVille"] ?? $sensTri;
    // Je fait le teste si la valeur du sens de tri du nom est rensignée dans l'url
    // je fais le teste par isset malgré que j'ai déja testé dans les variable $sensTriNom et $sensTriVille
    // car ils auront tous les deux une valeur et je peux pas identifier si l'utilisateur a choisi de trier par nom ou par ville
    if (isset($_GET["sensTriNom"])) {
        // $critereTri = "nom" par défaut
        // Si l'utilsateur efface le sens du tri dans l'url ou met une valeur invalide je remet le sens à la valeur par défaut
        $sensTri = ($sensTriNom !== "ASC" && $sensTriNom !== "DESC") ? "ASC" : $sensTriNom;
        $carctereTriNom = ($sensTri === "ASC") ? $carctereTriASC : $carctereTriDESC;
        // Ici j'inverse le sens du tri pour le prochain tri
        $sensTriNom = ($sensTri === "ASC") ? "DESC" : "ASC";
    }
    else {
        // Si non et si le tri par ville est choisi
        if (isset($_GET["sensTriVille"])) {
            $critereTri = "ville";
             // Si l'utilsateur efface le sens du tri dans l'url ou met une valeur invalide je remet le sens à la valeur par défaut
            $sensTri = ($sensTriVille !== "ASC" && $sensTriVille !== "DESC") ? "ASC" : $sensTriVille;
            $carctereTriVille = ($sensTri === "ASC") ? $carctereTriASC : $carctereTriDESC;
            // Ici j'inverse le sens du tri pour le prochain tri
            $sensTriVille = ($sensTri === "ASC") ? "DESC" : "ASC";
        }
        else {
            // Ici si aucun des deux critères n'est rensigné ou l'utilsateur les a effacé de l'url je remets les valeurs par défaut
            // le tri par nom est celui par défaut
            $carctereTriNom = ($sensTri === "ASC") ? $carctereTriASC : $carctereTriDESC;
            // Ici j'inverse le sens du tri pour le prochain tri
            $sensTriNom = ($sensTri === "ASC") ? "DESC" : "ASC";
        }
    }
?>
<html>
    <head>
        <title>Liste des équipes</title>
        <link rel="stylesheet" href="assets/css/main.css?v=1.1">
    </head>
    <body>
        <h1>Liste des équipes de la ligue</h1>
        <?php
            require_once("required/Navigation.php");
            $equipes = obtenir_equipes_triees($critereTri,$sensTri);
            mysqli_close($connexion);
            $html = "<table>";
            $html .= "<tr><th><a href='ListeEquipes.php?sensTriNom={$sensTriNom}'>Nom {$carctereTriNom}</a></th>";
            $html .= "<th><a href='ListeEquipes.php?sensTriVille={$sensTriVille}'>Ville {$carctereTriVille}</a></th>";
            $html .= "<th>Liste des joueurs</th></tr>";
            while($ligne = mysqli_fetch_assoc($equipes)){
                $html .= "<tr><td>" . htmlspecialchars($ligne["nom"], ENT_QUOTES) . "</td>";
                $html .= "<td>" . htmlspecialchars($ligne["ville"], ENT_QUOTES) . "</td>";
                $html .= "<td class='c'><a href='ListeJoueursParEquipe.php?idEquipe={$ligne["id"]}'>Afficher</a></td></tr>";
            }
            $html .= "</table>";
            echo $html;
        ?>
    </body>
</html>