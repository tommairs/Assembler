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

echo "<h2>How do we handle these files?</h2>";

$filedir = "./file-upload/" . $projectname;

foreach(glob($filedir.'/*') as $file) {
  $filenames[] = basename($file);
  print basename($file);
  $tmpfilecontents = file_get_contents($file);
  $filetype[basename($file)] = "FILE";

// Check for HTML content
  $pattern = "/<bod.*>/";
  preg_match($pattern, $tmpfilecontents, $matches);
  if ($matches){
    print " appears to be an HTML file";
//var_dump($matches);
    $html_template = $tmpfilecontents;
    $filetype[basename($file)] = "HTML";
  }

// Check for JSON content
/*
  $result = json_decode($tmpfilecontents);
  if (json_last_error() == JSON_ERROR_NONE) {
    $json_data_valid = "true";
  }
*/

//  $pattern = "/.*(\{\"\w+\":.*\}).*/m";
    $pattern = "/.*(\"\w+\":[\s]\".*\"[\s\,]+)+/m";
  preg_match($pattern, $tmpfilecontents, $matches);
  if ($matches){
    print " appears to be a JSON data file";
//var_dump($matches);
    $json_data = $tmpfilecontents;
    $filetype[basename($file)] = "JSON";
  }



// Check for CSV content
  $pattern = "/^(\w{3,}\,[\s]*\w{3,})/m";
  preg_match($pattern, $tmpfilecontents, $matches);
  if ($matches){
    print " appears to be a CSV file";
//var_dump($matches);
    $csv_data = $tmpfilecontents;
    $filetype[basename($file)] = "CSV";
  }


// Check for email list content
if ($filetype[basename($file)] != "CSV"){

  $pattern = "/[\w|.|+]+\@[\w|.|+]+\.\w{2,4}$/";
  preg_match($pattern, $tmpfilecontents, $matches);
  if ($matches){
    print " appears to be an email-list file";
//var_dump($matches);
    $email_data = $tmpfilecontents;
    $filetype[basename($file)] = "EMAIL";
  }
}

// Check for Plain TEXT content
if (($filetype[basename($file)] != "HTML") 
 AND ($filetype[basename($file)] != "JSON")
 AND ($filetype[basename($file)] != "CSV")
 AND ($filetype[basename($file)] != "EMAIL")){
  $extensions=array('png','jpg','gif','pdf');
  if ( !in_array(substr(basename($file),-3), $extensions, true ) ) {
    $pattern = "/((([^\{\}\<\>][A-Za-z])+)\s)+/m";
    preg_match($pattern, $tmpfilecontents, $matches);
    if ($matches){
      print " appears to be a Plain TEXT file";
      //var_dump($matches);
      $text_template = $tmpfilecontents;
      $filetype[basename($file)] = "TEXT";
    }
  }
}




if ($filetype[basename($file)] == "FILE"){
  print " is identified as an attachment";
}

  print "<br>";
}


echo '
<form method=POST action=fileprocessor.php>
<table border=1 cellpadding=10>
<tr>
  <th>HTML</th>
  <th>TEXT</th>
  <th>DATA</th>
  <th>ATTACH</th>
  <th>Filename</th>
  <th>Type</th>
  <th>DELETE</th>
  <th>ACTION</th>
</tr>';

$acount=0;
foreach ($filenames as $f){
$acount++;
echo '<tr>
  <td align=center><input type=radio name=html value="'.$f.'" ';
if ($filetype[$f] == "HTML"){
  echo ' checked';
}
else{
  echo ' unchecked';
}
echo '></td>
  <td align=center><input type=radio name=text value="'.$f.'" ';
if ($filetype[$f] == "TEXT"){
  echo ' checked';
}
else{
  echo ' unchecked';
}
echo '></td>
  <td align=center><input type=radio name=data value="'.$f.'" ';
if (($filetype[$f] == "JSON") OR ($filetype[$f] == "CSV")){
  echo ' checked';
}
else{
  echo ' unchecked';
}
echo '></td>
  <td align=center><input type=radio name=attach'.$acount.' value="'.$f.'"  unchecked></td>
';
  echo '<td>'. $f .'</td><td>'. $filetype[$f] .' </td>
        <td align=center><a href="killfile.php?fn='.$f.'"><b>X</b></td>
        <td align=center>&nbsp;';

  if ($filetype[$f] == "TEXT"){
    echo '<input type=button value=EDIT onclick="window.location.href=\'t-editfile.php?fn='.$f.'\'">';
  }

  if ($filetype[$f] == "HTML"){
    echo '<input type=button value=EDIT onclick="window.location.href=\'h-editfile.php?fn='.$f.'\'">';
  }

  if ($filetype[$f] == "CSV"){
    echo '<input type=button value=EDIT onclick="window.location.href=\'c-editfile.php?fn='.$f.'\'">';
  }

  if ($filetype[$f] == "JSON"){
    echo '<input type=button value=EDIT onclick="window.location.href=\'j-editfile.php?fn='.$f.'\'">';
  }



echo '</td>      </tr>';
}

echo "
<tr><td colspan=8 align=right><input type=submit name=submit value=NEXT></td></tr>
</table>
</form>";
 
include ('footer.php');

