<?php


include('common.php');
$now = time();
$action = $_POST['action'];


if ($action == "logout"){
  $_SESSION['ver']  = "false";
  header('location: ./index.php');
  exit(0);
}

echo '
  <form name=fm1 action=# method=POST>
    <p><h1>ARE YOU SURE?</h1></p>
    <input type="hidden" id="action" name="action" value="logout">
    <input type="submit" value="LOGOUT">   <input type="button" value="CANCEL" onclick="window.history.back()";>
  </form>
';

?>

