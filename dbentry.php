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


$cf1 = $_POST['cf1'];
$cf2 = $_POST['cf2'];
$cf3 = $_POST['cf3'];
$cf4 = $_POST['cf4'];
$wh1 = $_POST['wh1'];
$cond = $_POST['cond'];
$textval = $_POST['textval'];
$limitval = $_POST['limitval'];
$queryname= $_POST['queryname'];
$queryval = $_POST['queryval'];
$querydesc = $_POST['querydesc'];

if (!$owner){
  $owner = "system";
}


if (!$queryname){
  $queryname = "query-".$now;
}

if (!$querydesc){
  $querydesc = " ";
}




if ($queryname != ""){
  echo "<br>Saving  $queryname as $queryval <br>";


$query = "INSERT INTO MyQueries (Name, Owner, Description, Query ) VALUES (:P1,:P2,:P3,:P4)";
   $query_params = array(
              ':P1' => $queryname,
              ':P2' => $owner,
              ':P3' => $querydesc,
              ':P4' => $queryval
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


echo '<script type="text/javascript">
           window.location = "lists.php"
      </script>';
  

  exit;
} 

/*
echo "<pre>";
var_dump($_POST);
echo "</pre>";
*/

// Assemble query
$cf= "" .$cf1. " ";
if ($cf2 !="none"){ $cf.= ", $cf2";}
if ($cf3 !="none"){ $cf.= ", $cf3";}
if ($cf4 !="none"){ $cf.= ", $cf4";}

$query = "SELECT " .$cf. " FROM MyContacts WHERE ". $wh1 ." ". $cond ." ". $textval ."";

if ($limitval > 0){$query .= " LIMIT $limitval";}

echo '<p><form method=post>
Save this query:<br><b>'. $query . '</b>
<input type=hidden value="'.$query.'" name="queryval">
<br> as <input type=text value="" name=queryname placeholder="Friendly Query Name">
 <input type=submit value="SAVE">  <input type=button value="Or... Cancel" OnCLick="history.back(-1)"> 
</form></p>
 ';

?>

