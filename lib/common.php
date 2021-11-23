<?php
function extractFields($str="",$delimiter="'")
{
    $mots = [];
    $mot = "";
    $flag = false;
    for ($i=0; $i < strlen($str); $i++) 
    {
        $c =substr($str, $i, 1);        
        //===========================================
        if ($c == "`" && $flag == false) 
        {
            $flag = true;
            $i++;
            $c =substr($str, $i, 1);
        }
        if ($flag == true && $c != "`") 
        {
            $mot.= $c;
        }
        //===========================================
        if ($c == "`" && $flag == true) 
        {
            $flag = false;
            array_push($mots, $mot);
            $mot = "";
        }
    }
    return ($mots);
}

function arrayToVars($array)
{
    $a = [];
    foreach($array as $f=>$v)
    {
        array_push($a,"\$$f=\"$v\";");
    }
    return(implode(' ',$a));
}