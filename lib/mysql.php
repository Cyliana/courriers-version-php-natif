<?php

define('DBHOST', 'mysql.xiong.fr');
define('DBPORT', '3306');
define('DBNAME', 'courriers');
define('DBUSER', 'courriers');
define('DBPASSWORD', 'courriers');

class DB
{

    private $connection;

    public $records;

    public function __construct()
    {
        try 
        {
            $this->connection = new PDO('mysql:host='.DBHOST."; port=".DBPORT."; dbname=".DBNAME.";", DBUSER, DBPASSWORD);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } 
        catch (PDOException $e) 
        {
            $msg = 'Erreur PDO...';
            die($msg);
        }
    }
    
    public function getTables()
    {
        $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);

        $sql = 'SHOW TABLES;';

        $result = $this->connection->prepare($sql);
        $result->execute();

        $records = $result->fetchAll();
        $return = [];
        foreach($records as $record)
        {
            array_push($return, $record[0]);
        }
        return($return);
    }

    public function sql($sql, $fetch = "NUM")
    {
        if ($fetch == "NUM") 
        {
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);
        }
        else
        {
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }

        $result = $this->connection->prepare($sql);
        $result->execute();

        $c = explode(' ',$sql)[0];
        if($c == 'SELECT')
        {
             $this->records = $result->fetchAll();
             return($this->records);
        }
    }

    public function fieldsToVars()
    {
        $a = [];
        foreach($this->records[0] as $f=>$v)
        {
            array_push($a,"\$$f=\"$v\";");
        }
        return(implode(' ',$a));
    }

    public function arrayToSql($array)
    {
        $a = [];
        foreach($array as $f=>$v)
        {
            array_push($a,"`$f`=\"$v\"");
        }
        return(implode(', ',$a));
    }
   
}
