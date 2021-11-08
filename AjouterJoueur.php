<?php
    require_once("required/FonctionsDB.php");
    $erreur = false;
    if (isset($_POST["enregistrer"])) { // ou if (count($_POST) > 0)
        $nom = $_POST["nom"] ?? "";
        $prenom = $_POST["prenom"] ?? "";
        $idEquipe = $_POST["equipe"] ?? "";
        $salaire = $_POST["salaire"] ?? "";
        $pays = $_POST["pays"] ?? "";
        // Les controles des champs sont faits dans la fonction
        // Si un champ obligatoir n'est pas rensigné la fonction retourne false
        // Ou si un champs non obligatoir est rensigné ($idEquipe et $pays) la valeur doit correspondre au type de données du champs
        $resultat = ajouter_joueur($nom, $prenom, $idEquipe , $salaire, $pays);
        // Ici j'ai utilisé la variable $erreur pour ne pas faire plusieurs testes dans les valeurs des champs (isset($resultat) && !$resultat)
        // et elle est utilisée en cas d'une erreur imprévue
        if (!$resultat) $erreur = true;
    }
?>
<html>
    <head>
        <title>Ajouter un nouveau joueur</title>
        <link rel="stylesheet" href="assets/css/main.css?v=1.1">
    </head>
    <body>
        <h1>Ajouter un joueur</h1>
        <?php require_once("required/Navigation.php");?>
        <form action="AjouterJoueur.php" method="post">
            <!-- Malgré que les champs ont l'attribut required et j'ai voulu réafficher les valeur des champs s'il y a une erreur imprévue-->
            <!-- Pour ne pas obliger l'utilisateur à resaisir tous les champs-->
            <label for="nom">Nom</label>
            <input required type="text" name="nom" id="nom" value="<?= ($erreur) ? $nom : "" ?>"><span>*</span>
            <label for="prenom">Prénom</label>
            <input required type="text" name="prenom" id="prenom" value="<?= ($erreur) ? $prenom : "" ?>"><span>*</span>
            <label for="equipe">Équipe</label>
            <!-- Je n'ai pas mis ici l'attribut required car j'ai remarqué que le champ idEquipe dans la base de données accepte la valeur NULL-->
            <select name="equipe" id="equipe">
                 <option value="">Choisissez une équipe</option>
                <?php
                    $equipes = obtenir_equipes();
                    while($ligne = mysqli_fetch_assoc($equipes)){
                        $selected = ($erreur && $ligne["id"] == $idEquipe) ? " selected" : "";
                        echo '<option value="' . $ligne["id"] . '"' . $selected .'>' . htmlspecialchars($ligne["nom"], ENT_QUOTES) . '</option>';
                    }
                ?>
            </select>
            <label for="salaire">Salaire</label>
            <input required type="number" name="salaire" id="salaire" value="<?= ($erreur) ? $salaire : "" ?>"><span>*</span>
            <label for="pays">Pays</label>
            <!-- Je n'ai pas mis ici l'attribut required car j'ai remarqué que le champ idEquipe dans la base de données accepte la valeur NULL-->
            <input type="text" name="pays" id="pays" value="<?= ($erreur) ? $pays : "" ?>">
            <input type="submit" name="enregistrer" value="Enregistrer" class="submit">
            <?php
                if (isset($resultat)) {
                    if ($erreur) echo "<p>L'ajout n'a pas fonctionné.</p>";
                    else echo $resultat;
                }
                mysqli_close($connexion);
            ?>
        </form>
    </body>
</html>