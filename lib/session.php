<?php
    class Session
    {
        public $origin;

        public function __construct()
        {
            error_log("\$session->__construct();");

            session_start();
        }

        public function open($uid)
        {
            error_log("\$session->open();");

            session_regenerate_id(false);            
            $_SESSION['uid'] = $uid;
        }

        public function close()
        {
            error_log("\$session->close();");

            // Détruit toutes les variables de session
            $_SESSION = array();

            // Si vous voulez détruire complètement la session, effacez également
            // le cookie de session.
            // Note : cela détruira la session et pas seulement les données de session !
            if (ini_get("session.use_cookies"))
            {
                $params = session_get_cookie_params();
                setcookie(  session_name(),
                            '',
                            time() - 12000,
                            $params["path"],
                            $params["domain"],
                            $params["secure"],
                            $params["httponly"]);
            }

            // Finalement, on détruit la session.
            session_destroy();
        }

        public function check()
        {
            error_log("\$session->check();");

            $uid = (isset($_SESSION['uid'])) ? $_SESSION['uid'] : 0;
            $sid = (isset($_COOKIE['PHPSESSID'])) ? $_COOKIE['PHPSESSID'] : '';

            return ($sid == session_id() && $uid != 0) ? true : false;
        }
    }

    $session = new Session;
