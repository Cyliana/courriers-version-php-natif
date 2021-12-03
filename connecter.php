<?php
    error_log("connecter.php");

    // === requires =============================
    require_once('lib/mysql.php');
    require_once('lib/page.php');
    require_once('lib/session.php');
    require_once('lib/html.php');
    require_once('lib/errors.php');

    // === Init =================================
        $HTML = new HTML("Courriers - Connexion");

    // ==========================================

    $identifiant = "";
    $motdepasse = "";

    if($errors->check(count($_POST)== 2,6))
    {
        if($errors->check(isset($_POST['identifiant']),1))
        {
            $identifiant = $_POST['identifiant'];
            $errors->check($identifiant != "",2);
        }

        if ($errors->check($_POST['motdepasse'],4))
        {
            $motdepasse = $_POST['motdepasse'];
            $errors->check($motdepasse != "",8);
        }
    }

    // ==========================================
    if ($errors->code == 0)
    {
        error_log("\$errors->code = {$errors->code};");
        $db = new DB();

        $sql = "SHOW COLUMNS FROM `_connecter_utilisateur`;";
        $types=$db->sql($sql);

        $sql = "SELECT * FROM `_connecter_utilisateur` WHERE `identifiant`=? AND `mot_de_passe`=?;";
        $utilisateur = $db->sql($sql,$_POST,$types);
        

        error_log(count($utilisateur));
        // --------------------------------------
        if($errors->check(count($utilisateur) == 1,16))
        {
            error_log("\$errors->code = {$errors->code};");

            $sql = "SELECT `id`,`nom`,`prenom` FROM `utilisateurs` WHERE `identifiant` = ? AND `mot_de_passe` = ?;";
            $utilisateur = $db->sql($sql,$_POST, $types);
            $uid = $utilisateur[0][0];
            $session->open($uid);
            $_SESSION['identite'] = $utilisateur[0][1].' '.$utilisateur[0][2];

//            $HTML->output();
            header("Location: liste.php");
        }
        
        error_log("\$errors->code = {$errors->code};");
        error_log("\$errors->text = {$errors->text};");
    }

    if($errors->code != 0)
    {
        error_log("\$errors->code = {$errors->code};");
        error_log("\$errors->text = {$errors->text};");

        $HTML->innerHTML("Erreur {$errors->code} :\n{$errors->text}");
        $HTML->output();

        header("Location: index.php?error={$errors->code}");
    }
