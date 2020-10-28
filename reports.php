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

include('env.php');
include('header.php');
$now = time();


/**** Get any POSTED vars *******/
$from = $_POST['from'];
$to = $_POST['to'];
$metrics = $_POST['metrics'];
$precision = $_POST['precision'];
$delimiter = $_POST['delimiter'];
$domains = $_POST['domains'];
$campaigns = $_POST['campaigns'];
$templates = $_POST['templates'];
$sending_ips = $_POST['sending_ips'];
$ip_pools = $_POST['ip_pools'];
$sending_domains = $_POST['sending_domains'];
$subaccounts = $_POST['subaccounts'];
$timezone = $_POST['timezone'];


/******  Set Defaults ********/
if (!$from){
  $from = date("Y-m-d\TH:i:s",($now-36000));
}
if (!$to){
  $to = date("Y-m-d\TH:i:s",$now);
}

if (!$metrics){
  $metrics .= "count_targeted,";
  $metrics .= "count_rendered,";
  $metrics .= "count_accepted,";
  $metrics .= "count_bounce";
}

if ((strtotime($to)-strtotime($from)) > (3*86400)){
  $precision = "day";
}

if ((strtotime($to)-strtotime($from)) > (32*86400)){
  $precision = "month";
}

if (!$precision){
  $precision = "hour";
}


/**** Push any data into an array for the API call *****/
$data['from']=$from;
$data['to']=$to;
$data['metrics']=$metrics;

if($delimeter){
  $data['delimeter']=$delimeter;
}
if($domains){
  $data['domains']=$domains;
}
if($campaigns){
  $data['campaigns']=$campaigns;
}
if($templates){
  $data['templates']=$templates;
}
if($sending_ips){
  $data['sending_ips']=$sending_ips;
}
if($ip_pools){
  $data['ip_pools']=$ip_pools;
}
if($sending_domains){
  $data['sending_domains']=$sending_domains;
}
if($subaccounts){
  $data['subaccounts']=$subaccounts;
}
if($timezone){
  $data['timezone']=$timezone;
}
if($precision){
  $data['precision']=$precision;
}


/******* API call for the data *********/
$apiroot = "https://".$apidomain."/api/v1/";
$method = "metrics/deliverability/time-series";
$conditions =  http_build_query($data);;
$call = $apiroot.$method."?".$conditions;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $call);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/x-www-form-urlencoded",
  "Authorization: $apikey"
));

$response = curl_exec($ch);
curl_close($ch);


/******** Build the graph data **********/

$response_d = json_decode($response,true);

$datatable = " ['Time', 'count_targeted','count_rendered','count_accepted','count_bounce'],\n";

foreach($response_d as $a => $b){
  foreach($b as $c => $d){
    foreach($d as $e => $f){
      $myval[$e] = $f;
      if ($e == "ts"){
        $ts = strtotime($f);
        if ($precision == "hour"){
          $ts = date("H:i", $ts);
        }
        if ($precision == "day"){
          $ts = date("M d", $ts);
        }
        if ($precision == "month"){
          $ts = date("M", $ts);
        }
        $datatable .= "[ '". $ts ."', ". $myval['count_targeted'] .", ". $myval[count_rendered] .", ". $myval[count_accepted] .", ". $myval[count_bounce] ." ],\n";
      }
    }
  }
}


/***** Display options and inputs *****/
/**** Push any data into an array for the API call *****/
echo"
<p>
<center>
 <form method=post>
  <table>
   <tr><td> FROM: <input type=test name=from value=\"$from\"> </td><td> TO: <input type=text name=to value=\"$to\"></td></tr>
   <tr><td> Domains:$domains</td><td> Campaigns: $campaigns</td></tr>
   <tr><td> Templates: $templates</td><td> Sending IPs: $sending_ips</td></tr>
   <tr><td> IP Pools: $ip_pools</td> <td>Sending Domains: $sending_domains</td></tr>
   <tr><td> SubAccounts: $subaccounts</td><td> Timezone: $timezone</td></tr>
   <tr><td colspan=2> Metrics: $metrics</td></tr>
   <tr><td> Precision: ";

echo '<select name=precision>
        <option value="'.$precision.'">'.$precision.'</option>
        <option value="hour">hour</option>
        <option value="day">day</option>
        <option value="month">month</option>
     </select>';

echo "</td><td><input type=submit name=submit value=Refresh></td></tr>
  </table>
 </form>
</center>
</p>
";




/**** Now render the graph *****/
?>

<p>

<!-- This needs to be cleaned up -->

<html>
  <head>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript">
    
    // Load the Visualization API and the piechart package.
    google.charts.load('current', {'packages':['corechart']});
      
    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);
      

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
          <?php echo $datatable ?>
        ]);

        var options = {
          width: 900,
          height: 400,
          title: ' Performance',
          curveType: 'function',
          legend: { position: 'bottom' }
        };



      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }

    </script>
  </head>

  <body>
   <table>
     <tr><td><div>
        <b>Reports</b><br>
        <a href="">Performance Summary</a><br>
        Hardware<br>
        Engagement Patterns<br>
        Domain Spread <br>

     </div></td>
     <td> <div id="chart_div"></div></td>
     <td><div>
      <b>Metrics Options</b><br>
count_injected<br>count_bounce<br>count_rejected<br>count_delivered<br>count_delivered_first<br>count_delivered_subsequent<br>total_delivery_time_first<br>total_delivery_time_subsequent<br>total_msg_volume<br>count_policy_rejection<br>count_generation_rejection<br>count_generation_failed<br>count_inband_bounce<br>count_outofband_bounce<br>count_soft_bounce<br>count_hard_bounce<br>count_block_bounce<br>count_admin_bounce<br>count_undetermined_bounce<br>count_delayed<br>count_delayed_first<br>count_rendered<br>count_unique_rendered<br>count_unique_confirmed_opened<br>count_clicked<br>count_unique_clicked<br>count_targeted<br>count_sent<br>count_accepted<br>count_spam_complaint<br>
      </div></td>
     </tr>
   </table>

  </body>
</html>







<?php


include ('d_footer.php');

echo '
</center>
</body>
</html>
';
?>

