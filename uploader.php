<?php
session_start();
$pn = $_SESSION['project'];

  $uploaddir = './file-upload/'. $pn ."/";
  $target = "bad";
  $filedir = "./file-upload";
  foreach(glob($filedir.'/*',GLOB_ONLYDIR) as $file) {
    if ($pn == basename($file)){
      $target = "ok";
    }
  }
  if ($target == "bad"){
    mkdir($uploaddir,0700);
  }


$uploadfile = $uploaddir . basename($_FILES['file']['name']);

//echo '<pre>';
if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
    echo "File is valid, and was successfully uploaded.\n";
} else {
    echo "Possible file upload attack!\n";
}

/*
echo 'Here is some more debugging info:';
print_r($_FILES);

print "</pre>";
*/
?>

