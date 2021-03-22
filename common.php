<?php

// If not using HTTPS, bail
if (($_SERVER['REMOTE_PORT']) AND ($_SERVER['SERVER_PORT'] != "443")){
  echo "Please use HTTPS";
  exit;
}

include('env.php');

date_default_timezone_set($TZ);
$vermajor = "0"; // Major version number
$verminor = "1";  // How many times _TODAY_ have you made _COMMITS_

// Build the most ressent version from file tags
$filelocation = dirname(__FILE__);
$filename = $filelocation . '/changelog.txt';
$verdate = date ("Y-m-d H:i:s", filemtime($filename));
$vertiny = date ("Y-m-d", filemtime($filename));
$AppVersion = "$vermajor.$verminor.$vertiny";
$nRelease = $verdate;

include('header.php');
$now = time();
$onehourago = $now - 3600;
$lastmonth = $now - (30*24*3600);

 
// Build DB Access structure
    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); 
    try 
    { 
        $db = new PDO("mysql:host={$dbhost};dbname={$dbname};charset=utf8", $dbuser, $dbpass, $options); 
    } 
    catch(PDOException $ex) 
    { 
        // Note: On a production website, you should not output $ex->getMessage(). 
        // It may provide an attacker with helpful information about your code 
        die("Failed to connect to the database: " . $ex->getMessage()); 
    } 
     
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
     
    // This statement configures PDO to return database rows from your database using an associative 
    // array.  This means the array will have string indexes, where the string value 
    // represents the name of the column in your database. 
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
     
    // http://php.net/manual/en/security.magicquotes.php 
    if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) 
    { 
        function undo_magic_quotes_gpc(&$array) 
        { 
            foreach($array as &$value) 
            { 
                if(is_array($value)) 
                { 
                    undo_magic_quotes_gpc($value); 
                } 
                else 
                { 
                    $value = stripslashes($value); 
                } 
            } 
        } 
     
        undo_magic_quotes_gpc($_POST); 
        undo_magic_quotes_gpc($_GET); 
        undo_magic_quotes_gpc($_COOKIE); 
    } 
     
    header('Content-Type: text/html; charset=utf-8'); 
     
    session_start(); 

  if ($Owner == ""){
    // Launch auth system

    // Fake it for now
    $Owner = "Mickey";
  }



