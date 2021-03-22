<?php

include('common.php');
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


// List Templates stored in SparkPost
echo '
<p>
<form name="f1" method="POST" action="./quill.php">
Templates stored in SparkPost<br>
<select name="SPTemplates" id="SPTemplates">
';

foreach($templateArray as $a=>$b){
  foreach ($b as $c => $d){
      echo "<option value=\"$d[id]\" selected>$d[name]</option>";
  }
}

echo '
</select>
<button type="submit">Open</button> &nbsp; 
</form>
</p>';


/*

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
<p><button type="button" onClick="window.location.href=\'./quill.php?type=new\';">Create new template from scratch</button></p>
</p>';

*/



echo "<br>========= Import/Upload from file =============<br><br>";

echo "Upload a new HTML or TEXT file. If there are no <TAGs> in the file, TEXT will automatically be assumed.<br>";
echo '<form action="upload.php" method="post" enctype="multipart/form-data">
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="Upload" name="submit">
</form>
';





?>


