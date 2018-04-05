<?php

/* Copyright 2016 Tom Mairs */

/* License and Rights

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

*/

include('env.php');
$apiroot = "https://".$apidomain."/api/v1";
include('header.php');

$projectname = $_SESSION['project'];

echo "<h1>Project: ". $projectname ."</h1>";

$htmlfile=$_POST['html'];
$textfile=$_POST['text'];
$datafile=$_POST['data'];
$attach1=$_POST['attach1'];
$attach2=$_POST['attach2'];
$attach3=$_POST['attach3'];
$attach4=$_POST['attach4'];
$attach5=$_POST['attach5'];

$filedir = "./file-upload/" . $projectname;
$textcontent = file_get_contents($filedir.'/'.$textfile); 
$htmlcontent = file_get_contents($filedir.'/'.$htmlfile);
//$datacontent = file_get_contents($filedir.'/'.$datafile); 

//FIXME  This needs to be read as lines

/*

$recordcount = 0;

$lines=file($filedir.'/'.$datafile);
//echo count($lines).'<br>';
foreach($lines as $line)
{
   $fields = explode(",",$line);
   foreach($fields as $f){
     if ($recordcount == 0){
       echo "<b> $f ,</b> ";
     }
     else {
       echo $f .', ';
     }
   }
  echo "<br>";
  $recordcount++;
}

*/

echo '
<form method=POST action=preview.php>
<table border=1 cellpadding=10>
<tr><td>TEXT Portion:<br><textarea name=text cols=80 rows=10>'.$textcontent.'</textarea></td></tr>
<tr><td width=800>
HTML Portion:
<br>----------------------------------------------------------<br>
'.$htmlcontent.'
<br>----------------------------------------------------------<br>
</td></tr>
<tr><td>Distribution List: (Select one for preview)<br>
<table>
';

$recordcount = 0;

$lines=file($filedir.'/'.$datafile);
//echo count($lines).'<br>';
foreach($lines as $line)
{
   $fields = explode(",",$line);
   echo "<tr>";

   foreach($fields as $f){
     if ($recordcount == 0){
       echo "  <td><b>$f</b></td>";
     }
     else {
       echo "  <td>$f</td>";
     }
   }
  $recordcount++;

  echo "</tr>";

}

echo '
</table>
<!-- <textarea name=data cols=80 rows=10>'.$datacontent.'</textarea>-->

</td></tr>
<tr><td>Attachments: (Click to view)
';

if ($attach1){
  echo '<br><a href="'.$filedir.'/'.$attach1.'" target=_blank>'.$attach1.'</a>';
}
if ($attach2){
  echo '<br><a href="'.$filedir.'/'.$attach2.'" target=_blank>'.$attach2.'</a>';
}
if ($attach3){
  echo '<br><a href="'.$filedir.'/'.$attach3.'" target=_blank>'.$attach3.'</a>';
}
if ($attach4){
  echo '<br><a href="'.$filedir.'/'.$attach4.'" target=_blank>'.$attach4.'</a>';
}
if ($attach5){
  echo '<br><a href="'.$filedir.'/'.$attach5.'" target=_blank>'.$attach5.'</a>';
}


echo '
</td></tr>
';


echo "
<tr><td colspan=7 align=right>
<input type=button name=preview value=PEVIEW>
<input type=submit name=submit value=NEXT>
</td></tr></table>
</form>";
 
include ('footer.php');

