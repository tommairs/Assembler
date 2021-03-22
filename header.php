<?php
session_start();
$verified = $_SESSION['ver'];

// make sure we start with a secure connection.
if ($_SERVER['SERVER_PORT'] != "443"){
   header("Location: https://app.trymsys.net/cst/Ramesses/index.php");
   die();
}

echo '
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\" class="no-js">
<head>';

include_once("/var/www/html/analyticstracking.php");

echo '<title>'.$apptitle.'</title>
<link rel="shortcut icon" href="./res/favicon.ico" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="src/styles_new.css">
<link rel="stylesheet" type="text/css" href="src/tabs.css">
<!--<link rel="stylesheet" type="text/css" href="styles.css">-->
<!--<link rel="stylesheet" type="text/css" href="dropzone/dist/dropzone.css"> --> 
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script type="text/javascript" src="src/scripts.js"></script>
<script type="text/javascript" src="src/helperscripts.js"></script>
<script src="./dropzone/dist/dropzone.js"></script>

';

echo "
<script>(function(e,t,n){var r=e.querySelectorAll(\"html\")[0];r.className=r.className.replace(/(^|\s)no-js(\s|$)/,\"$1js$2\")})(document,window,0);</script>

</head>
<body id='bkgnd' onload='cleanup()'>
";

// If not logged in, request credentials

if ($verified != "true"){
    echo '<div class="result critical">
            <h2 class="critical">You need to log in to use this site</h2>
          </div>
          <form action="./login.php" method="post" id="form1">
            <table>
              <tr><td>Username:</td><td><input type="username" size=50 name="uid" placeholder="your@email.addr"></td></tr>
              <tr><td>Password:</td><td><input type="password" size=50 name="passwd" placeholder="shhhhhhh....."></td></tr>
              <tr><td>&nbsp;</td><td><button type="submit" form="form1" value="Submit">Submit</button></td></tr>
            </table>
          </form>
    ';
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

 include ('toolbar.php');

echo '
  <p><h1>'.$apptitle.'</h1></p>
';

  if ($AppVersion){
    echo "<a href='changelog.php'><h2>Version $AppVersion</h2></a>";
  }


