<?php
    error_log("ajouter.php");

    // === requires =============================
    require_once('lib/common.php');
    require_once('lib/mysql.php');
    require_once('lib/page.php');
    require_once('lib/session.php');
    require_once('lib/html.php');
    require_once('lib/errors.php');
    
    // === Init =================================
    header("Content-Type: text/plain");
    
    // ==========================================
    if($errors->check($page->referer == "formulaire",32768) && $errors->check($session->check(),32768))
    {
        $date = date('Y-m-d');

        eval(arrayToVars($_POST));  
        print_r($_POST);

        $db = new DB();
        $set= $db->arrayToSql($_POST);
        $sql= "INSERT INTO `courriers` SET $set , `date_creation` = $date, `date_modification`= $date;";

        $db->sql($sql);

        print($sql);

    }
