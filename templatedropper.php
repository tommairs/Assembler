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


// If no project name, get one

$pn = $_POST['pn'];
$npn = $_POST['npn'];
if ($npn != ""){
  $pn = $npn;
}

$_SESSION['project'] = $pn;

if (!$pn){

echo "
  <p><h2>Select a project or create a new one.</h2> </p>
<table cellpadding=5>
    <form name=f1 method=POST>";
  $filedir = "./file-upload";
  foreach(glob($filedir.'/*',GLOB_ONLYDIR) as $file) {
    $cleanfile = basename($file);
    $cleanfile=preg_replace('/\s+/','_',$cleanfile);
    $filenames[] = $cleanfile;
    print "<tr>
             <td><input type=checkbox name=pn value=". $cleanfile ."></td>
             <td>". $cleanfile ."</td>
             <td><a href=\"killdir.php?fn=".$cleanfile."\"><b>X</b></td>
           </tr>";
  } 

echo "
  <tr><td>New Project: </td><td><input type=text name=npn value=\"\"></td></tr>
  <tr><td>&nbsp;</td><td><input type=submit name=submit value=NEXT></td></tr>
  </form>
</table>
";

//  Project Name: <input type="text" name="pn" value="project1">

exit(0);

}

// Else continue to get files

$projectname = $_SESSION['project'];

echo "<h1>Project: ". $projectname ."</h1>";

echo '
    <center>
<table cellpadding=10><tr><td>
<center>
Drop your project files here.<br>
<font size = -2>(HTML template, Plain Text template, recipient file, attachments)</font><br> 
</center>
<p>
<form action="./uploader.php"
      class="dropzone"
      id="my-awesome-dropzone">
</form>
</p>
</td>
</tr><tr>
<td>
<center>
<a href="fileanalyzer.php">CONTINUE</a>
</center>
</td>
</tr>
</table>
<p>
<a href="templateselector.php">CLICK HERE TO SELECT AN EXISTING LIST AND TEMPLATE</a> <br>
</p>
';
 
include ('footer.php');

