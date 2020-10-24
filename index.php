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
$apiroot = "https://".$apidomain."/api/v1";




// If we have the keys, go for a drive right away...

if (strlen($apikey) > 39 && $apiroot != ""){
header("Location: ./templatedropper.php");
die();
}



/*
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
*/

echo ' 
<form action="templateselector.php" id="keyform" name="keyform" onsubmit="stringToHex()">
<table border="0" width="60%" cellpadding="10">
  <tr>
    <td valign=top>
        <h3>Your SparkPost API Key:</h3>
    </td>
    <td>
        <input id="key" 
               name="apikey" 
               placeholder="API Key.." 
               required=true 
               size="30"
               type="password" 
               value="'.$apikey.'" 
               autocomplete="off"
         >
    </td>
    </tr>
    <tr>
    <td valign=top>
         <h3>Your SparkPost API Domain:</h3>
   </td>
   <td> 
         <input id="apiroot" 
                name="apiroot" 
                placeholder="API Domain" 
                size="30" 
                type="text"  
                value="'.$apiroot.'">
         <br>
         <h5>Optional for Enterprise/Elite Users: Enter your API Root URL
         <br>Empty will default to: api.sparkpost.com</h5>
    </td>
    </tr>
    <td></td>
    <td>
         <input  type="submit" value="Submit" name="submit">
    </td>
  </tr>
</table>
</form>
';


echo '
<table border="0" width="75%" cellpadding="20">
    <tr>
        <td>
            Note: These API Keys are created from the admin page within your SparkPost account at 
            <a href="https://app.sparkpost.com/account/credentials">
            https://app.sparkpost.com/account/credentials</a>.  
            Please remember that the SparkPost system only shows your API Key "once", so you need 
            to keep the API Key safe where you can get to it each time you use this or any application 
            that needs an API Key.  If you loose the API Key you can always create a new one.<p>
                    
            At a minimum, you need to select \'Recipient Lists: Read/Write, Templates: Read/Write, 
              Transmissions: Read/Write and Sending Domains: Read\'.
        </td>
    </tr>
</table>

<p>
    <table border="1" style="background-color:#a3e9f7" width="75%" cellpadding="20">
        <tr>
            <td>
                This tool is free to use at your own risk.  It is not monitored and is 
                considered a DEMO tool. <br>
                If you wish to build this in your own environment, the code is open source and 
                is available on Github <br>
                <a href="https://github.com/botbuilder2000/Ramesses" target="_blank">
                https://github.com/botbuilder2000/Ramesses</a>.
            </td>
        </tr>
    </table>
</center>
</body>
</html>
';

