<?php

error_log("destinataire_formulaire.php");

// === requires =============================
require_once('lib/common.php');
require_once('lib/mysql.php');
require_once('lib/page.php');
require_once('lib/session.php');
require_once('lib/html.php');
require_once('lib/errors.php');

// === Init =================================
$HTML = new HTML("Formulaire - Destinataire");

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
    // --------------------------------------
}

$cmd = (isset($_GET['cmd'])) ? $_GET['cmd'] : '';

$db = new DB();

$sql = "SELECT `id`,`titre`,`prenom`,`nom`,`fonction`,`denomination`,`adresse`, `code_postal`, `localite`, `telephone`, `email`, `commentaire` FROM destinataires WHERE id= {$_POST['destinataires'][0]};";

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
    $destinataires = $db->sql($sql, "ASSOC")[0];
    eval($db->fieldsToVars());
}

// ----------------------------------------------

$HTML->form_('formUtilisateur', 'destinataire_modifier.php','POST', ["class"=>"formForm"]);
if($cmd == "modifier")
{
    $HTML->fieldInput('id', 'id', 'hidden', $id);
}
$HTML->fieldSelect('titre', 'titre',["Mme"=>"Madame", "Melle"=>"Mademoiselle", "M."=>"Monsieur"],$titre,["placeholder"=>"Titre"]);
$HTML->fieldInput('nom', 'nom', 'text', $nom, ["placeholder"=>"Nom","title"=>"Saisissez un nom."]);
$HTML->fieldInput('prenom', 'prenom', 'text', $prenom, ["placeholder"=>"Prenom","title"=>"Saisissez un prénom."]);
$HTML->fieldInput('fonction', 'fonction', 'text', $fonction, ["placeholder"=>"Fonction","title"=>"Saisissez la fonction"]);
$HTML->fieldInput('denomination', 'denomination', 'text', $denomination, ["placeholder"=>"Dénomination","title"=>"Saississez la dénomination de l'entreprise."]);
$HTML->fieldInput('adresse', 'adresse', 'text', $adresse, ["placeholder"=>"Adresse","title"=>"Saisissez une adresse."]);
$HTML->fieldInput('localite', 'localite', 'text', $localite, ["placeholder"=>"Ville","title"=>"Saisissez la ville."]);
$HTML->fieldInput('code postal', 'code postal', 'text', $code_postal, ["placeholder"=>"Code Postal","title"=>"Saisissez un code postal."]);
$HTML->fieldInput('telephone', 'telephone', 'text', $telephone, ["placeholder"=>"Téléphone","title"=>"Saisissez un numéro de téléphone."]);
$HTML->fieldInput('email', 'email', 'text', $email, ["placeholder"=>"Email","title"=>"Saisissez un adresse email."]);
$HTML->fieldTextarea('commentaire', 'commentaire', $commentaire, ["placeholder"=>"Commentaire","title"=>"Saisissez un commentaire."]);

// ----------------------------------------------
if($cmd == "ajouter")
{
    $HTML->submit('', 'Valider',["title" => "Valider pour ajouter un destinataire.", "formaction"=>"destinataire_ajouter.php"]);
}

// ----------------------------------------------
if($cmd == "modifier")
{
    $HTML->submit('', 'Valider',["title" =>"Valider pour enregistrer les modifications.", "formaction"=>"destinataire_modifier.php"]);
}

// ----------------------------------------------
$HTML->a('', "{$page->referer}.php", "Retour",["title" => "Retourner à la page précédente.", "formaction"=>"liste.php"]);

$HTML->_form();

$HTML->output();