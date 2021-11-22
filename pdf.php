<?php

session_start();

require_once('./lib/pdf.php');
require_once('./lib/mysql.php');

$erreur = 0;

//==========================================================================================

$erreur = (isset($_GET['sid'])) ? $erreur + 0 : $erreur + 1;
if ($erreur == 0) {
    $erreur = ($_GET['sid'] == session_id()) ? $erreur + 0 : $erreur + 2;
}

$erreur = (isset($_GET['id'])) ? $erreur + 0 : $erreur + 4;
if ($erreur == 0) {
    $erreur = (ctype_digit($_GET['id'])) ? $erreur + 0 : $erreur + 8;
}

//==========================================================================================

if ($erreur == 0) {
    $id_courrier = $_GET['id'];

    //--------------
    $db = new DB();

    $courrier = $db->sql("SELECT utilisateur_id, destinataire_id, vosref, nosref, objet, date_envoi, paragraphe1, paragraphe2, paragraphe3, paragraphe4 FROM courriers WHERE courriers.id= $id_courrier;");

    $erreur = (count($courrier) > 0)  ? $erreur + 0 : $erreur + 16;
}

// construire la requête
if ($erreur == 0) {
    $id_utilisateur = $courrier[0][0];

    //--------------
    $db = new DB();

    $utilisateur = $db->sql("SELECT * FROM utilisateurs WHERE id = $id_utilisateur;");

    $erreur = (count($utilisateur) > 0) ? $erreur + 0 : $erreur + 32;
}

// construire la requête
if ($erreur == 0) {
    $id_destinataire = $courrier[0][1];

    //--------------
    $db = new DB();

    $destinataire = $db->sql("SELECT * FROM destinataires WHERE id= $id_destinataire;");

    $erreur = (count($destinataire) > 0) ? $erreur + 0 : $erreur + 64;
}

//==========================================================================================

