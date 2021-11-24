<?php
    error_log("liste_destinataire.php");

    // === requires =============================
    require_once('lib/mysql.php');
    require_once('lib/page.php');
    require_once('lib/session.php');
    require_once('lib/html.php');
    require_once('lib/errors.php');

    // === Init =================================
    $HTML = new HTML("Destinataires - Liste");
    
    // ==========================================

    if($errors->check($session->check(),32768))
    {
        $uid = $_SESSION["uid"];
        // --------------------------------------
        $header = new HTML();
            $header->a('','deconnecter.php','Déconnecter',['title'=>"Déconnecter la session et retourner à la page d'identification."]);
            $header->space();
            $header->a('','utilisateur.php',$_SESSION['identite'],['title'=>"Mes informations."]);
        $HTML->header($header->HTML);

        $HTML->form_("","","POST", ["class"=>"formList"]);
        // --------------------------------------
        $main = new HTML();
            $db = new DB();
            $destinataires = $db->sql("SELECT `titre`,`prenom`,`nom`,`fonction`,`denomination`,`localite` FROM `destinataires` WHERE utilisateur_id = $uid;");
            $main->tableFilled('destinataires',['Titre','Prénom','Nom','Fonction','Dénomination','Localité'],$destinataires);
        $HTML->main($main->HTML);

        // --------------------------------------
        $footer = new HTML();

        $footer->submit('', 'Ajouter', ['title'=>'Créer un nouveau destinataire.', 'class'=>'button', "formaction"=>'destinataire_formulaire.php?cmd=ajouter']);
        $footer->submit('', 'Modifier', ['title'=>'Modifier les destinataires sélectionnés.', 'class'=>'button', "formaction"=>"destinataire_formulaire.php?cmd=modifier"]);
        $footer->submit('', 'Supprimer', ['title'=>'Supprimer les destinataires sélectionnés.', 'class'=>'button', "formaction"=>"destinataire_supprimer.php"]);

        $HTML->footer($footer->HTML,['class'=>'cmd']);
        $HTML->_form();
    }

    $HTML->output();