<?php

error_log("formulaire.php");

// === requires =============================
require_once('lib/common.php');
require_once('lib/mysql.php');
require_once('lib/page.php');
require_once('lib/session.php');
require_once('lib/html.php');
require_once('lib/errors.php');

// === Init =================================
$HTML = new HTML("Courriers - Connexion");

// ==========================================

// --------------------------------------
$header = new HTML();
    $header->a('','deconnecter.php','Déconnecter',['title'=>"Déconnecter la session et retourner à la page d'identification."]);
    $header->space();
    $header->a('','utilisateur.php',$_SESSION['identite'],['title'=>"Mes informations."]);
    $header->a('','destinataire_liste.php',' Mes destinataires',['title'=>"Mes destinataires."]);
$HTML->header($header->HTML);
// --------------------------------------

$uid = $_SESSION['uid'];
$cmd = (isset($_GET['cmd'])) ? $_GET['cmd'] : '';


$courrier_id="";

if ($cmd == "modifier")
{
    if(isset($_POST['courriers']))
    {
        if(isset($_POST['courriers'][0]))
        {
            $courrier_id = $_POST['courriers'];
        }
    }

    if($courrier_id == "")
    {
        header("Location: liste.php");
    }

    $_SESSION["courrier_id"] = $courrier_id;
}

//var_dump($courrier_id[0]);

$db = new DB();

$sql = "SELECT `objet`, `offre`, `date_envoi`, `date_relance`, `paragraphe1`, `paragraphe2`, `paragraphe3`, `paragraphe4`, `nosref`, `vosref`, `annonce`, `destinataire_id`, `status` FROM courriers WHERE id= {$courrier_id[0]};";

// ----------------------------------------------
if($cmd == "ajouter")
{
    $fields = extractFields($sql,"`");

    foreach ($fields as $f) 
    {
        eval("\$$f='';");
    }
}

// ----------------------------------------------
if($cmd == "modifier")
{
    $courrier = $db->sql($sql, "ASSOC")[0];
    eval($db->fieldsToVars());
}

// ----------------------------------------------
$sql = "SELECT id, CONCAT(`prenom`,' ',`nom`) AS identite FROM destinataires WHERE utilisateur_id={$_SESSION['uid']} ORDER BY nom ASC, prenom ASC;";
$destinataires = $db->sql($sql);
$destinataires_select = [];
foreach ($destinataires as $record) 
{
    $destinataires_select[$record[0]] = $record[1];
}

// ----------------------------------------------
$sql = "SELECT `id`, `libelle` FROM `_status`;";
$status_ = $db->sql($sql);
$status_select = [];
foreach ($status_ as $record) 
{
    $status_select[$record[1]] = $record[1];
}


$HTML->form_('formCourrier', '','POST',["class"=>"formForm"]);
$HTML->input('utilisateur_id','utilisateur_id',"hidden",$_SESSION['uid']);
$HTML->fieldSelect('status', 'status',$status_select,$status,["placeholder"=>"Status","title"=>"Status"]);
$HTML->fieldTextarea('annonce','annonce',$annonce ,["placeholder"=>"Annonce","title"=>"Annonce"]);
$HTML->fieldSelect('destinataire_id', 'destinataire_id', $destinataires_select,$destinataire_id,["placeholder"=>"Destinataire","title"=>"Destinataire."]);
$HTML->fieldInput('nosref', 'nosref', 'text', $nosref, ["placeholder"=>"Nos reférences","title"=>"Saisissez votre référence."]);
$HTML->fieldInput('vosref', 'vosref', 'text', $vosref, ["placeholder"=>"Vos références","title"=>"Saisissez la référence de l'utilisateur."]);
$HTML->fieldInput('objet', 'objet', 'text', $objet, ["placeholder"=>"Objet","title"=>"Objet du message."]);
$HTML->fieldInput('offre', 'offre', 'text', $offre, ["placeholder"=>"Offre","title"=>"Numéro de l'offre."]);
if($cmd == 'modifier')
{
    $HTML->fieldInput('date_envoi', 'date_envoi', 'date', $date_envoi, ["placeholder"=>"Date d'envoi","title"=>"Saisissez la date d'envoi'."]);
}
$HTML->fieldInput('date_relance', 'date_relance', 'date', $date_relance, ["placeholder"=>"Date de relance prévue","title"=>"Saisissez la date de relance prévue."]);
$HTML->fieldTextarea('paragraphe1', 'paragraphe1', $paragraphe1, ["placeholder"=>"Paragraphe 1","title"=>"Saisissez votre premier paragraphe."]);
$HTML->fieldTextarea('paragraphe2', 'paragraphe2', $paragraphe2, ["placeholder"=>"Paragraphe 2","title"=>"Saisissez votre deuxieme paragraphe."]);
$HTML->fieldTextarea('paragraphe3', 'paragraphe3', $paragraphe3, ["placeholder"=>"Paragraphe 3","title"=>"Saisissez votre troisieme paragraphe."]);
$HTML->fieldTextarea('paragraphe4', 'paragraphe4', $paragraphe4, ["placeholder"=>"Paragraphe 4","title"=>"Saisissez votre quatrieme paragraphe."]);

// ----------------------------------------------
if($cmd == "ajouter")
{
    $HTML->submit('', 'Valider',["title" => "Valider vos informations pour créer votre courrier.", "formaction"=>"ajouter.php"]);
}

// ----------------------------------------------
if($cmd == "modifier")
{
    $HTML->submit('', 'Valider',["title" =>"Valider pour enregistrer vos modifications.", "formaction"=>"modifier.php"]);

    $HTML->submit('', 'Supprimer',["title"=>"Supprimer ce courrier.","formaction"=>"supprimer.php"]);
}

// ----------------------------------------------
$HTML->a('', "{$page->referer}.php", "Retour",["title" => "Retourner à la page de connexion.", "formaction"=>"liste.php"]);

$HTML->_form();

$HTML->output();