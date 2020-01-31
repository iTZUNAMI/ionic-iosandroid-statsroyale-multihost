<?php
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 0);
    
    require_once ('MysqliDb.php');
    require_once ('crawler_new.php');
    
    //cambia se non sono in checkonly con data[tag]
    $tag=strtoupper($_REQUEST['t']);
     
    //data: tag e htmlraw
    $data = json_decode(file_get_contents('php://input'), true);

    
    //da passare a crawler
    $htmlraw=$data['htmlraw'];
    
    $versione=$data['v'];
    $richiestaDa=$data['r']; //android = a  ios = i
    
    $checkonly = $_REQUEST['checkonly'];
    $forzato = $data['forzato'];
  
    
   
    
    //cambio app mostro json con info
    if ($versione && $versione < 11 && $richiestaDa=="a"){
       
        mostrajsonErr(1,0);
        exit;
    }
    if ($versione && $versione < 11 && $richiestaDa=="i"){
       
        mostrajsonErr(0,1);
        exit;
    }
    
    //se presente nel db mostro subito (se vecchio richiedi aggiornamento)
    if ($checkonly!=NULL && $checkonly==1){
        
        
        
        $db = new MysqliDb ('localhost', 'admin_itzunami', 'Hmwd4@71', 'admindb_itzunami');
        $db->where ("tag", $tag);
        $user = $db->getOne("sr_user_remote_client");
        //output json con richiesta di crawling client
        if ($user==NULL){
            
            mostrajson_creaAggiorna($tag);
            
        }else
        {
            
                
                $adesso = date('Y-m-d H:i:s');
                $dopoXore=date("Y-m-d H:i:s", strtotime($user['aggiorna_data_last'].' +24 hours'));

                    
                //se sono passate almeno x ore dall ultimo aggiornamento
                //o ancora in fase 3 ricontrollo e riaggiorno
                if ($adesso > $dopoXore || $user['stato']==3 || $user['stato']==0 || ($forzato!=NULL && $forzato==1)  ){

                   //output json con richiesta di crawling client (per aggiornare)
                   mostrajson_creaAggiorna($tag);
                  
                }else{
                    $source="db_checkonly";
                    //mostro dal db direttamente
                    mostrajson($db,$tag, $source); 
                }
        }
        
    }
    //qua 
    else{

    //nuovo tag
    $tag=strtoupper($data['tag']);
    
    //db
    $db = new MysqliDb ('localhost', 'admin_itzunami', 'Hmwd4@71', 'admindb_itzunami');

    //crawler
    //controllo se già in db
    $db->where ("tag", $tag);
    $user = $db->getOne("sr_user_remote_client");
    //non presente nel nostro db? 
    if ($user==NULL && $data!=NULL){
        
              $source="prim_crw";   
              
              $Crawler = new Crawler($tag,$htmlraw);
              $Crawler->goCrawler();
              
              $dataDB = Array (
                "creato" => $db->now(),
                "stato" => $Crawler->sr_stato,
                "aggiorna_data_last" => $db->now(),
                "tag" => $tag,
                "username" => $Crawler->sr_username,
                "livello" => $Crawler->sr_livello,
                "clan" => $Crawler->sr_clan,
                "clantag" => $Crawler->sr_clantag,
                "chest" => $Crawler->sr_chest,

                "s1" => $Crawler->sr_s1,   
                "s2" => $Crawler->sr_s2,
                "s3" => $Crawler->sr_s3,
                "s4" => $Crawler->sr_s4,
                "s5" => $Crawler->sr_s5,
                "s6" => $Crawler->sr_s6,
                "s7" => $Crawler->sr_s7,
                "s8" => $Crawler->sr_s8,
                "s9" => $Crawler->sr_s9,
                "s10" => $Crawler->sr_s10,
                "s11" => $Crawler->sr_s11

             );
            $id = $db->insert ('sr_user_remote_client', $dataDB);
            if($id){
               // echo 'user was created. Id=' . $id;
            }
              
            }

            else{
                   //aggiorno crawler e db
                   $source="agg_crw";  
                   $Crawler_UP = new Crawler($tag,$htmlraw);
                   $Crawler_UP->goCrawler();

                   $data_UP = Array (
                    "stato" => $Crawler_UP->sr_stato,
                    "aggiorna_data_last" => $db->now(),
                    "username" => $Crawler_UP->sr_username,
                    "livello" => $Crawler_UP->sr_livello,
                    "clan" => $Crawler_UP->sr_clan,
                    "clantag" => $Crawler_UP->sr_clantag,
                    "chest" => $Crawler_UP->sr_chest,

                    "s1" => $Crawler_UP->sr_s1,   
                    "s2" => $Crawler_UP->sr_s2,
                    "s3" => $Crawler_UP->sr_s3,
                    "s4" => $Crawler_UP->sr_s4,
                    "s5" => $Crawler_UP->sr_s5,
                    "s6" => $Crawler_UP->sr_s6,
                    "s7" => $Crawler_UP->sr_s7,
                    "s8" => $Crawler_UP->sr_s8,
                    "s9" => $Crawler_UP->sr_s9,
                    "s10" => $Crawler_UP->sr_s10,
                    "s11" => $Crawler_UP->sr_s11
                    );
                    $db->where ('tag', $tag);
                    //aggiorno solo nuove stats con stato = 1
                    if ($Crawler_UP->sr_stato==1){
                    if ($db->update ('sr_user_remote_client', $data_UP)){
                       // echo $db->count . ' records were updated';
                    }
                    }
                  
                
            }
    //mostro dal db appena aggiornato o creato
    mostrajson($db,$tag, $source); 
    }
    
    
