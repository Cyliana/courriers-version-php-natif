<?php
    class Page
    {
        public $referer;
        public $current;

        public function __construct()
        {
            $this->referer = '';
            if(isset($_SERVER['HTTP_REFERER']))
            {
                $path = explode('.php',$_SERVER['HTTP_REFERER']);
                $path = explode('/', $path[0]);
                $this->referer = end($path);
            }

            $this->current = '';
            if(isset($_SERVER['PHP_SELF']))
            {
                $path = explode('.php',$_SERVER['PHP_SELF']);
                $path = explode('/', $path[0]);
                $this->current = end($path);
            }
        }
    }

    $page = new Page();