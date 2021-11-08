<?php
    if(!isset($_GET["idEquipe"])) {
        header("Location: ListeEquipes.php");
        die();
    }
    else {
        $idEquipe = $_GET["idEquipe"];
        require_once("required/FonctionsDB.php");
        $joueurs = obtenir_joueurs_par_equipe($idEquipe);
        $equipe = obtenir_equipe_par_id($idEquipe);
        if(!$joueurs || !$equipe) {
            header("Location: ListeEquipes.php");
            die();
        }
    }
?>
<html>
    <head>
        <title>Liste des joueurs</title>
        <link rel="stylesheet" href="assets/css/main.css?v=1.1">
    </head>
    <body>
        <?php
            $rangeeEquipe = mysqli_fetch_assoc($equipe);
            mysqli_close($connexion);
            echo "<h1>Liste des joueurs de l'équipe " . htmlspecialchars($rangeeEquipe["nom"], ENT_QUOTES) . " de " . htmlspecialchars($rangeeEquipe["ville"], ENT_QUOTES) ."</h1>";
            require_once("required/Navigation.php");
            echo "<ul>";
            while($joueur = mysqli_fetch_assoc($joueurs)) {
                echo "<li>" . htmlspecialchars($joueur["prenom"], ENT_QUOTES) . " " . htmlspecialchars($joueur["nom"], ENT_QUOTES) . "</li>";
            }
            echo "</ul>";
        ?>
    </body>
</html>