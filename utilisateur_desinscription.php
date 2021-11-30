<?php
    error_log("desinscription.php");

    // === requires =============================
    require_once('lib/common.php');
    require_once('lib/mysql.php');
    require_once('lib/page.php');
    require_once('lib/session.php');
    require_once('lib/html.php');
    require_once('lib/errors.php');
    
    // ==========================================
    if($errors->check($page->referer == "utilisateur",32768) && $errors->check($session->check(),32768))
    {
  
        $db = new DB();
        $sql= "UPDATE `courriers` SET `status`= \"Supprimé\" WHERE `id`={$_SESSION["courrier_id"]};";

        $db->sql($sql);

        header("Location: index.php");

    }
