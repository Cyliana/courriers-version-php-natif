<?php
    error_log("supprimer.php");

    // === requires =============================
    require_once('lib/common.php');
    require_once('lib/mysql.php');
    require_once('lib/page.php');
    require_once('lib/session.php');
    require_once('lib/html.php');
    require_once('lib/errors.php');
    
    // ==========================================
    if($errors->check($page->referer == "formulaire",32768) && $errors->check($session->check(),32768))
    {
        $date = date('Y-m-d');

        eval(arrayToVars($_POST));  
        $_POST["date_envoi"] = ($_POST["date_envoi"]=="") ? "NULL" : $_POST["date_envoi"];
        $_POST["date_relance"] = ($_POST["date_relance"]=="") ? "NULL" : $_POST["date_relance"];
        
        $db = new DB();
        $sql= "UPDATE `courriers` SET `status`= \"Supprimé\" WHERE `id`={$_SESSION["courrier_id"]};";

        $db->sql($sql);

        header("Location: liste.php");


    }
