<?php


$url="http://itzunami.net/chestroyale/myip.php";
$url2="http://statsroyale.com/profile/9U0LLLYY";

echo "<br><br>Test statsRoyale";
echo avviaCrawlerRemoto2($url2);


echo "IP server   ".$_SERVER['SERVER_NAME']." <br>";
echo avviaCrawlerRemoto2($url);


echo "<br><br>Test statsRoyale Ban 2YJUJQLQ ";
$test=1;
include ('request_view.php');



function avviaCrawlerRemoto2($url){
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);	
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
      curl_setopt($ch, CURLOPT_COOKIEJAR, 'amazoncookie.txt');
                curl_setopt($ch, CURLOPT_COOKIEFILE, 'amazoncookie.txt');
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, 1);
    
    
    $content=curl_exec($ch);
    curl_close($ch);
  //   header("Location: $url");
return $content;
}



?>