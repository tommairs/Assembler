<?php

include('common.php');

$JobID = $_GET['job'];
$testvar = $_GET['test'];


$nowFormatted = strftime("%FT%T%z");
$nowFormatted = substr_replace( $nowFormatted, ":", -2, 0 );

echo "Processing Job ID $JobID at $nowFormatted <br>";

if ($JobID != ""){
   $query = "SELECT * FROM MyProjects WHERE id = :ID";
   $query_params = array(
              ':ID' => $JobID
        );
        try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }

        catch(PDOException $ex)

       {
            die("Failed to run query 4: " . $ex->getMessage());
        }

   while ($row = $stmt->fetch()){
     $StartTime = $row['StartTime'];
     $SPQuery = $row['Query'];
     $SPTemplate = $row['Template'];
   }

   if (substr($SPQuery,0,14) == "SELECT * FROM "){
     echo "This is a full list <br>";
     $len = ((strlen($SPQuery)-14)*-1);
     $ListID = substr($SPQuery, $len);
     echo "Found a list ID to work with <br>";

       $json = '{
         "recipients": {
             "list_id": "'.$ListID.'"
           },
           "content": {
             "template_id": "'.$SPTemplate.'"
           }
        }';
  }
  else {
    echo "This is an SQL query <br>";
    echo $SPQuery . "<br>";
    $rcount = 3; // FIXME - this need to count the Query result
    echo substr($SPQuery);
      $json = '{
         "recipients": {
             "list_id": "'.$ListID.'"
           },
           "content": {
             "template_id": "'.$SPTemplate.'"
           }
        }';

  }
}

if (strval($rcount) < 1){$rcount = 0;}
 
   if ($testvar != "true"){
     echo "Not a test...<br>";

     if (strtotime($StartTime) <= $now){
       echo "This one qualifies to send now<br>";

       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, "https://$apidomain/api/v1/transmissions");
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
       curl_setopt($ch, CURLOPT_HEADER, FALSE);
       curl_setopt($ch, CURLOPT_POST, TRUE);
       curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
           curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
       curl_setopt($ch, CURLOPT_HTTPHEADER, array(
         "Content-Type: application/json",
         "Authorization: $apikey"
       ));

       $response = curl_exec($ch);
       curl_close($ch);
       
       $res = json_decode($response,true);
       $TransID = $res['results']['id'];
echo "<pre><br>";
       var_dump($res);
echo "<br> USING <br>";
       var_dump($json);
echo "</pre><br>";

       if ($res['total_rejected_recipients'] > 0){
           echo 'Message could not be sent to ' .$res['total_rejected_recipients']. ' recipients <br>';
       } 
       if ($res['errors'] != "") {
           echo 'Message could not be sent. <br>';
       } 
       else {
         echo "<p>   Message sent to customer!  </p> ";
         echo "Res: $response <br>";
         echo "Transmission ID = $TransID <br>";
         echo "<br/> <br/>";
       }
     }
   }
   else {
     echo "This is a test and will not actually send <br>";

     if ($rcount < 1){  
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, "https://$apidomain/api/v1/recipient-lists/$ListID?show_recipients=false");
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
       curl_setopt($ch, CURLOPT_HEADER, FALSE);
       curl_setopt($ch, CURLOPT_POST, FALSE);
    //   curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
           curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
       curl_setopt($ch, CURLOPT_HTTPHEADER, array(
         "Content-Type: application/json",
         "Authorization: $apikey"
       ));

       $response = curl_exec($ch);
       curl_close($ch);
       $res = json_decode($response,true);
       $rcount = $res['results']['total_accepted_recipients'];
     }

     echo "<br>Will generate email to $rcount recipient(s) <br>"; 
     echo "<pre><br>";
     print_r($json);
     echo "</pre><br>";
     $TransID = "";
   }

if ($TransID == ""){
  $nowFormatted = "TEST:".$nowFormatted;
}
 $query = "UPDATE MyProjects SET TransID=:TD, BuildTime=:BD WHERE id=:JID";
     $query_params = array(
              ':JID' => $JobID,
              ':TD' => $TransID,
              ':BD' => $nowFormatted

        );

        try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }

        catch(PDOException $ex)

       {
            die("Failed to run query 3: " . $ex->getMessage());
        }

  if ($testvar == "true"){
    echo '<button type="button" onClick="window.location.href=\'sherpa.php\';">
          Return to Sherpa
          </button>';
  }
  else{
    echo '<button type="button" onClick="window.location.href=\'reports.php?tid='.$TransID.'\';">
          Go to Reports
          </button>';
  }


exit;

    echo '<script type="text/javascript">
            window.open("sherpa.php","_self");
          </script>';


?>


