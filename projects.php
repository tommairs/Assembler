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
  $_SESSION['templateHTML'] = "";
  $_SESSION['templateTEXT'] = "";
  $_SESSION['templateAMP'] = "";
  $_SESSION['templateSET'] = "";
  $_SESSION['subvars'] = "";


$url = "https://".$apidomain."/api/v1/templates";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, FALSE);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "Authorization: $apikey"
));
$response = curl_exec($ch);
curl_close($ch);

$templateArray = json_decode($response,true);

/*
foreach($templateArray as $a=>$b){
  foreach ($b as $c=>$d){
    foreach ($d as $e=>$f){
      echo "" . $e . "  = " . $f . "<br>";
    }
  }
}
*/


$url = "https://".$apidomain."/api/v1/recipient-lists";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, FALSE);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "Authorization: $apikey"
));
$response = curl_exec($ch);
curl_close($ch);

$reciplistArray = json_decode($response,true);


// List Templates stored in SparkPost
echo '
<p>
<form name="f1" method="POST" action="./quill.php">
Templates stored in SparkPost<br>
<select name="SPTemplates" id="SPTemplates">
';

foreach($templateArray as $a=>$b){
  foreach ($b as $c=>$d){
      echo "<option value=$d[id] selected>$d[name]</option>";
  }
}

echo '
</select>
<button type="submit">Open</button> &nbsp; 
</form>
</p>';



echo '
<p>
Templates stored Locally<br>
<select name="GitTemplates" id="GitTemplates">
  <option value="x" selected>Select a Template</option>
';

$query = "SELECT id, Name FROM MyTemplates";
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

  while ($row = $stmt->fetch()){
    echo "<option value=". $row['id'] ." selected>". $row['Name'] ."</option>";
  }


echo '
</select>
<button type="submit">Open</button> &nbsp; <br> 
<p><button type="button" onClick="window.location.href=\'./quill.php\';">Create New Template</button></p>
<p><button type="button" onClick="window.location.href=\'./dblist.php\';">Import/Upload from file</button></p>
</p>';


echo '
<p>
<form name="f1" method="POST" action="./dblist.php">
Recipient Lists stored in SparkPost<br>
<select name="SPRecipients" id="SPRecipients">
';

foreach($reciplistArray as $a=>$b){
  foreach ($b as $c=>$d){
      echo "<option value=$d[id] selected>$d[name]</option>";
  }
}

//  <option value="template_sample" selected>Template Sample</option>
echo '
</select>
<button type="submit">Open</button> &nbsp;


</p>';

echo '
<p>
Local Stored Lists<br>
<select name="SPQueries" id="SPQueries">
  <option value="x" selected>Select a Query</option>
';

$query = "SELECT id, Name FROM MyQueries";
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

  while ($row = $stmt->fetch()){
    echo "<option value=". $row['id'] ." selected>". $row['Name'] ."</option>";
  }

echo '
</select>
<button type="button">Open</button> &nbsp; <br>
<p><button type="button" onClick="window.location.href=\'./DBEntry.php\';">Create New List</button></p>
<p><button type="button" onClick="window.location.href=\'./dblist.php\';">Import/Upload from file</button></p>
</p>';


echo '
<p>
Saved Projects<br>
<select name="Projects" id="Projects">
  <option value="x" selected>Select a Project</option>

';

$query = "SELECT id, Name FROM MyProjects";
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

  while ($row = $stmt->fetch()){
    echo "<option value=". $row['id'] ." selected>". $row['Name'] ."</option>";
  }

echo '
</select>
<button type="button">Open</button> &nbsp; <button type="button">New</button>
</p>';



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


