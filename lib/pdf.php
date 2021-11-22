<?php
require_once('./lib/TCPDF/tcpdf.php');

class PDF extends TCPDF
{
    public function Header()
    {
    }

    public function Footer()
    {
    }

    public function Output($name = 'doc.pdf', $dest = 'I')
    {
        if (isset($_GET['sid'])) {
            if ($_GET['sid'] == session_id()) {
                parent::Output($name, $dest);
            } else {
                header("Content-Type: Text/plain");
                print('session non valide');
            }
        } else {
            header("Content-Type: Text/plain");
            print('Pas de sid');
        }
    }
}
