<?php
session_start();
$verified = $_SESSION['ver'];

// make sure we start with a secure connection.
if ($_SERVER['SERVER_PORT'] != "443"){
   header("Location: https://app.trymsys.net/home.php");
   die();
}

echo '
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\" class="no-js">
<head>
<title>'.$apptitle.'</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="styles.css">
<link rel="stylesheet" type="text/css" href="dropzone/dist/dropzone.css">
<link rel="stylesheet" href="css">
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script type="text/javascript" src="scripts.js"></script>
<script type="text/javascript" src="helperscripts.js"></script>
<script src="./dropzone/dist/dropzone.js"></script>

';

echo "
<script>(function(e,t,n){var r=e.querySelectorAll(\"html\")[0];r.className=r.className.replace(/(^|\s)no-js(\s|$)/,\"$1js$2\")})(document,window,0);</script>

</head>
<body id='bkgnd' onload='cleanup()'>
";

include_once("/analyticstracking.php");

// If not logged in, request credentials

if ($verified != "true"){
    echo "<div class=\"result critical\">
            <h2 class=\"critical\">You need to log in to use this site</h2>
          </div>
          <form action=\"/login.php\" method=\"post\" id=\"form1\">
            Secret: <input type=\"password\" name=\"secret\">
            <button type=\"submit\" form=\"form1\" value=\"Submit\">Submit</button>
          </form>
    ";
exit(0);
}

if (!$apidomain){
    ini_set('post_max_size', '200000');
    $hash = $_GET["apikey"];
    $apikey = hex2bin($hash);
    $apiroot = $_GET["apiroot"];
    $apiroot = trim ($apiroot); //remove any white space
        if (substr($apiroot, -1) == "/") $apiroot = substr($apiroot, 0, -1); //get rid of trailing slash
}

    include ('cgPHPLibraries.php');

echo '
<center>

<ul class="topnav" id="generatorTopNav">
  <li><a href="index.php">Home</a></li>
  <li><a href="help.php">Help</a></li>
  <li><a href="https://developers.sparkpost.com/" target="_blank">SparkPost Documentation</a></li>
  <li><a href="'.$contactlink.'">Contact</a></li>
</ul>
  <p><h1>'.$apptitle.'</h1></p>

';