if ($erreur == 0) {
    header("Content-Type: text/plain");

    //--------------
    $pdf = new PDF();

    $pdf->SetAutoPageBreak(false, 0);
    $pdf->AddPage();

    $style = array(
        'width' => 0.5,
        'cap' => 'butt',
        'join' => 'miter',
        'color' => array(0, 0, 0)
    );
    $pdf->SetLineStyle($style);

    // --- Repères ------------------------------

    $x = 0;
    $y = 99.5;

    // Plis
    $pdf->Line($x, $y, $x + 10, $y);
    $pdf->Line($x, $y + 99.5, $x + 10, $y + 99.5);

    // Marges
    $m1 = 20;
    $m2 = 40;
    $m3 = 110;

    // CSS
    $css  = "<style>\n";
    $css .= "b { font-family: arialnb; } \n";
    $css .= "p { border: 1px solid red; } \n";
    $css .= "</style>\n";


    // === En-tête ==============================

    $pdf->SetFont("arial", '', 11);


    // --- Expéditeur ---------------------------

    $expediteur = $utilisateur[0];
    $prenom     = $expediteur[6];
    $nom        = $expediteur[5];
    $tel        = $expediteur[7];
    $mail       = $expediteur[8];
    $adresse    = str_replace("\n", "<br/>", $expediteur[9]);
    $cp         = $expediteur[10];
    $localite   = $expediteur[11];

    $y = 10;
    $x = $m1;

    $html  = $css;
    $html .= "<b>$prenom $nom</b><br/>";
    $html .= "$adresse<br/>";
    $html .= "$cp $localite<br/><br/>";
    $html .= "Tél: $tel<br/>";
    $html .= $mail;

    $pdf->writeHTMLCell(170, 2.5, $x, $y, $html);




    // --- Destinataire -------------------------

    $destinataire = $destinataire[0];
    $titre        = $destinataire[2];
    $nom          = $destinataire[3];
    $prenom       = $destinataire[4];
    $fonction     = $destinataire[5];
    $fonction2    = ($titre == "M.") ? "le " . $fonction : "la " . $fonction;
    $denomination = $destinataire[6];
    $adresse      = $destinataire[7];
    $cp           = $destinataire[8];
    $localite     = $destinataire[9];

    $titres = [];
    $titres["M."] = "Monsieur";
    $titres["Mme"] = "Madame";
    $titres["Melle"] = "Mademoiselle";

    $titre = $titres[$titre];

    $x = $m3;
    $y = 50;

    $html  = $css;
    $html .= "<b>$titre ";
    $html .= ($nom != '') ? "$prenom $nom" : $fonction2;
    $html .= "</b>";
    $html .= "<br/>$denomination<br/>";
    $html .= "$adresse<br/>";
    $html .= "$cp $localite";

    $html = str_replace('  ', ' ', $html);

    $pdf->SetXY($x, $y);
    $pdf->writeHTMLCell(80, 2.5, $x, $y, $html);


    // --- Lieu et date -------------------------

    $date     = $courrier[0][5];
    $localite = $expediteur[11];

    $a = substr($date, 0, 4);
    $m = substr($date, 5, 2) - 1;
    $j = substr($date, 8, 2) + 0;
    $mois = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];

    $y = 85;

    $html = "$localite, le $j {$mois[$m]} $a";

    $pdf->SetXY($x, $y);
    $pdf->Cell(80, 2.5, $html, 0, 0, "L", false, "", 1);


    // --- Objet/Nos ref/Vos ref ----------------

    $courrier = $courrier[0];
    $vref     = $courrier[2];
    $nref     = $courrier[3];
    $objet    = str_replace('..', '.', trim($courrier[4] . '.'));

    $y = 50;
    $x = $m1;

    $html  = $css;
    $html .= "<b>Nos Réf.</b> : $nref<br/>";
    $html .= "<b>Vos Réf.</b> : $vref<br/>";
    $html .= "<b>Objet</b> : $objet.";

    $pdf->writeHTMLCell(80, 2.5, $x, $y, $html);

    // === Texte ================================

    $je   = $courrier[6];
    $vous = $courrier[7];
    $nous = $courrier[8];
    $slts = $courrier[9];

    $html = trim("$titre,\n$je\n$vous\n$nous\n$slts");

    for ($i = 0; $i < 3; $i++) {
        $html  = str_replace("\r", "", $html);
        $html  = str_replace("\n\n", "\n", $html);
        $html  = str_replace("  ", " ", $html);
    }

    $html_ = explode("\n", $html);

    $html = '';
    foreach ($html_ as $p) {
        $html .= "<p>$p</p>\n";
    }
    $html = $css . $html;
    //   header("Content-Type: text/plain");
    //   var_dump($html);

    $x = $m2;
    $y = 100;

    $pdf->SetFont("times", '', 12);
    $pdf->SetLeftMargin(40);
    $pdf->SetRightMargin(20);
    $pdf->SetXY($x, $y);
    $pdf->writeHTML($html);

    // --- Signature ----------------------------

    $prenom     = $expediteur[6];
    $nom        = $expediteur[5];

    $prenoms = explode("-", $prenom);
    $prenom = "";
    foreach ($prenoms as $p) {
        $prenom .= $p[0] . " ";
    }

    $prenom = trim($prenom);
    $prenom = str_replace(" ", ".", $prenom) . ".";

    $html = "$prenom $nom";

    $x = $m3;
    $y = $pdf->GetY();

    $pdf->SetXY($x, $y + 10);
    $pdf->writeHTML($html, true, false);

    $pdf->Output('courrier.pdf', 'I');
} else {
    header("Content-Type: text/plain");
    print("$erreur\n");
    print($erreur & 4);
    print("\n");

    if (($erreur & 1) == 1)
        print("Pas de session\n");

    if (($erreur & 2) == 2)
        print("session non valide\n");

    if (($erreur & 4) == 4)
        print("Pas de courrier\n");

    if (($erreur & 8) == 8)
        print("Courrier invalide\n");

    if (($erreur & 16) == 16)
        print("Courrier non trouvé\n");

    if (($erreur & 32) == 32)
        print("T'es qui?\n");

    if (($erreur & 64) == 64)
        print("A qui écris-tu?\n");
}
