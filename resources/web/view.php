<?php
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 0);
    
    //show json user se esiste, altrimenti richiesta crawler e poi mostra
    require_once ('MysqliDb.php');
    include ('common.php');
    include ('crawler.php');
    
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
    //rimetto https 
    $url="https://statsroyale.com/profile/";
    $tag=strtoupper($tagr); 
    //RVL0YUYZ invalido
    //2YJUJQLQ ok
    //20PV0U08 da mettere
    //non posso aggiornare 2LJYPCLRY
    

    //db
    $db = new MysqliDb ('localhost', 'admin_itzunami', 'Hmwd4@71', 'admindb_itzunami');

      
    //controllo se già in db
    $db->where ("tag", $tag);
    $user = $db->getOne("sr_user");
    //non presente nel nostro db? 
    //crawler
    //se hash corretto ma problemi nel parsing craweler stato 0
    //se presente su statsroyale ok stato 1 e inserisco tutto
    //se hash sbagliato errore 2

    if ($user==NULL){
        
        //primo inserimento
        $Crawler = new Crawler($url,$tag);
        $Crawler->goCrawler();
        //echo "<br>stato ".$Crawler->sr_stato;
        //echo "<br>agg ".$Crawler->sr_aggiorna;
        //echo $Crawler->outputhtml;

        $data = Array (
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
        $id = $db->insert ('sr_user', $data);
        if($id){
           // echo 'user was created. Id=' . $id;
        }
      
        mostrajson("pr_cr",$Crawler,$adesso);
        
        
    }
    //altrimenti esiste nel nostro db in vari casi
    //stato 0 errore generico tipo statsroyale down
    //stato 1 ok mostro
    //stato 2 errore hash
    //stato 3 nella prima inizializzazione diventa stato 1
    else{
        
      
        
        
        //se al momento della richiesta siamo entro 5 (o x) ore mostro dal db direttamente
        //altrimenti faccio un aggiornamento del crawler /refresh e aggiornodb di nuovo
        //8 ore
        $adesso = date('Y-m-d H:i:s');
        $dopo5ore=date("Y-m-d H:i:s", strtotime($user['aggiorna_data_last'].' +5 hours'));
        
            if ($forza!=NULL && $forza==2){
                echo $adesso;
                echo "<br>";
                echo $dopo5ore;
                exit;
            }
        //se sono passate almeno 5 ore dall ultimo aggiornamento
        //o ancora in fase 3 ricontrollo e riaggiorno
        if ($adesso > $dopo5ore || $user['stato']==3 || $user['stato']==0 || ($forza!=NULL && $forza==1)  ){
            
            //aggiorno crawler e db
            
            $Crawler_UP = new Crawler($url,$tag);
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
            if ($db->update ('sr_user', $data_UP)){
               // echo $db->count . ' records were updated';
            }
            else{
               // echo 'update failed: ' . $db->getLastError();
            }
            //mostro json dal crawler aggiornato
          
            mostrajson("cr",$Crawler_UP,$adesso);
            
            
        }
        else {
        
        //mostro json dal db e li metto in oggetto tipo crawler 
           $UserToJson->sr_stato=$user['stato'];
           $UserToJson->sr_tag=$user['tag'];
           $UserToJson->sr_username=$user['username'];
           $UserToJson->sr_livello=$user['livello'];
           $UserToJson->sr_clan=$user['clan'];
           $UserToJson->sr_clantag=$user['clantag'];
           $UserToJson->sr_chest=$user['chest'];
           
           $UserToJson->sr_s1=$user['s1'];
           $UserToJson->sr_s2=$user['s2'];
           $UserToJson->sr_s3=$user['s3'];
           $UserToJson->sr_s4=$user['s4'];
           $UserToJson->sr_s5=$user['s5'];
           $UserToJson->sr_s6=$user['s6'];
           $UserToJson->sr_s7=$user['s7'];
           $UserToJson->sr_s8=$user['s8'];
           $UserToJson->sr_s9=$user['s9'];
           $UserToJson->sr_s10=$user['s10'];
           $UserToJson->sr_s11=$user['s11'];
    
           mostrajson("d",$UserToJson,$user['aggiorna_data_last']);;
           
        }

    
    }
    
function getClanList($clantag){
    global $db;
      //prendo lista clan
        $clanlist='"0"';
        if ($clantag != "0"){
             $cols = Array ("username", "livello", "tag");
             $db->orderBy("username","asc");
             $db->where("clantag",$clantag);
             $clanlist = $db->JsonBuilder()->get("sr_user",50,$cols);
        }
        
        return $clanlist;
}
    
function mostrajson($source,$craw,$aggiorna_data_last){
    
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
    if ($craw->sr_clantag!=""){
    $clanlist = getClanList($craw->sr_clantag);
    }else{
    $clanlist='""';
    }
    
   echo'{
              "s": "'.$source.'",
              "userinfo": {
                "stato" : "'.$craw->sr_stato.'",
                "tag" : "'.$craw->sr_tag.'",
                "username" : '.noSpecial($craw->sr_username).',
                "livello" : '.noSpecial($craw->sr_livello).',
                "clan" : '.noSpecial($craw->sr_clan).',
                "clantag" : '.noSpecial($craw->sr_clantag).',
                "s1" : '.noSpecial($craw->sr_s1).',    
                "s2" : '.noSpecial($craw->sr_s2).',
                "s3" : '.noSpecial($craw->sr_s3).',
                "s4" : '.noSpecial($craw->sr_s4).',
                "s5" : '.noSpecial($craw->sr_s5).',
                "s6" : '.noSpecial($craw->sr_s6).',
                "s7" : '.noSpecial($craw->sr_s7).',
                "s8" : '.noSpecial($craw->sr_s8).',
                "s9" : '.noSpecial($craw->sr_s9).',
                "s10" : '.noSpecial($craw->sr_s10).',
                "s11" : '.noSpecial($craw->sr_s11).'
              },
              "l": "'.$aggiorna_data_last.'",
              "c": '.noSpecial($craw->sr_chest).',
              "clanlist": '.$clanlist.'  
            }
            ';
    
    
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