<?php
    error_log("connecter.php");

    // === requires =============================
    require_once('lib/mysql.php');
    require_once('lib/page.php');
    require_once('lib/session.php');
    require_once('lib/html.php');
    require_once('lib/errors.php');

    // === Init =================================
    $HTML = new HTML("Courriers - Déconnexion");
    print("{$page->current} / {$page->referer}");

    // ==========================================

    $session->close();

    header("Location: index.php");
    