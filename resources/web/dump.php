<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 0);

//view
if ($_REQUEST['v']!=NULL){
    
    $a=file_get_contents('output.txt', true);
    echo "INIZIO LETTURA<br><br>";
    echo $a;
    echo "<br><br>FINE LETTURA";
    }
    else
    {
        
    $data = json_decode(file_get_contents('php://input'), true);
    //print_r($data);


    //salvo x vedere

    $file = 'output.txt';
    // Open the file to get existing content
   // $current = file_get_contents($file);
    // Append a new person to the file
    //Senza . sovrascrivo
//    $current = "John Smith\n";
    // Write the contents back to the file
    file_put_contents($file, $data);
    
    
       echo'{  
              
              "message": "'.$data['message'].'",
              "culo" : "'.$data['culo'].'"
              
          }
            ';
  
        
    }
    
    

?>