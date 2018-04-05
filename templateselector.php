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


echo '<script type="text/javascript">

function prepsubmit() 
{
    var json = document.getElementById("json").innerHTML;
    var segmented = document.getElementById("segmented");
	if (json.value.length > 2) {segmented.value = "TRUE";}
 }
  
function countaddresses() 
{
    //var recipientCount = document.getElementById("recipientCount");
    var filteredcount = document.getElementById("filteredcount");
    var json = document.getElementById("json");
    var doublequote = (json.value.match(/"address"/g) || []).length;
    var singlequote = (json.value.match(/\'address\'/g) || []).length;
	//recipientCount.value = singlequote + doublequote;
	filteredcount.value = singlequote + doublequote;
 }
 
function recipientcount() 
{
    var recipientCount = document.getElementById("recipientCount");
    var recipientlist = document.getElementById("Recipients");
    var apikey = "'.$apikey.'";
    var apiroot = "'.$apiroot.'";

    $.ajax({
      url:\'cgGetRecipientListCount.php\',
      type: "POST",
      data: {"apikey" : apikey, "apiroot" : apiroot, "recipients" : recipientlist.value},
      complete: function (response) 
      {
          recipientCount.value=response.responseText;
      },
      error: function () {
          $(\'#output\').html(\'0\');
      }
    }); 
    return false;
}

function generatePreview()
{
	var selectList = document.getElementById("Template");
    var selectList2 = document.getElementById("Recipients");
    var globalsub = document.getElementById("globalsub").value;
    var apikey = "'.$apikey.'";
    var apiroot = "'.$apiroot.'";

    $.ajax({
      url:\'cgBuildPreview.php\',
      type: "POST",
      data: {"apikey" : apikey, "apiroot" : apiroot, "template" : selectList.value, "recipients" : selectList2.value, "globalsub" : globalsub},
      complete: function (response) 
      {
          $(\'#iframe1\').contents().find(\'html\').html(response.responseText);
          xbutton = document.getElementById("submit");
          var strCheck1 = "attempt to call non-existent macro";
          var strCheck2 = "crash";
          var location1 = response.responseText.search(strCheck1);
          var location2 = response.responseText.search(strCheck2);
          if (location1 > 0  && location2 > 0)
          {
              xbutton.disabled = true;
              xbutton.value = "Submit";
              xbutton.style.backgroundColor = "red";
              xbutton.style.color = "black";
              alert("Warning!! Your data protection check was triggered, bad Recipient List selected - Submit Turned off!");
          }
          else
          {  
              var strCheck = "Matching Problem";
              var location = response.responseText.search(strCheck);
              if (location > 0) 
              {
                  xbutton.disabled = true;
                  xbutton.value = "Submit";
                  xbutton.style.backgroundColor = "red";
                  xbutton.style.color = "black";
                  alert("Warning!! Template & Recipient error detected; please see preview box - Submit Turned off!");
              }
              else
              {   
                  xbutton.disabled = false;
                  xbutton.value = "Submit";
                  xbutton.style.color = "white";
                  xbutton.style.backgroundColor = "#72A4D2";
              }
          }
      },
      error: function () {
          $(\'#output\').html(\'Bummer: there was an error!\');
      }
    }); 
    return false;
}

function sendTestEmail()
{
	var templateList = document.getElementById("Template");
    var recipientList = document.getElementById("Recipients");
    var emailaddresses = document.getElementById("previewTestEmails").value;
    var campaign = document.getElementById("campaign").value;
    var open = document.getElementById("open").value;
    var click = document.getElementById("click").value;
    var meta1 = document.getElementById("meta1").value;
    var data1 = document.getElementById("data1").value;
    var meta2 = document.getElementById("meta2").value;
    var data2 = document.getElementById("data2").value;
    var meta3 = document.getElementById("meta3").value;
    var data3 = document.getElementById("data3").value;
    var meta4 = document.getElementById("meta4").value;
    var data4 = document.getElementById("data4").value;
    var meta5 = document.getElementById("meta5").value;
    var data5 = document.getElementById("data5").value;
    var returnpath = document.getElementById("returnpath").value;
    var domain = document.getElementById("domain").value;
    var apikey = "'.$apikey.'";
    var apiroot = "'.$apiroot.'";
    var globalsub = document.getElementById("globalsub").value;
    
    $.ajax({
      url:\'segSendTestEmail.php\',
      type: "POST",
      data: {"apikey" : apikey, "template" : templateList.value, "recipients" : recipientList.value, "emailaddresses" : emailaddresses, 
      		 "apiroot" : apiroot, "campaign" : campaign, "open" : open, "click" : click, "globalsub" : globalsub,
      		 "meta1" :  meta1, "data1" : data1,   "meta2" :  meta2, "data2" : data2, "meta3" :  meta3, "data3" : data3, "meta4" :  meta4, "data4" : data4, "meta5" :  meta5, "data5" : data5, 
      		 "returnpath" : returnpath, "domain" : domain },
      complete: function (response) 
      {
          // This is for error checking  in order to see echo\'ed items...
          //$(\'#iframe1\').contents().find(\'html\').html(response.responseText);
      },
      error: function () 
      {
          $(\'#iframe1\').contents().find(\'html\').html(response.responseText);
      }
    });
    
    return false;
}

function buildsegdrop()
{
    var recipientList = document.getElementById("Recipients");
    var segList1 = document.getElementById("segList1");
    var segList2 = document.getElementById("segList2");
    var segList3 = document.getElementById("segList3");
    var segList4 = document.getElementById("segList4");
    var segList5 = document.getElementById("segList5");
    var apikey = "'.$apikey.'";
    var apiroot = "'.$apiroot.'";
    
    $.ajax({
      url:\'segFieldDropdown.php\',
      type: "POST",
      data: {"apikey" : apikey, "recipients" : recipientList.value, "apiroot" : apiroot },
      complete: function (response) 
      {
          // This is for error checking  in order to see echo\'ed items...
          segList1.innerHTML=response.responseText;
          segList2.innerHTML=response.responseText;
          segList3.innerHTML=response.responseText;
          segList4.innerHTML=response.responseText;
          segList5.innerHTML=response.responseText;
      },
      error: function (response) 
      {
          $(\'#iframe1\').contents().find(\'html\').html(response.responseText);
      }
    });
    
    return false;
}


function resetpreview()
{
	$(\'#iframe1\').contents().find(\'html\').html("<p>Please select your Template and Recipient List</p>");
	xbutton = document.getElementById("submit");
	xbutton.disabled = false;
    xbutton.value = "Submit";
    xbutton.style.color = "white";
    xbutton.style.backgroundColor = "#72A4D2";
}

function resetsummary()
{
	$(\'#template\').contents().find(\'html\').html("<p>Please select your Template and Recipient List</p>");
	$(\'#substitution\').contents().find(\'html\').html("<p>Please select your Template and Recipient List</p>");
}

function getSegment()
{

    var apikey = "'.$apikey.'";
    var apiroot = "'.$apiroot.'";
    var recipientList = document.getElementById("Recipients");
	var filterArray = {
		\'filter\': [],
		\'logic\' : \'and\'
	};
	var segList1 = document.getElementById("segList1");
	var segValue1 = document.getElementById("segValue1");
	var segOperand1 = document.getElementById("operand1");
	var segList2 = document.getElementById("segList2");
	var segValue2 = document.getElementById("segValue2");
	var segOperand2 = document.getElementById("operand2");
	var segList3 = document.getElementById("segList3");
	var segValue3 = document.getElementById("segValue3");
	var segOperand3 = document.getElementById("operand3");
	var segList4 = document.getElementById("segList4");
	var segValue4 = document.getElementById("segValue4");
	var segOperand4 = document.getElementById("operand4");
	var segList5 = document.getElementById("segList5");
	var segValue5 = document.getElementById("segValue5");
	var segOperand5 = document.getElementById("operand5");
	var jsonEntry = document.getElementById("json");
	if (segList1.value != "Filter Not Entered" ) {filterArray.filter.push({ \'metadata-fieldname\': segList1.value, \'logical-comparison\': operand1.value, \'comparison-value\': segValue1.value })};
	if (segList2.value != "Filter Not Entered" ) {filterArray.filter.push({ \'metadata-fieldname\': segList2.value, \'logical-comparison\': operand2.value, \'comparison-value\': segValue2.value })};
	if (segList3.value != "Filter Not Entered" ) {filterArray.filter.push({ \'metadata-fieldname\': segList3.value, \'logical-comparison\': operand3.value, \'comparison-value\': segValue3.value })};
	if (segList4.value != "Filter Not Entered" ) {filterArray.filter.push({ \'metadata-fieldname\': segList4.value, \'logical-comparison\': operand4.value, \'comparison-value\': segValue4.value })};
	if (segList5.value != "Filter Not Entered" ) {filterArray.filter.push({ \'metadata-fieldname\': segList5.value, \'logical-comparison\': operand5.value, \'comparison-value\': segValue5.value })};


	$.ajax({
      url:\'segStub.php\',
      type: "POST",
      data: {"apikey" : apikey, "recipients" : recipientList.value, "apiroot" : apiroot, "filterArray" : filterArray },
      complete: function (response) 
      {
          json.innerHTML = response.responseText;
    	  var segmented = document.getElementById("segmented");
	  if (json.innerHTML.length > 2) {segmented.value = "TRUE";}
 	  //var recipientCount = document.getElementById("recipientCount");
    	  var filteredcount = document.getElementById("filteredcount");
    	  //var json = document.getElementById("json");
    	  var doublequote = (json.value.match(/"address"/g) || []).length;
    	  var singlequote = (json.value.match(/\'address\'/g) || []).length;
          //recipientCount.value = singlequote + doublequote;
          filteredcount.value = singlequote + doublequote;
      },
      error: function () 
      {
          $(\'#iframe1\').contents().find(\'html\').html(response.responseText);
      }
    });
    
    return false;
}
</script>

';
 
    //
    // Check the APIKey by calling one of the REST API\'s.  If I call this from the cgPHPLibraries.php the
    // Server returns and Unauthorized.  Will look into that later.
    //
        $curl = curl_init();
        $url = $apiroot . "/recipient-lists/";
    	curl_setopt_array($curl, array(
    	CURLOPT_URL => $url,
    	CURLOPT_RETURNTRANSFER => true,
    	CURLOPT_ENCODING => "",
    	CURLOPT_MAXREDIRS => 10,
    	CURLOPT_TIMEOUT => 30,
    	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    	CURLOPT_CUSTOMREQUEST => "GET",
    	CURLOPT_HTTPHEADER => array("authorization: $apikey","cache-control: no-cache","content-type: application/json")
    	));

    	$response = curl_exec($curl);
    	$err      = curl_error($curl);
    	curl_close($curl);

    	if ($err) 
    	{
        	echo "cURL Error #:" . $err;
     echo "<br> $url <br>";
    	}
    	if ((stripos($response, "Forbidden") == true) or (stripos($response, "Unauthorized") == true)) 
    	{
        	echo "<h2>Alert Messages</h2><div class=\'alert\'> WARNING: BAD API KEY, PLEASE RETURN TO <a href=\'cgKey.php\'>PREVIOUS PAGE</a> AND RE-ENTER</div>";
    	}
  
echo '
    <center>
    <table width="1300" cellpadding="20" height=900>
    <tr width="1300">
            <td><table border=1 bgcolor="#FFFFFF" width="850" height="900">
            <td style="padding-left: 8px; padding-right: 8px;">
     <br><a href="templatedropper.php">CLICK HERE TO DROP A LIST AND TEMPLATE</a> <br>
                <form action="segConfirmSubmission.php"  onsubmit="countaddresses() recipientcount() " method="POST" height="900">
                <!-- <form action="segConfirmSubmission.php"  onsubmit="prepsubmit(), countaddresses() recipientcount()" method="POST" height="900">-->
                    <input name="apikey" type="hidden" value="$hash;"/>
                    <input name="apiroot" type="hidden" value="'.$apiroot.'"/>
                    <input id="recipientCount" name="recipientCount" type="hidden" value=""/>
                    <input id="segmented" name="segmented" type="hidden" value="FALSE"/>
                    <h3>Select a Template (Showing Published Templates Only):*</h3><select id="Template" name="Template">
';

                        buildTemplateList ($apikey, $apiroot);
echo '                   
                    </select> 
                    <h3>Select a Recipient List or Manually Enter Data:*</h3>
                    <select id="Recipients" name="Recipients" onchange="recipientcount(), buildsegdrop()">
';

                    	global $apikey, $apiroot;
                      buildRecipientList ($apikey, $apiroot);
echo '                      
                    </select>
                    <h3>Select a Field :*</h3>
                    <select id="segList1" name="segList1"></select> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <select id="operand1" name="operand1"><option value="==">==</option><option value="!=">!=</option><option value="<">&lt;</option><option value=">">&gt;</option><option value=">=">&gt;=</option><option value="<=">&lt;=</option></select>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" id="segValue1" name="segValue1">
                    <br><br>
                    <select id="segList2" name="segList2"></select> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <select id="operand2" name="operand2"><option value="==">==</option><option value="!=">!=</option><option value="<">&lt;</option><option value=">">&gt;</option><option value=">=">&gt;=</option><option value="<=">&lt;=</option></select>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" id="segValue2" name="segValue2">
                    <br><br>
                    <select id="segList3" name="segList3"></select> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <select id="operand3" name="operand3"><option value="==">==</option><option value="!=">!=</option><option value="<">&lt;</option><option value=">">&gt;</option><option value=">=">&gt;=</option><option value="<=">&lt;=</option></select>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" id="segValue3" name="segValue3">
                    <br><br>
                    <select id="segList4" name="segList4"></select> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <select id="operand4" name="operand4"><option value="==">==</option><option value="!=">!=</option><option value="<">&lt;</option><option value=">">&gt;</option><option value=">=">&gt;=</option><option value="<=">&lt;=</option></select>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" id="segValue4" name="segValue4">
                    <br><br>
                    <select id="segList5" name="segList5"></select> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <select id="operand5" name="operand5"><option value="==">==</option><option value="!=">!=</option><option value="<">&lt;</option><option value=">">&gt;</option><option value=">=">&gt;=</option><option value="<=">&lt;=</option></select>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" id="segValue5" name="segValue5">
                    <br><br><br>
					<input type="button" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="getSegment(), countaddresses()" value="Get Segmented List">
					<br><br>Number of Recipients after Filter (segmentation):
					<input id="filteredcount" name="filteredcount" readonly type="textnormal" style="border:none">
<textarea id="json" name="json" class="text" maxlength="675000" cols="120" placeholder=
\'{"recipients":[
{"address":"jeff.goldstein@sparkpost.com","UserName":"Sam Smith","substitution_data":{"first":"Sam","id":"342","city":"Princeton"}},
{"address":"austein@hotmail.com","UserName":"Fred Frankly","substitution_data":{"first":"Fred","id":"38232","city":"Nowhere"}},
{"address":"jeff@geekswithapersonality.com","UserName":"Zachary Zupers","substitution_data":{"first":"Zack","id":"9","city":"Hidden Town"}}
]}\'></textarea>
					<br><br>Input Global Substitution Data in JSON Format up to 70k of data<br>
    				<textarea id="globalsub" name="globalsub" class="text" maxlength="70000" cols="120" style="display:block;"placeholder=
    			\'"substitution_data": {
"subject" : "More Wonderful Items Picked for You",
"link_format": "style= \"font-family: arial, helvetica, sans-serif; color: rgb(85,85, 90); font-size: 12px; text-decoration: none;\"",
"dynamic_html": {
	"member_level" : "<strong>GOLD</strong>",
	"job1" : "<a data-msys-linkname=\"jobs\" {{{link_format}}} href=\"https://www.messagesystems.com/careers\">Inside Sales Representative, San Francisco, CA</a>",
	"job2" : "<a data-msys-linkname=\"jobs\" {{{link_format}}} href=\"https://www.messagesystems.com/careers\">Sales Development Representative, San Francisco, CA</a>",
	"job3" : "<a data-msys-linkname=\"jobs\" {{{link_format}}} href=\"https://www.messagesystems.com/careers\">Social Media Marketing, San Francisco, CA</a>",
	"job4" : "<a data-msys-linkname=\"jobs\" {{{link_format}}} href=\"http://page.messagesystems.com/careers\">Platform Developer, Columbia, MD</a>",
	"job5" : "<a data-msys-linkname=\"jobs\" {{{link_format}}} href=\"http://page.messagesystems.com/careers\">Rain Catcher & Beer Drinker, Seattle, WA</a>"
},
"default_jobs": ["job1", "job3"],
"backgroundColor" : "#ffffff",
"company_home_url" : "www.sparkpost.com",
"company_logo" : "https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTVYSp0xUPD8yNMYOyTS20VZBwbzt4J-pjta3FtjcT_0rM-cj2o"
}\'></textarea>
                    <h3>Launch now OR enter data & time of campaign launch (YYYY-MM-DD HH:mm)*
                    <div class="tooltip"><a><img height="35" src="https://dl.dropboxusercontent.com/u/4387255/info.png" width="35"></a> 
                    <span class="tooltiptext">Note:<br>1) Campaigns scheduled within 10 minutes of running cannot be cancelled.<p>2) Campaigns can only be scheduled less than 32 days out.</span></div></h3>
                    <input checked id="now" name="now" type="checkbox" value="T"> OR
                    Enter Date/Time <input data-format="YYYY-MM-DD" data-template="YYYY-MM-DD" id="datepicker" name="date" placeholder="YYYY-MM-DD" type="text">
                    <input data-format="HH" data-template="HH" max="23" min="0" name="hour" size="6" type="number" value="00"> <input data-format="MM" data-template="MM" max="59"
                    min="0" name="minutes" size="6" type="number" value="00"> 
 ';

                    	$tzSelect = buildTimeZoneList ();
                    	echo $tzSelect;
echo '                   
                    <h3>Campaign Name:*</h3><input id="campaign" name="campaign" required="" type="text" placeholder="Please Enter a Campaign Name"><br>
                    <br>
                	<h3>Global Return Path (Required for Elite/Enterprise SparkPost Users):*</h3>
                	<input id="returnpath" name="returnpath" type="text">@
                	<select id="domain" name="domain" value="reply" onfocus="if (this.value==\'reply\') this.value\'\';"/>
       ';

                    	buildDomainList ($apikey, $apiroot);
                	
echo '
                	</select>
                	<br><br>
                    <input checked id="open" name="open" type="checkbox" value="T"> Turn on Open Tracking<br>
                    <input checked id="click" name="click" type="checkbox" value="T"> Turn on Click Tracking<br>
                    <h3>Optional Items (leave blank if you don\'t want to use them)...</h3>
                    <h4>Want Proof, Enter Your Email Address Here</h4><input name="email" type="email" placeholder="Email Address">
                    <h4>Enter Meta Data: first column Is the Metadata Field Name, the second column is the data:</h4>
                    Metadata Field Name: <input id="meta1" name="meta1" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input id="data1" name="data1" type="textnormal" value=""><br>
                    <br>
                    Metadata Field Name: <input id="meta2" name="meta2" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input id="data2" name="data2" type="textnormal" value=""><br>
                    <br>
                    Metadata Field Name: <input id="meta3" name="meta3" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input id="data3" name="data3" type="textnormal" value=""><br>
                    <br>
                    Metadata Field Name: <input id="meta4" name="meta4" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input id="data4" name="data4" type="textnormal" value=""><br>
                    <br>
                    Metadata Field Name: <input id="meta5" name="meta5" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input id="data5" name="data5" type="textnormal" value=""><br>
                    <br>
                    <br>
                    <input id="submit" size="10" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" type="submit" value="Submit"> 
                    <input size="10" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" type="reset" value="Reset" onclick="resetpreview(), resetsummary()"><p><p>
                </form></td></table></td>
            </tr>
        </table>
</center>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* Mandatory fields
    <table cellpadding="25" border=0><tr><td>
    <br>
    <h3>Preview Uses Selected Template and First Member of Stored Recipient List (not filtered)</h3>
    <input type="button" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="generatePreview(), recipientcount()" value="Preview">
	&nbsp;&nbsp;
	<input type="button" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="generatePreview(), sendTestEmail()" value="Send Test Email"><br>
	<br>
	<input id="previewTestEmails" name="previewTestEmails" type="previewEmailEntries" placeholder="Comma Separated Email Addresses to Use for Test Emails"><br>
	&nbsp;&nbsp;<br><br>
    <i>**This feature is still in beta...Still working on error messaging...Large Recipient Lists may cause the Preview to malfunction</i>
    <div class="main">
        <iframe height="600" id="iframe1" name="iframe1" width="1300" style="background: #FFFFFF;" srcdoc="<p>Please select your Template and Recipient List</p>"></iframe>
    </div></td></tr></table>
</body>
</html>

';
