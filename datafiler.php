<?php
// Webhook file processor

// Define log field separator and other vars
  error_reporting(0);
  $f = "data";          // location of raw text file log data

  $dir    = '/var/www/html/cst/Ramesses/data/';
  $markedfiles=[];

// catalog all files in data folder
// and read each file into data log
  $filelist = scandir($dir);
  $mids = [];
  foreach ($filelist as $f){
    if (substr($f,0,5) == "data_"){
      $timestamp = date('U');
      print "$timestamp: Processing file $f \r\n";
      $filecontents = file_get_contents($dir.$f);
      $data_array = json_decode($filecontents, true);

//print_r($data_array);print_r9 $data_array[msys][message_event];

foreach ($data_array as $x=>$y){
  foreach ($y as $a=>$b){
    foreach ($b as $c=>$d){
      foreach ($d as $e=>$f){
        print_r($f) ;
        print "\r\n";
      }
    }
  }
}

//      extracttologs($data_array,$logdir);
      if (file_exists($dir.$f)){       // then mark the file for deletion
//        array_push($markedfiles,$dir.$f);
      }
    }
  }
  foreach($markedfiles as $mf){
     unlink($mf);                       // delete all the processed files
  }
  unset ($mids);

// End of MAIN
/***************************/



?>


