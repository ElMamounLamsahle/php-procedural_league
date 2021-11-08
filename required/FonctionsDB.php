<?php

    /**
     * Fonction de connexion à la base de données
     * @return object  $connexion, l'objet $connexion
    */
    function connect() {
        // se connecter à la base de données
        // vous devez peut être modifier le mot de passe et la nom de la base de données
        $connexion = mysqli_connect("localhost", "root", "", "ligue_Exercice");
        if(!$connexion) {
            trigger_error("Erreur de connexion : " . mysqli_connect_error());
            die();
        }
        return $connexion;
    }

    // Stockage de l'objet connexion dans la variable $connexion
    $connexion = connect();

    /**
     * Fonction qui ferme la connexion à la base de données
    */
    function fermer_connexion() {
        global $connexion;
        mysqli_close($connexion);
    }

    /**
     * Fonction pour obtenir la liste des équipe
     * @return array  $resultats, tableau associatif du résultat de la requête
    */
    function obtenir_equipes() {
        global $connexion;
        $requete = "SELECT * FROM equipe";
        $resultats = mysqli_query($connexion, $requete);
        return $resultats;
    }

    /**
     * Fonction pour obtenir la liste des équipes triée par nom ou par ville
     * @param  string $critereTri, le critère de tri : nom ou ville
     * @param  string $sensTri, sens du tri : ASC ou DESC
     * @return array  $resultats, tableau associatif du résultat de la requête
    */
    function obtenir_equipes_triees($critereTri, $sensTri) {
        global $connexion;
        // Je n'ai pas fait un conrole ici des paramètres car ils sont bien controlés dans la page ListeEquipes.php
        // et la fonction ne recevera jamais un autre critére que nom ou ville
        // et ne recevera jamais un autre sens de tri que ASC ou DESC
        $requete = "SELECT * FROM equipe ORDER BY {$critereTri} {$sensTri}";
        $resultats = mysqli_query($connexion, $requete);
        return $resultats;
    }

    /**
     * Fonction pour obtenir la liste des joueurs par équipe
     * @param  integer $idEquipe
     * @return array|boolean  $resultats|false, tableau associatif du résultat de la requête ou false si le paramètre n'est pas numérique
    */
    function obtenir_joueurs_par_equipe($idEquipe) {
        if(is_numeric($idEquipe)) {
            global $connexion;
            $requete = "SELECT * FROM joueur WHERE idEquipe=$idEquipe";
            $resultats = mysqli_query($connexion, $requete);
            return $resultats;
        }
        else return false;
    }

    /**
     * Fonction pour obtenir les données d'équipe par son id
     * @param  integer $idEquipe
     * @return array|boolean  $resultats|false, tableau associatif du résultat de la requête ou false si le paramètre n'est pas numérique
    */
    function obtenir_equipe_par_id($idEquipe) {
        if(is_numeric($idEquipe)) {
            global $connexion;
            $requete = "SELECT * FROM equipe WHERE id=$idEquipe";
            $resultats = mysqli_query($connexion, $requete);
            return $resultats;
        }
        else return false;
    }

    /**
     * Fonction d'ajout d'un nouveau joueur
     * @param  string $nom, requis
     * @param  string $prenom, requis
     * @param  string $idEquipe, optionnel
     * @param  string $salaire, requis
     * @param  string $pays, optionnel
     * @return string|boolean  message|false, message indiquant que l'ajout a été bien fait ou ou false si l'ajout n'a pas fonctionné
    */
    function ajouter_joueur($nom, $prenom, $idEquipe, $salaire, $pays) {
        // Je n'ai pas obligé ici les champs idEquipe et pays car j'ai remarqué que dans la base de données ils acceptent la valeur NULL
        // Cependant si le champs idEquipe est rensigné il doit être numérique
        // J'ai fait le teste malgré que la valeur de idEquipe provienne de la value du select mais on ne sait jamais si une requête HTTP a été contournée ou pas
        if ($nom !== "" && $prenom !== "" && ($idEquipe === "" || is_numeric($idEquipe)) && is_numeric($salaire)) {
            global $connexion;
            $requete = "INSERT INTO joueur (nom, prenom, idEquipe, salaire, pays) VALUES (?, ?, ?, ?, ?)";
            // Mettre la valeur NULL pour les champs non requis s'ils ne sont pas rensignés
            if ($idEquipe === "") $idEquipe = NULL;
            if ($pays === "") $pays = NULL;
            // Préparer la requête
            $reqPrep = mysqli_prepare($connexion, $requete);
            if($reqPrep) {
                // Faire le lien entre les paramètres de la fonction et les paramètres de la requête (les points d'interrogation)
                mysqli_stmt_bind_param($reqPrep, "ssiis", $nom, $prenom, $idEquipe, $salaire, $pays);
                // Exécuter la requête
                mysqli_stmt_execute($reqPrep);
                // Obtenir le nombre de lignes affectées
                $nbLignes = mysqli_affected_rows($connexion);
                // Retourner le résultat
                if($nbLignes > 0) return "<p>Joueur ajouté avec succés.</p>";
                else return false;
            }
            else return false;
        }
        else return false;
    }

    /**
     * Fonction de recherche d'un joueur par son nom ou son prénom
     * @param  string $critere, critère de recherche : une partie ou la totalité du nom ou prénom
     * @return array|boolean  $resultats|false, tableau associatif du résultat de la requête ou false si aucun joueur trouvé
    */
    function rechercher_joueur($critere) {
        global $connexion;
        $critere = "%".$critere."%";
        $requete = "SELECT joueur.nom As nomJoueur, prenom, equipe.nom As nomEquipe, ville FROM joueur LEFT OUTER JOIN Equipe ON joueur.IdEquipe = equipe.Id WHERE joueur.nom LIKE ? OR prenom LIKE ?";
        // Préparer la requête
        $reqPrep = mysqli_prepare($connexion, $requete);
        if($reqPrep) {
            // Faire le lien entre les paramètres de la fonction et les paramètres de la requête (les points d'interrogation)
            mysqli_stmt_bind_param($reqPrep, "ss", $critere, $critere);
            // Exécuter la requête
            mysqli_stmt_execute($reqPrep);
            // Obtenir le résultat
            $resultat = mysqli_stmt_get_result($reqPrep);
            // Retourner le résultat
            echo "<pre>".var_dump($resultat)."</pre>";
            if (mysqli_num_rows($resultat) > 0) return $resultat;
            else return false;
        }
    }
?>