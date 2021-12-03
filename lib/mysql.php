<?php

use Symfony\Component\Validator\Constraints\Length;

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

    public function sql($sql, $values=[], $types=[], $fetch = "NUM")
    {
        if ($fetch == "NUM") 
        {
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);
        }
        else
        {
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        //---------------------------------------
        $valuesTypes=[];

        foreach ($types as $k=>$row) 
        {
            $field = $row[0];
            $type = $row[1];
            $null = $row[2];

            if($null == "YES")
            {
                $valuesTypes[$k]= 0;
            }
            else
            {
                $refs=['timestamp'=>2,'datetime'=>2,'varchar'=>2,'text'=>2,'date'=>2,'year'=>2,'json'=>2,'int'=>1];

                foreach ($refs as $key =>$ref) 
                {
                    if(strpos($type,$key)!== false)
                    {
                        $valuesTypes[$k] = $ref;
                        break;
                    }
                }
            }    
        }

        //---------------------------------------

        $PARAMS=[];
        array_push($PARAMS, PDO::PARAM_NULL);
        array_push($PARAMS, PDO::PARAM_INT);
        array_push($PARAMS, PDO::PARAM_STR);

        $result = $this->connection->prepare($sql);

        $i = 0;
        foreach($values as $v)
        {
            $t = $valuesTypes[$i];
            $result->bindValue($i+1,$v,$PARAMS[$t]);
            $i++;
        }
        $result->execute();

        $c = explode(' ',$sql)[0];
        if($c == 'SELECT')
        {
            $this->records = $result->fetchAll();
            return($this->records);
        }
        if($c == 'SHOW')
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
            array_push($a,"`$f`=?");
        }
        return(implode(', ',$a));
    }
   
}
