<?php

include('common.php');
$now = time();

echo "Upload a new HTML or TEXT file. If there are no <TAGs> in the file, TEXT will automatically be assumed.<br>";
echo '<form action="upload.php" method="post" enctype="multipart/form-data">
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="Upload" name="submit">
</form>
';


