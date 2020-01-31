<?php

    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 0);

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

$tagr=$_REQUEST['t'];
$tag=strtoupper($tagr);
$Data = $_REQUEST['data'];
$decodeData = json_decode($Data);

$source="";

require_once ('MysqliDb.php');
$db = new MysqliDb ('localhost', 'admin_itzunami', 'Hmwd4@71', 'admindb_itzunami');
//tabella nuova
$db->where('tag', $tag);
$user = $db->getOne("sr_user_remote");
//se primo inserimento

if ($user==NULL){
    
    $source="pr_crw"; 
    $edajje_nuovo = Array (
            "creato" => $decodeData->creato,
            "stato" => $decodeData->stato,
            "aggiorna_data_last" => $decodeData->aggiorna_data_last,
            "tag" => $tag,
            "username" => $decodeData->username,
            "livello" => $decodeData->livello,
            "clan" => $decodeData->clan,
            "clantag" => $decodeData->clantag,
            "chest" => $decodeData->chest,
                
            "s1" => $decodeData->s1,   
            "s2" => $decodeData->s2,
            "s3" => $decodeData->s3,
            "s4" => $decodeData->s4,
            "s5" => $decodeData->s5,
            "s6" => $decodeData->s6,
            "s7" => $decodeData->s7,
            "s8" => $decodeData->s8,
            "s9" => $decodeData->s9,
            "s10" => $decodeData->s10,
            "s11" => $decodeData->s11
            );
    
    
$id = $db->insert('sr_user_remote', $edajje_nuovo);
if($id){
   //echo 'user was created. Id=' . $id;
}else {
  //  echo $db->getLastError();
}
}
//e' un aggiornamento
else{
       
      $source="agg_crw"; 
      $edajje_aggiorna = Array (
           //non ci sta creato
            "stato" => $decodeData->stato,
            "aggiorna_data_last" => $decodeData->aggiorna_data_last,
            
            "username" => $decodeData->username,
            "livello" => $decodeData->livello,
            "clan" => $decodeData->clan,
            "clantag" => $decodeData->clantag,
            "chest" => $decodeData->chest,
                
            "s1" => $decodeData->s1,   
            "s2" => $decodeData->s2,
            "s3" => $decodeData->s3,
            "s4" => $decodeData->s4,
            "s5" => $decodeData->s5,
            "s6" => $decodeData->s6,
            "s7" => $decodeData->s7,
            "s8" => $decodeData->s8,
            "s9" => $decodeData->s9,
            "s10" => $decodeData->s10,
            "s11" => $decodeData->s11
            );
    
    $db->where('tag', $tag);
    
    if ($db->update('sr_user_remote', $edajje_aggiorna)){
        // echo $db->count . ' records were updated';
    }else {
   // echo $db->getLastError();
    }
}



//header("Location: http://itzunami.net/chestroyale/view_new.php?d=1&tag=$tag&source=$source");

    


?>