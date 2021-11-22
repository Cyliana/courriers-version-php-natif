<?php
    error_log("index.php");
    
    // === requires =============================
    require_once('lib/mysql.php');
    require_once('lib/page.php');
    require_once('lib/session.php');
    require_once('lib/html.php');
    require_once('lib/errors.php');

    // === Init =================================
    $HTML = new HTML("Courriers");
    
    // ==========================================

    $HTML->header("COURRIERS");

    $main = new HTML();
        $main->form_('formLogin','connecter.php');
            $main->fieldInput('identifiant', 'identifiant', 'text', '', ['placeholder'=>"Identifiant", 'title'=>"Votre identifiant.", 'required'=>"required"]);
            $main->fieldInput('motdepasse', 'motdepasse', 'password', '', ['placeholder'=>"Mot de passe", 'title'=>"Votre mot de passe.", 'required'=>"required"]);

            $main->hr();
            $main->div_('cmd',['class'=>'cmd']);
                $main->submit('', 'Se connecter', ['title'=>'Envoyer les informations de connexion.', 'class'=>'button']);
                $main->a('','utilisateur.php', "S'inscrire", ['title'=>"Vous n'avez pas encore de compte, et vous souhaitez vous inscrire.", 'class'=>'button']);
            $main->_div();
        $main->_form();
        $error = (isset($_GET['error'])) ? $_GET['error'] : 0;
        if($error > 0)
        {
            $main->p('',nl2br("Erreur : \n".$errors->getMessages("connecter",$error)),['class'=>'error']);
        }
    $HTML->main($main->HTML, ['style'=>"height: calc(100vh - 50px);"]);

    $HTML->output();