<?php
exit;
foreach (getallheaders() as $name => $value) {
    echo "$name: $value\n";
}

//cambia se non sono in checkonly con data[tag]
    $tag=strtoupper($_REQUEST['t']);
    
    echo "<a href='https://statsroyale.com/profile/$tag/refresh'>https://statsroyale.com/profile/$tag/refresh</a>";
    
    
    echo '
    <br><br><br>
    <iframe src="https://statsroyale.com/profile/'.$tag.'/refresh" width="100%" height="150px"></iframe> 
    ';
    ?>

