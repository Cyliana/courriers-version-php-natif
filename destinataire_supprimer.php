<?php
    error_log("destinataire_supprimer.php");

    // === requires =============================
    require_once('lib/common.php');
    require_once('lib/mysql.php');
    require_once('lib/page.php');
    require_once('lib/session.php');
    require_once('lib/html.php');
    require_once('lib/errors.php');
    
    // ==========================================
    if($errors->check($page->referer == "destinataire_liste",32768) && $errors->check($session->check(),32768))
    {
        eval(arrayToVars($_POST)); 

        $db = new DB();

        $destinataires = $_POST['destinataires'];
        $ids=[];

        foreach ($destinataires as $id) 
        {
            $c = "`id` = $id";
            array_push($ids, $c);
        }

        $clause = implode(' OR ',$ids);

        var_dump($_POST);
        
        $sql= "UPDATE `destinataires` SET `status`= \"Supprimé\" WHERE $clause;";

        $db->sql($sql);

        header("Location: destinataire_liste.php");


    }
