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
include('header.php');

echo '
<body id="bkgnd">
<ul class="topnav" id="generatorTopNav">
  <li><a href="index.php">Home</a></li>
  <li><a href="help.php">Help</a></li>
  <li><a href="https://developers.sparkpost.com/" target="_blank">SparkPost Documentation</a></li>
  <li><a href="'.$contactlink.'">Contact</a></li>
</ul>
<center>
  <p><h1>'.$apptitle.'</h1></p>
';

echo "

<table>
  <tr>
    <td> Project Owner: </td>
    <td> Tom Mairs </td>
  </tr>
  <tr>
    <td> Project Page: </td>
    <td> <a href=\"https://github.com/botbuilder2000/Ramesses\" target=\"_blank\">
         https://github.com/botbuilder2000/Ramesses</a> </td>
  </tr>
  <tr>
    <td> Contact Email: </td>
    <td> <a href=\"mailto:tmairs@aasland.com\">tmairs@aasland.com</a> </td>
  </tr>

</table>




";

