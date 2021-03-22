<?php 
session_start();
$email = $_POST['email'];
$passwd = $_POST['passwd'];
$uid = $_POST['uid'];
$action = $_POST['action'];

if (substr($uid,-14) == "@sparkpost.com") {
  if ($passwd == "Friday") {
    $_SESSION['ver']  = "true";
    $_SESSION['User']  = $uid;
    header('location: ./index.php');
    exit(0);
  }
  else{
    echo "Sorry, no dice</br>";
    echo "kthxbye";
  }
}

// if ($action == "logout"){
  $_SESSION['ver']  = "false";
  header('location: ./index.php');
//}

?>
