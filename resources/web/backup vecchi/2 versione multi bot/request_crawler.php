<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 0);

class Crawler
{
	var $curl;
	private $dim=0;
        
	var $url="";
        var $urlrefresh="";
        var $outputhtml="";
        var $outputjson_aggiorna="";
        
        
        //var userinfo
        var $sr_tag="";
        var $sr_username="";
        var $sr_livello="";
        var $sr_clan="";
        var $sr_clantag="";
        //chests
        var $sr_chest = "";
        
        //stato crawling url
        var $sr_stato = 0;
        var $sr_aggiorna = 0;
        
        
        //stats
        var $sr_s1="";
        var $sr_s2="";
        var $sr_s3="";
        var $sr_s4="";
        var $sr_s5="";
        var $sr_s6="";
        var $sr_s7="";
        var $sr_s8="";
        var $sr_s9="";
        var $sr_s10="";
        var $sr_s11="";
        
	function dimensione()
	{
		return $this->dim;
	}
	
	
	function __construct($urlbase,$tag)
	{		
		$this->curl = curl_init();
                $this->sr_tag = strtoupper($tag);
                $this->url = $urlbase.$tag;
                $this->urlrefresh = $urlbase.$tag."/refresh";
	}
	//This is used for login.
	function logIn($loginActionUrl,$parameters)
	{
			curl_setopt ($this->curl, CURLOPT_URL,$loginActionUrl);	
			curl_setopt ($this->curl, CURLOPT_POST, 1);	
			curl_setopt ($this->curl, CURLOPT_POSTFIELDS, $parameters);	
			curl_setopt ($this->curl, CURLOPT_COOKIEJAR, 'cookie.txt');	
			curl_setopt ($this->curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($this->curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);			
			curl_exec ($this->curl);						
	}
	public function getContent()
	{
            // $ip=$this->get_client_ip();
            //curl_setopt($this->curl, CURLOPT_HTTPHEADER, array("X-Forwarded-For: $ip"));
		curl_setopt($this->curl, CURLOPT_URL, $this->url);	
		curl_setopt ($this->curl, CURLOPT_RETURNTRANSFER, 1);
                
                curl_setopt($ch, CURLOPT_COOKIEJAR, 'amazoncookie.txt');
                curl_setopt($ch, CURLOPT_COOKIEFILE, 'amazoncookie.txt');
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, 1);

                
		$content=curl_exec ($this->curl);
                $this->outputhtml = $content;
	}
	
