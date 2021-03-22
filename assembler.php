<?php
/***** Copyright 2016 Tom Mairs ******/

/***** License and Rights ****************

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

******************************************/

include('common.php');
$now = time();


session_start();
$SavedProject = $_POST['Projects'];
$NewProject = $_POST['NewProjectName'];
$Template = $_POST['SPTemplates'];
$RecipList = $_POST['SPRecipients'];
$StartTime = $_POST['DeliveryTime'];
$Description = $_POST['Description'];
$QueryRef = $_POST['Query'];
$User = $_SESSION['User'];
$BuildTime = strftime("%FT%T%z");
$BuildTime = substr_replace( $BuildTime, ":", -2, 0 );
//$StartTime = substr_replace( $StartTime, ":", -2, 0 );

if (!$QueryRef){
  $QueryRef = $RecipList;
}


if ($NewProject != ""){
  $CampaignID = $NewProject;
}
else{
 $CampaignID = $SavedProject;
}

// Replace any spaces in the CampaignID
$CampaignID = preg_replace("/\s/","-",$CampaignID);


$json='{
  "campaign_id": "'.$CampaignID.'",
  "options": {
    "start_time": "'.$StartTime.'",
    "open_tracking": true,
    "click_tracking": true
  },
  "recipients": {
    "list_id": "'.$RecipList.'"
  },
  "content": {
    "template_id": "'.$Template.'"
  }
}';


$url = "https://".$apidomain."/api/v1/transmissions";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "Authorization: $apikey"
));
$response = curl_exec($ch);
curl_close($ch);


$res = json_decode($response,1);
//echo "<pre>";
//var_dump($res);
//echo "<br> TID = ";
//var_dump($res["results"]["id"]);
//echo "</pre>";

$res_id = $res["results"]["id"];
$res_rej = $res["results"]["total_rejected_recipients"];
$res_acc = $res["results"]["total_accepted_recipients"];


//echo "". $res_id .", ". $res_rej . ", ". $res_acc . "";


// For now this will always be the saved recip list - change it later

  $query = "INSERT INTO Mailings (CampaignID,User,Description,Template,Query,StartTime,BuildTime,TransID) 
            VALUES ('$CampaignID','$User','$Description','$Template','$QueryRef','$StartTime','$BuildTime','$res_id')";

   $query_params = array(
              ':ID' => 1

        );
        try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }

        catch(PDOException $ex)

       {
            die("Failed to run query: " . $ex->getMessage());
        }



// Get current campaigns for the table
$query = "SELECT * FROM Mailings ORDER BY id DESC LIMIT 20";
   $query_params = array(
              ':ID' => 1
        );
        try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }

        catch(PDOException $ex)

       {
            die("Failed to run query: " . $ex->getMessage());
        }




echo "
Most recent camoaigns:<br>
<table border=1 cellpadding=5>
  <tr><th>Owner</th><th>Campaign</th><th>Template</th><th>RecipList/Query</th><th>Start Time</th><th>Build Time</th><th width=200>Description</th><th>Transmission</th></tr>
";


  while ($row = $stmt->fetch()){
    echo "  <tr><td>".$row['User']."</td><td>".$row['CampaignID']."</td><td>".$row['Template']."</td><td>".$row['Query']."</td><td>".$row['StartTime']."</td><td>".$row['BuildTime']."</td><td>".$row['Description']."</td><td>";

echo "<input type=button value=\"Report\" onclick=\"location.href='./reports.php?t=".$row['TransID']."';\">";

echo "</td></tr>";
  }

echo "</table>";

echo "<input type=button value=\"See ALL results\" onclick=\"location.href='./allreports.php';\">";


?>
