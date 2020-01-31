<?php
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 0);
    
    require_once ('MysqliDb.php');
    
    $display=$_REQUEST['d'];
    $source=$_REQUEST['source'];
    $tagback=$_REQUEST['tag'];
    
    $tagr=$_REQUEST['t'];
    $versione=$_REQUEST['v'];
    $forza = $_REQUEST['f'];
    $richiestaDa=$_REQUEST['r']; //android = a  ios = i
    
    //cambio app mostro json con info
    if ($versione && $versione > 11 && $richiestaDa=="a"){
       
        mostrajsonErr(1,0);
        exit;
    }
    if ($versione && $versione > 11 && $richiestaDa=="i"){
       
        mostrajsonErr(0,1);
        exit;
    }
    
    $tag=strtoupper($tagr); 
    //db
    $db = new MysqliDb ('localhost', 'admin_itzunami', 'Hmwd4@71', 'admindb_itzunami');

    //crawler
    //se hash corretto ma problemi nel parsing craweler stato 0
    //se presente su statsroyale ok stato 1 e inserisco tutto
    //se hash sbagliato errore 2
    
    //Seleziono un proxy random
    $min=1;
    $max=3;
    $server= rand($min,$max);
     $urlRm1="http://scimmiablu.altervista.org/royale/request_view.php?t=$tag";
     $urlRm2="http://fsmirror.altervista.org/royale/request_view.php?t=$tag";
     $urlRm3="http://minizip.altervista.org/royale/request_view.php?t=$tag";
   //  $urlRm4="http://agorainfo.it/royale/request_view.php?t=$tag";
     
     if ($server==1){$urlRm=$urlRm1;}
     if ($server==2){$urlRm=$urlRm2;}
     if ($server==3){$urlRm=$urlRm3;}
     if ($server==4){$urlRm=$urlRm4;}
     
     //se ritorno qua dal getDataRemote con i dati aggiornati mostro dal db
     if ($display!=NULL && $display==1){
         
         mostrajson($db,$tagback, $source);
         
     }
     //altrimenti vari casi
     else{
            //controllo se già in db
            $db->where ("tag", $tag);
            $user = $db->getOne("sr_user_remote");
            //non presente nel nostro db? 
         
         
            if ($user==NULL){
              $source="prim_crw";   
              avviaCrawlerRemoto($urlRm);
              sleep(2);
            }
            //altrimenti esiste nel nostro db in vari casi
            //stato 0 errore generico tipo statsroyale down
            //stato 1 ok mostro
            //stato 2 errore hash
            //stato 3 nella prima inizializzazione diventa stato 1
            else{
                //se al momento della richiesta siamo entro 24 (o x) ore mostro dal db direttamente
                //altrimenti faccio un aggiornamento del crawler /refresh e aggiornodb di nuovo

                $adesso = date('Y-m-d H:i:s');
                $dopoXore=date("Y-m-d H:i:s", strtotime($user['aggiorna_data_last'].' +24 hours'));

                    if ($forza!=NULL && $forza==2){
                        echo $adesso;
                        echo "<br>";
                        echo $dopoXore;
                        exit;
                    }
                //se sono passate almeno x ore dall ultimo aggiornamento
                //o ancora in fase 3 ricontrollo e riaggiorno
                if ($adesso > $dopoXore || $user['stato']==3 || $user['stato']==0 || ($forza!=NULL && $forza==1)  ){

                    //aggiorno crawler e db
                   $source="agg_crw";  
                   avviaCrawlerRemoto($urlRm);
                   sleep(2);
                }else{
                    $source="db";
                    
                }



            }
            
     }       
    
    //mostro dal db appena aggiornato o creato
    mostrajson($db,$tag, $source); 
    
    
    
    
function avviaCrawlerRemoto($url){
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);	
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    curl_close($ch);
  //   header("Location: $url");

}
    
function mostrajson($db,$tag,$source){

    $db->where("tag", $tag);
    $user = $db->getOne("sr_user_remote");

    
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
    
    $adesso = date('Y-m-d H:i:s');
    $dopoXore=date("Y-m-d H:i:s", strtotime($user['aggiorna_data_last'].' +6 hours'));
    $puoaggiornare="0";
    if ($adesso > $dopoXore){
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
                "s11" : '.noSpecial($user['s11']).'
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
             $clanlist = $db->JsonBuilder()->get("sr_user_remote",50,$cols);
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
              "nome": "Please update the app to the new version!",
              "messaggio" : "This old version is not working. Android already online. iOS version soon wait two days!",
              "urlapp" : "https://play.google.com/store/apps/details?id=io.ionic.tzunami.statsroyale",
              "urlapp2" : "https://itunes.apple.com/it/app/stats-royale-for-clash-royale/id1268968428?l=it&ls=1&mt=8",
              "urlicona" : "http://itzunami.net/chestroyale/share/img/icona.png",
              "messaggio2" : "Download and install the new app.. thanks!"
          }
            ';
    
}
    
    

?>