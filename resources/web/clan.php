<?php

   ini_set('error_reporting', E_ALL);
   ini_set('display_errors', 0);
    
    require_once ('MysqliDb.php');
    include ('common.php');

    $statocr=$_REQUEST['s'];
    if ($statocr==NULL) $statocr=1;
     //db
    $db = new MysqliDb ('localhost', 'admin_itzunami', 'Hmwd4@71', 'admindb_itzunami');
    
    $db->orderBy("id","desc");
    $db->where("stato",$statocr);
    $results = $db->JsonBuilder()->get('sr_user_remote_client',25);
    
    //echo ($results);
    
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
    
    
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
    
    echo'{
        "clan": '.$results.'
    }';
    
    
?>