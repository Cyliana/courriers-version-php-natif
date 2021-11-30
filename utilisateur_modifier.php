<?php
    error_log("utilisateur_modifier.php");

    // === requires =============================
    require_once('lib/errors.php');
    require_once('lib/common.php');
    require_once('lib/mysql.php');
    require_once('lib/page.php');
    require_once('lib/session.php');
    require_once('lib/html.php');
    
    
    // ==========================================

    error_log("test 1 =========================================");
    if($errors->check($page->referer == "utilisateur",32768) && $errors->check($session->check(),32768))
    {
        $db = new DB();

        if($_POST['mot_de_passe'] != '')
        {
            if(($_POST['nouveau_mot_de_passe'] != '') && $_POST['confirmation_mot_de_passe'] != '')        
            {
                if(($_POST['nouveau_mot_de_passe']) == ($_POST['confirmation_mot_de_passe']))
                {
                    $sql = "SELECT `mot_de_passe` FROM `utilisateurs` WHERE id={$_SESSION['uid']};";
                    
                    $mdp = $db->sql($sql);

                    //print_r($mdp[0][0]);

                    if($_POST['mot_de_passe'] == $mdp[0][0])
                    {   
                        $_POST['mot_de_passe'] = $_POST['nouveau_mot_de_passe'];
                    }
                }
            }
        }
        if($_POST['mot_de_passe'] == "")
        {
            unset($_POST['mot_de_passe']);
        }
        
        unset($_POST['nouveau_mot_de_passe']);
        unset($_POST['confirmation_mot_de_passe']);

        eval(arrayToVars($_POST));   // eval exécute les chaines de caractères de arrayToVars en code.(en variables)

        $set= $db->arrayToSql($_POST);

        //print($set);
    
        $sql = "UPDATE `utilisateurs` SET $set WHERE id={$_SESSION['uid']};";
        $db->sql($sql);

        header("Location: liste.php");
    }