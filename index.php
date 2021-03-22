<?php

include('common.php');
$apiroot = "https://".$apidomain."/api/v1";
$APIKeyValid = "false";

// If we have the keys, go for a drive right away...
if (strlen($apikey) > 39 && $apiroot != ""){
  $APIKeyValid = "true";
}


// Get the API key if it is not recorded already
if ($APIKeyValid == "false"){
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
';
}
// end function to get API key



// Standard validated index page

if ($APIKeyValid == "true"){

echo '
  <div indent>
    <p>
This tool leverages the SparkPost API, stored recipients and stored templates.  No recipient data is stored within this tool.</p>
    <p>
If you elect to store log-term detailed data and provide an external datastore location, you are responsible for the data security of the external location.
    </p>
    <p>
APIs used by this application include Templates, Recipients, Metrics, Events, Webhooks.
   </p>

  </div>
';

}



echo '
</body>
</html>
';

