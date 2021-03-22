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




echo '
<form name=fn1 method=POST action="./assembler.php">
<p>
Select a Project (Campaign)<br>
<select name="Projects" id="Projects">
  <option value="x" selected>Select a Project</option>

';

$query = "SELECT CampaignID FROM Mailings";
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
    echo "<option value='". $row['CampaignID'] ."'>". $row['CampaignID'] ."</option>";
  }

echo '
</select>
Or Create a new one named : <input type=text name="NewProjectName" value="" size=30>
</p>';




//**********************************************//
//*** Show SP API stuff first ***//

// List Templates
echo '
<p>
Select a template<br>
<select name="SPTemplates" id="SPTemplates">
';

foreach($templateArray as $a=>$b){
  foreach ($b as $c=>$d){
      echo "<option value=$d[id]>$d[name]</option>";
  }
}

echo '
</select>
</p>';



echo '
<p>
Select a Recipient List<br>
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


</p>';



//**** Now show templates and recipients stored in Git and the local DB ****//
/* For now, Hide the non SP resources


echo '
<p>
Apply a querie<br>
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
</p>';

*/
//**** For now, hide the above non SP resources ****//

// Schedule the delivery
$nowFormatted = strftime("%FT%T%z");
$nowFormatted = substr_replace( $nowFormatted, ":", -2, 0 );


echo '
Schedule delivery time : <input type=text name=DeliveryTime value="'.$nowFormatted.'" size=30><br>
Note that the time format is very important - always use this format.<br>
2018-09-11T08:00:00-04:00<br>
Any time in the past will deliver immediately.<br>
REF: <a href="https://developers.sparkpost.com/api/transmissions/#transmissions-scheduled-transmissions">
https://developers.sparkpost.com/api/transmissions/#transmissions-scheduled-transmissions</a>
</p>';

echo "
Add a description: <input type=text name=Description value=\"\" size=100><br>
";


// Make it happen

echo '
 <input type=submit name=dooit value="Make it happen!">
</form>
';


?>