        public function get_client_ip() {
            $ipaddress = '';
            if (getenv('HTTP_CLIENT_IP'))
                $ipaddress = getenv('HTTP_CLIENT_IP');
            else if(getenv('HTTP_X_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
            else if(getenv('HTTP_X_FORWARDED'))
                $ipaddress = getenv('HTTP_X_FORWARDED');
            else if(getenv('HTTP_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_FORWARDED_FOR');
            else if(getenv('HTTP_FORWARDED'))
               $ipaddress = getenv('HTTP_FORWARDED');
            else if(getenv('REMOTE_ADDR'))
                $ipaddress = getenv('REMOTE_ADDR');
            else
                $ipaddress = 'UNKNOWN';
            return $ipaddress;
        }

        
	public function hasProtocol($url)
	{			
		return strpos($url,"//");		
	}
	public function getDomain($url)
	{
		return substr($url,0,strrpos($url,"/"));
	}
	//Convert the link as It should be
	public function convertLink($domain,$url,$link)
	{
		
		if($this->hasProtocol($link))
		{
			return $link;
		}		
		elseif (($link=='#')||($link=="/"))
		{			
			return $url;			
		}		
		//else if((strpos($link,'/'))==0)
                else if(substr($link,0,1)=="/")
		{
			return $domain.$link;			
                        
		}
		else 
		{
			return $domain."/".$link;			
		}
            
	}
	public function crawlLinks($url)
	{
		$content=$this->getContent($url);//get the whole page
		
		//parte da post
//		$sopra=explode('<div class="boxTitolo">Elenco',$content);
//		$sopra=explode('<img src="../img/null.gif"',$sopra[1]);
		//prendo da sopra la fine dei post
		//$sopra_c=$sopra[0];
                
                $sopra_c=$content;
		
		$domain=$this->getDomain($url);
		$dom = new DOMDocument();
		@$dom->loadHTML($sopra_c);		
		$xpath = new DOMXPath($dom);
		$hrefs = $xpath->evaluate("//a");//get all a tags	

		$this->dim=$hrefs->length;
		
		for ($i = 0; $i < $hrefs->length; $i++) 
		{
			$href = $hrefs->item($i);//select an a tag									
			$links['link'][$i]=$this->convertLink($domain,$url,$href->getAttribute('href'));
			$links['text'][$i]=$href->nodeValue;	
                        //echo $links['link'][$i]."<br>";
                       // echo $links['text'][$i];
		}
		return  $links;
		
	}
	public	function crawlImage($url)
	{
		$content=$this->getContent($url);
		
		//parte da post
		//$sopra=explode('<img src="img/null.gif" width=178 height=5 border=0><br>',$content);
		//$sopra=explode('<img src="img/null.gif" width=172 height=2 border=0><br>',$sopra[2]);
		//prendo da sopra la fine dei post
		//$sopra_c=$sopra[0];
		
                $sopra_c=$content;
		
		$domain=$this->getDomain($url);
		$dom = new DOMDocument();
		@$dom->loadHTML($sopra_c);		
		$xdoc = new DOMXPath($dom);	
		//Read the images that is between <a> tag
		$atags = $xdoc ->evaluate("//a");		//Read all a tags	
		$index=0;
		for ($i = 0; $i < $atags->length; $i++) 
		{
			$atag = $atags->item($i);			//select an a tag
			$imagetags=$atag->getElementsByTagName("img");//get img tag
			$imagetag=$imagetags->item(0);
			if(sizeof($imagetag)>0)//if img tag exists
			{
				$imagelinked['src'][$index]=$imagetag->getAttribute('src');//save image src
				$imagelinked['link'][$index]=$atag->getAttribute('href');//save image link		
				$index=$index+1;
			}
		}			
		//Read all image
		//Betweem <img> tag 
		$imagetags = $xdoc ->evaluate("//img");	//Read all img tags	
		$index=0;
		$indexlinked=0;
		for ($i = 0; $i < $imagetags->length; $i++) 
		{
			$imagetag = $imagetags->item($i);									
			$imagesrc=$imagetag->getAttribute('src');			
			$image['link'][$index]=null;
			if($imagesrc==$imagelinked['src'][$indexlinked])//check wheather this image has link
			{
				$image['link'][$index]=$this->convertLink($domain,$url,$imagelinked['link'][$indexlinked]);
				$indexlinked=$indexlinked+1;
			}
			$image['src'][$index]=$this->convertLink($domain,$url,$imagesrc);
			$index=$index+1;			
		}		
		return $image;	
	}
	
        //avvio il crawler
        public function goCrawler(){
            //salvo html
            $this->getContent();
            $this->getStato();
            
            //in base allo stato procedo
            //se stato 3 o 0 (caso downtime) da aggiungere oppure se stato 1 e posso gia aggiornare la prima volta
            if ( ($this->sr_stato == 3) || ($this->sr_stato == 0)  || ($this->sr_stato == 1 && $this->sr_aggiorna == 1)){
               // echo "sono qua aggiorno";
                $this->creaoAggiorna();
                sleep(3);
                
                //quindi devo fare di nuovo il crawling aggiornato
                $this->getContent();
                $this->getStato();
                
            }
            
            //se entro qua ho il profilo ed è aggiornato entro 4 ore o da me o da qualcuno sul sito
            if ( $this->sr_stato == 1){
            
            $this->getUsername();
            $this->getLivello();
            $this->getClan();
            $this->getClanTag();
            $this->getChests();
            $this->getStats();
            
            $this->checkInfo();
            }
            
            
            
        }
        
        
        
        public function creaoAggiorna(){
            
                curl_setopt($this->curl, CURLOPT_URL, $this->urlrefresh);	
		curl_setopt ($this->curl, CURLOPT_RETURNTRANSFER, 1);
		$content=curl_exec ($this->curl);
                $this->outputjson_aggiorna = $content;
            
        }
        
        public function getStato(){
            $tutto = $this->outputhtml;
            $stato = 0; //default errore su crawler
            $trovato = strpos($tutto, 'Invalid Hashtag Provided'); 
            //metto questo caso speciale perche il sito statsroyale alle volte prende O e converte in 0
            //quindi fa un redirect ecc. quindi segnalo invalido e lui ricontrolla meglio
            $trovatoSpeciale = strpos($tutto, 'Redirecting');
            if ($trovato || $trovatoSpeciale) {
               // echo "invalido";
                $stato=2; //invalid hash
            }
            else{
                $trovato2 = strpos($tutto, 'Profile is currently missing');
                if ($trovato2) {
                  //  echo "missing";
                    $stato=3; //esiste ma da aggiungere dal bot2
                }else{
                // perfetto, sempre che il crawler sia ok
                 $stato = 1;
                // controllo se posso aggiornare subito o devo aspettare 4 ore
                 $this->possoAggiornare();
                 
               }
            }
            $this->sr_stato=$stato;
            
        }
        
        public function possoAggiornare(){
            $tutto = $this->outputhtml;
            $start = explode('<div class="refresh__buttonContainer">', $tutto); 
            $end   = explode('<div class="refresh__buttonIcon"></div>', $start[1]); 
            //$testo =  $this->toStringSemplice($end[0]);
            $testo=$end[0];
            $trovato = strpos($testo, 'refresh__disabled');
            if ($trovato) {
                $this->sr_aggiorna = 0;
            }else{
            $this->sr_aggiorna = 1;
            }
            
         }
        
        
        //controllo che non ci siano campi nulli
        public function checkInfo(){
            if ($this->sr_username == NULL || $this->sr_livello == NULL ){
                 $this->sr_stato = 0;
            
            }
            //altrimenti il crawler dovrebbe aver preso tutto correttamente
           
                
        }
        
        public function getUsername(){
            $tutto = $this->outputhtml;
            $start = explode('<div class="ui__headerMedium profileHeader__name">', $tutto); 
            $end   = explode('<span', $start[1]); 
            $this->sr_username=strtolower($this->toStringSemplice($end[0]));
        }
        
        public function getLivello(){
            $tutto = $this->outputhtml;
            $start = explode('<span class="profileHeader__userLevel">', $tutto); 
            $end   = explode('</span>', $start[1]); 
            $this->sr_livello=$this->toStringSemplice($end[0]);
            
            
        }
        
        
         public function getClan(){
        
            $tutto = $this->outputhtml;
            $start = explode('ui__link ui__mediumText profileHeader__userClan">', $tutto); 
            $end   = explode('</a>', $start[1]); 
            $this->sr_clan=$this->toStringSemplice($end[0]);
            
            //caso no clan
            $trovato = strpos($tutto, '/images/badges/0.png'); 
            if ($trovato) {
                $this->sr_clan="0";
            }       
            
        }
        public function getClanTag(){
        
            if ($this->sr_clan=="0"){ //no clan quindo no clantag
                $this->sr_clantag="0";
            }
            else{
            $tutto = $this->outputhtml;
            $start = explode('/clan/', $tutto); 
            $end   = explode('\' class="ui__link ui__mediumText profileHeader__userClan"', $start[1]); 
            $this->sr_clantag=strtoupper($this->toStringSemplice($end[0]));
            
            }
        }
        
        public function getChests(){
            
            
            $tutto = $this->outputhtml;
            
            $start = explode('<div class="chests__queue">', $tutto); 
            $end   = explode('ui__headerSmall offers__title', $start[1]); 
            
            $tutto2 = $end[0];

            
            $start2 = explode('<div class="ui__tooltip ui__tooltipTop ui__tooltipMiddle chests__tooltip', $tutto2);
            
            //ora per ogni occorrenza di start2 filtro
            //parto gia da 1
            $chestfinale="";
            for ($i=1;$i<sizeof($start2);$i++){
                $a = explode('">', $start2[$i]); 
                $z   = explode('</div>', $a[1]); 
            
            $stringa = $z[0];
            if ($i==1){
                //aggiungo +0
                $stringa=" +0: ".$stringa;
            }
            //echo "A".$stringa."Z<br>";
            $stringaNOhtml = strip_tags($stringa);
            $stringOK = trim(preg_replace('/\s\s+/', ' ', $stringaNOhtml));
            $stringOK = str_replace(" Chest","",$stringOK);
            $stringOK = str_replace("+","",$stringOK);
            $stringOK = str_replace(":","",$stringOK);
            
           // echo $stringOK;echo "<br>";
            
             $divido = explode(" ",$stringOK);
             $nChest=$divido[0];
             $tipoChest = $this->toNumberChest($divido[1]);
//                echo $nChest;
//                echo " - ";
//                echo $tipoChest;
//                echo "<br>";
                
                //metto insieme stringa per dopo
                //numero:tipo|n:t
                $chestfinale.=$nChest.":".$tipoChest;
                if ($i!=sizeof($start2)-1) {
                    $chestfinale.="|";
                }
            }
         //   echo $chestfinale;
  
            //rimuovo tag
           // $stringaNOhtml = strip_tags(str_replace("Next", "+0",$tutto2));
          
            
            //senza spazi newline
          //  $stringOK = trim(preg_replace('/\s\s+/', ' ', $stringaNOhtml));
           // $stringOK = str_replace(" Chest","",$stringOK);
           
            //echo "A".$stringOK."B";
            //ora divido grazie al carattere "+" faccio prima
          //  $chests = explode('+',$stringOK);
            
            //ora abbiamo a partire da 1 (non zero)
            //numero chesta
            //0 Silver
            //2 Gold
            
//            $totChest=sizeof($chests) - 1;
//            
//            $chestfinale="";
//            
//            for ($i=1;$i<=$totChest;$i++){
//                
//               // echo ">".$chests[$i]."<<br>";
//                $divido = explode(" ",$chests[$i]);
//                //divide in 3 parti, numero, chest e uno spazio vuoto
//                //echo "!-".$divido[0]."-".$divido[1]."-!<br>";
//               
//                $nChest=$divido[0];
//                
//                $tipoChest = $this->toNumberChest($divido[1]);
////                echo $nChest;
////                echo " - ";
////                echo $tipoChest;
////                echo "<br>";
//                
//                //metto insieme stringa per dopo
//                //numero:tipo|n:t
//                $chestfinale.=$nChest.":".$tipoChest;
//                if ($i!=$totChest) $chestfinale.="|";
//                
//            }
           
            $this->sr_chest=$chestfinale;
            
        }
        
        public function toNumberChest($valore){
            
         switch ($valore){
            case "Silver"   : return 0;
                break;
            case "Gold"     : return 1;
                break;
            case "Epic"     : return 2;
                break;
            case "Giant"    : return 3;
                break;
            case "Magic"    : return 4;
                break;
            case "Super"    : return 5;
                break;
            case "Legendary" : return 6;
                break;
            default: return 0;

            }
            
            
        }
        
      
        
        public function getStats(){
            
            $tutto = $this->outputhtml;
            $start = explode('<div class="statistics__metricCounter ui__headerExtraSmall statistics__trophyMetric">', $tutto); 
            $end   = explode('</div>', $start[1]); 
            $this->sr_s1=$this->toStringSemplice($end[0]);
            
            
            $start = explode('<div class="statistics__metricCounter ui__headerExtraSmall statistics__trophyMetric">', $tutto); 
            $end   = explode('</div>', $start[2]); 
            $this->sr_s2=$this->toStringSemplice($end[0]);
            
            
            $start = explode('<div class="statistics__metricCounter ui__headerExtraSmall statistics__cardMetric">', $tutto); 
            $end   = explode('</div>', $start[1]); 
            $this->sr_s3=$this->toStringSemplice($end[0]);
            
            $start = explode('<div class="statistics__metricCounter ui__headerExtraSmall statistics__tournamentMetric">', $tutto); 
            $end   = explode('</div>', $start[1]); 
            $this->sr_s4=$this->toStringSemplice($end[0]);
            
            $start = explode('<div class="statistics__metricCounter ui__headerExtraSmall statistics__donationMetric">', $tutto); 
            $end   = explode('</div>', $start[1]); 
            $this->sr_s5=$this->toStringSemplice($end[0]);
            
            $start = explode('<div class="statistics__metricCounter ui__headerExtraSmall statistics__seasonMetric">', $tutto); 
            $end   = explode('</div>', $start[1]); 
            $this->sr_s6=$this->toStringSemplice($end[0]);
            
            $start = explode('<div class="statistics__metricCounter ui__headerExtraSmall statistics__seasonMetric">', $tutto); 
            $end   = explode('</div>', $start[2]); 
            $this->sr_s7=$this->toStringSemplice($end[0]);
            
            $start = explode('<div class="statistics__metricCounter ui__headerExtraSmall statistics__seasonMetric">', $tutto); 
            $end   = explode('</div>', $start[3]); 
            $this->sr_s8=$this->toStringSemplice($end[0]);
            
            $start = explode('<div class="statistics__metricCounter ui__headerExtraSmall statistics__matchesMetric">', $tutto); 
            $end   = explode('</div>', $start[1]); 
            $this->sr_s9=$this->toStringSemplice($end[0]);
            
            $start = explode('<div class="statistics__metricCounter ui__headerExtraSmall statistics__matchesMetric">', $tutto); 
            $end   = explode('</div>', $start[2]); 
            $this->sr_s10=$this->toStringSemplice($end[0]);
            
            $start = explode('<div class="statistics__metricCounter ui__headerExtraSmall statistics__crownMetric">', $tutto); 
            $end   = explode('</div>', $start[1]); 
            $this->sr_s11=$this->toStringSemplice($end[0]);
            
        
            
        }
        
        
        
        
        public function toStringSemplice($stringa)
        {
            $stringa=strip_tags($stringa);
            $stringa=trim(preg_replace('/\s\s+/', ' ', $stringa));
            $stringa=strtolower($stringa);
            return $stringa;
        }
        
        
}


?>