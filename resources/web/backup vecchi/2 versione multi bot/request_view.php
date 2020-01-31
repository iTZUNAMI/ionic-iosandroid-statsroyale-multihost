<?php
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 0);
    
    //show json user se esiste, altrimenti richiesta crawler e poi mostra
    require_once ('MysqliDb.php');
    include ('request_crawler.php');
    
    $tagr=$_REQUEST['t'];
    $forza = $_REQUEST['f'];
    
    //da cambiare in base al server
    $oraserver=date("Y-m-d H:i:s",strtotime(date('Y-m-d H:i:s').' +0 hours'));
    
     if ($test!=NULL && $test==1){
       $tagr="2YJUJQLQ";
       echo "<br>Data Server ";
        $date = new DateTime(date('Y-m-d H:i:s'), new DateTimeZone('Europe/Rome'));
        echo $date->format('Y-m-d H:i:s');
        echo "<br>Data corretta per questo Server ";
        echo "<strong>".$oraserver."</strong>";
        echo "<br><br>";
    }

    
    
    //rimetto http
    $url="http://statsroyale.com/profile/";
    $tag=strtoupper($tagr); 
    

    //$db = new MysqliDb ('5.79.71.15', 'admin_itzunami', 'Hmwd4@71', 'admindb_itzunami');
    //$db = new MysqliDb ('localhost', 'admin_itzunami', 'Hmwd4@71', 'admindb_itzunami');
    //$db = new MysqliDb ('localhost', '', '', 'my_uptiki');
      
    //controllo se già in db
    //$db->where ("tag", $tag);
    //$user = $db->getOne("sr_user");
    //non presente nel nostro db? 
    //crawler
    //se hash corretto ma problemi nel parsing craweler stato 0
    //se presente su statsroyale ok stato 1 e inserisco tutto
    //se hash sbagliato errore 2
    
    
    $Crawler = new Crawler($url,$tag);
    $Crawler->goCrawler();
    
     $data = Array (
            "creato" => $oraserver,
            "stato" => $Crawler->sr_stato,
            "aggiorna_data_last" => $oraserver,
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
    
    //ora devo inviarli su itzunami (con inserimento e deco)
     //due modi, invio da curl post  e poi redirect pagina id
     //redirect con dati in get pagina id
   
    $inviare=noSpecial($data);
 
    if ($test!=NULL && $test==1){
        echo $inviare;
        exit;
    }
    
    header("Location: http://itzunami.net/chestroyale/getDataRemote.php?t=$tag&data=$inviare");
    

function noSpecial($val)
    {
  // array map per convertire numeri in stringhe
    $s = json_encode(array_map('strval', $val));
    return $s;
    }

function now()
    {
        return date('Y-m-d H:i:s');
    }
    

    

?>