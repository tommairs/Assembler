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

$filename = $_GET['fn'];
$killname = $_POST['fn'];


echo "<h1>Project: ". $projectname ."</h1>";

echo "<h2>Are you sure you want to remove this files?</h2>";

echo "<h1>".$filename."</h1>";

$filedir = "./file-upload/" . $projectname;

if ($killname){
  unlink($filedir ."/" .$killname);
echo "deleting ". $filedir . "/" .$killname ."<br>";

header("Location: ./fileanalyzer.php");

}

echo '
<form method=POST>
<input type=hidden name=fn value='.$filename.'>
<input type=reset name=reset value=CANCEL onclick="history.go(-1);">
<input type=submit name=submit value=DELETE>
</form>';
 
include ('footer.php');

