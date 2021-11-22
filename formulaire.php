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

$uid = $_SESSION['uid'];

$cmd = (isset($_GET['cmd'])) ? $_GET['cmd'] : '';

$db = new DB();

$sql = "SELECT `objet`, `offre`,  DATE(date_envoi) AS `date_envoi`, DATE(date_relance) AS `date_relance`, `paragraphe1`, `paragraphe2`, `paragraphe3`, `paragraphe4`, `nosref`, `vosref`, `annonce`, `destinataire_id` FROM courriers WHERE id=$uid;";

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

$HTML->form_('formUtilisateur', 'modifier.php');
$HTML->fieldSelect('destinataire', 'destinataire', $destinataires_select, $courrier["destinataire_id"],["placeholder"=>"Destinataire","title"=>"Destinataire."]);
$HTML->fieldInput('nosref', 'nosref', 'text', $nosref, ["placeholder"=>"Nos reférences","title"=>"Saisissez votre référence."]);
$HTML->fieldInput('vosref', 'vosref', 'text', $vosref, ["placeholder"=>"Vos références","title"=>"Saisissez la référence de l'utilisateur."]);
$HTML->fieldInput('objet', 'objet', 'text', $objet, ["placeholder"=>"Objet","title"=>"Objet du message."]);
$HTML->fieldInput('offre', 'offre', 'text', $offre, ["placeholder"=>"Offre","title"=>"Numéro de l'offre."]);
$HTML->fieldInput('date_envoi', 'date_envoi', 'date', $date_envoi, ["placeholder"=>"Date de creation","title"=>"Saisissez la date de création."]);
$HTML->fieldInput('date_relance', 'date_relance', 'date', $date_relance, ["placeholder"=>"Date de relance","title"=>"Saisissez la date de relance."]);
$HTML->fieldTextarea('paragraphe1', 'paragraphe1', $paragraphe1, ["placeholder"=>"Paragraphe 1","title"=>"Saisissez votre premier paragraphe."]);
$HTML->fieldTextarea('paragraphe2', 'paragraphe2', $paragraphe2, ["placeholder"=>"Paragraphe 2","title"=>"Saisissez votre deuxieme paragraphe."]);
$HTML->fieldTextarea('paragraphe3', 'paragraphe3', $paragraphe3, ["placeholder"=>"Paragraphe 3","title"=>"Saisissez votre troisieme paragraphe."]);
$HTML->fieldTextarea('paragraphe4', 'paragraphe4', $paragraphe4, ["placeholder"=>"Paragraphe 4","title"=>"Saisissez votre quatrieme paragraphe."]);

// ----------------------------------------------
if($cmd == "ajouter")
{
    $HTML->submit('', 'Valider',["title" => "Valider vos informations pour vous inscrire.", "formaction"=>"ajouter.php"]);
}

// ----------------------------------------------
if($cmd == "modifier")
{
    $HTML->submit('', 'Valider',["title" =>"Valider pour enregistrer vos modifications.", "formaction"=>"modifier.php"]);
}

// ----------------------------------------------
$HTML->a('', "{$page->referer}.php", "Retour",["title" => "Retourner à la page de connexion.", "formaction"=>"liste.php"]);

$HTML->_form();

$HTML->output();