<?php
    // Je teste ici seulement la valeur de critère
    // Il va avoir la chaîne vide s'il s'agit du premier affichage de la page
    // ou si le champ de saisi du critére est vide
    $critere = $_GET["critere"] ?? "";
?>
<html>
    <head>
        <title>Rechercher un joueur</title>
        <link rel="stylesheet" href="assets/css/main.css?v=1.1">
    </head>
    <body>
        <h1>Rechercher un joueur</h1>
        <?php require_once("required/Navigation.php");?>
        <form action="RechercherJoueur.php" method="get">
            <label for="critere">Nom ou prénom contient</label>
            <input type="text" name="critere" id="critere" value="<?= $critere ?>" class="search">
            <input type="submit" name="chercher" value="Chercher" class="search">
            <?php
                if ($critere !== "") {
                    require_once("required/FonctionsDB.php");
                    $critere = $_GET["critere"];
                    $resultat = rechercher_joueur($critere);
                    mysqli_close($connexion);
                    if ($resultat) {
                        $html = '<table>';
                        $html .= '<tr><th>Nom</th><th>Prénom</th><th>Équipe</th><th>Ville</th></tr>';
                        while($ligne = mysqli_fetch_assoc($resultat)){
                            $html .= '<tr><td>' . htmlspecialchars($ligne["nomJoueur"], ENT_QUOTES) . '</td>';
                            $html .= '<td>' . htmlspecialchars($ligne["prenom"], ENT_QUOTES) . '</td>';
                            $html .= '<td>' . htmlspecialchars($ligne["nomEquipe"], ENT_QUOTES) . '</td>';
                            $html .= '<td>' . htmlspecialchars($ligne["ville"], ENT_QUOTES) . '</td></tr>';
                        }
                        $html .= '</table>';
                        echo $html;
                    }
                    else echo '<p class="msg-search">Aucun résultat trouvé pour votre recherche.</p>';
                }
            ?>
        </form>
    </body>
</html>