/* funzioni */
    
function mostrajson_creaAggiorna($tag){
    
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
    
    //stato 31 richiesta refresh o aggiunta su statsroyale
    echo'{
              "s": "check",
              "userinfo": {
                "stato" : "31",
                "tag" : "'.$tag.'",
                "err" : "Sorry! wait for new fix!"
               }
            }
            ';
    
}    
    
    
    
function mostrajson($db,$tag,$source){

    $db->where("tag", $tag);
    $user = $db->getOne("sr_user_remote_client");

    
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
    if ($user['clantag']!=""){
    $clanlist = getClanList($user['clantag']);
    }else{
    $clanlist='""';
    }
    
    //dopo ogni 4 ore o nei primi 10 minuti per sicurezza
    $adesso = date('Y-m-d H:i:s');
    $dopoXore=date("Y-m-d H:i:s", strtotime($user['aggiorna_data_last'].' +2 hours'));
    $menoXore=date("Y-m-d H:i:s", strtotime($user['aggiorna_data_last'].' +60 minutes'));
    $puoaggiornare="0";
    if ($adesso > $dopoXore){
      $puoaggiornare="1";  
    }
    
    if ($adesso < $menoXore){
     $puoaggiornare="1";  
    }
    
    
   echo'{
              "s": "'.$source.'",
              "userinfo": {
                "stato" : "'.$user['stato'].'",
                "tag" : "'.$user['tag'].'",
                "username" : '.noSpecial($user['username']).',
                "livello" : '.noSpecial($user['livello']).',
                "clan" : '.noSpecial($user['clan']).',
                "clantag" : '.noSpecial($user['clantag']).',
                "s1" : '.noSpecial($user['s1']).',    
                "s2" : '.noSpecial($user['s2']).',
                "s3" : '.noSpecial($user['s3']).',
                "s4" : '.noSpecial($user['s4']).',
                "s5" : '.noSpecial($user['s5']).',
                "s6" : '.noSpecial($user['s6']).',
                "s7" : '.noSpecial($user['s7']).',
                "s8" : '.noSpecial($user['s8']).',
                "s9" : '.noSpecial($user['s9']).',
                "s10" : '.noSpecial($user['s10']).',
                "s11" : '.noSpecial($user['s11']).',
                "err" : "Sorry! wait for new fix!"    
              },
              "l" : "'.$user['aggiorna_data_last'].'",
              "fl": "'.$puoaggiornare.'",    
              "c" : '.noSpecial($user['chest']).',
              "clanlist": '.$clanlist.'  
            }
            ';
    
    
}

function getClanList($clantag){
    global $db;
      //prendo lista clan
        $clanlist='"0"';
        if ($clantag != "0"){
             $cols = Array ("username", "livello", "tag");
             $db->orderBy("username","asc");
             $db->where("clantag",$clantag);
             $clanlist = $db->JsonBuilder()->get("sr_user_remote_client",50,$cols);
        }
        
        return $clanlist;
}
    


function noSpecial($val){

  //  $s = preg_replace('/[^A-Za-z0-9 !@#$%^&*().]/u','', strip_tags($val));
    $s = json_encode($val);
    return $s;
    
}

function mostrajsonErr($android,$ios){
    
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
              "userinfo": {
                "stato" : "4",
                "android" : "'.$android.'",
                "ios" : "'.$ios.'"
              },
              "nome": "Culo Royale",
              "messaggio" : "Sorry we have moved to a new app...",
              "urlapp" : "https://play.google.com/store/apps/details?id=",
              "urlapp2" : "https://itunes.apple.com/it/app/stats-royale-for-clash-royale/id1268968428?l=it&ls=1&mt=8",
              "urlicona" : "http://itzunami.net/chestroyale/share/img/icona.png",
              "messaggio2" : "Download and install the new app.. thanks!"
          }
            ';
    
}
    
    

?>