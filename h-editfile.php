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
$filedir = "./file-upload/" . $projectname;
$filename = $_GET['fn'];
$pass2 = $_POST['write'];
$tmpdata = $_POST['tmpdata'];
$original = $_POST['orig'];

if ($pass2 == "OK"){

  $tmpfilecontents = file_get_contents($filedir ."/payload.json");
  if ($tmpfilecontents == false){
    $tmpfilecontents = '{
  "options": {
    "open_tracking": true,
    "click_tracking": true,
    "start_time":"2016-04-07T13:00:00-06:00"
  },
  "campaign_id": "placeholder",
  "return_path": "placeholder",
  "metadata": {},
  "substitution_data": {},
  "recipients": [
    { "address": {"email": "placeholder@here.com","name": "placeholder"},
      "substitution_data": {"sender":"placeholder"}
    }
  ],
  "content": {
    "from": {"name": "placeholder","email": "placeholder@placeholder.com"},
    "subject": "placeholder",
    "reply_to": "placeholder <placeholder@placeholder.net>",
    "headers": {},
    "text": "placeholder",
    "html": "placeholder"
   }
  }';

  }
  $tmpdata = preg_replace("/\"/","\\\"",$tmpdata);
  $tmpfilecontents = preg_replace("/\"html\": \".*\"/","\"html\": \"$tmpdata\"",$tmpfilecontents);

  file_put_contents($filedir ."/payload.json", $tmpfilecontents);
  file_put_contents($filedir ."/". $original, $tmpdata);
  header("Location: ./fileanalyzer.php");
}
else {

  echo "<h1>Project: ". $projectname ."</h1>
  <h2>Edit text in the field below and then SAVE</h2>";

  $tmpfilecontents = file_get_contents($filedir ."/". $filename);

  echo "
    <form name=f1 method=post>
      <textarea name=tmpdata rows=\"20\" cols=\"100\">". $tmpfilecontents ."</textarea>
      <input type=hidden name=\"write\" value=\"OK\">
      <input type=hidden name=\"orig\" value=\"". $filename ."\">
      <br><input type=reset value=\"CANCEL\" onclick=\"history.go(-1);\">
      <input type=submit value=\"SAVE\">
    </form>
  ";
}

include ('footer.php');

