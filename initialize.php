<?php

   include ("env.php");
   $firstpass = $_POST['first'];
   $a_user =  $_POST['user'];
   $a_pass =  $_POST['pass'];
   
   if ($_SERVER['SERVER_PORT'] != "443"){
     echo "Please use HTTPS";
     exit;
   }

   if ($firstpass != "false"){
   echo "
    <html><body>
    <p>
      This is the initialization script.  Running this will wipe and replace the database configured in the 
       environment file.<br />
      Please set the desired database name, username, and password in the <i>env.php</i> file BEFORE running this script.<br />
      \$LNHome = \"https://10.79.0.15/licenseninja-dev/\";  // This is the installed location of the web service  <br />
      \$TZ = 'America/Los_Angeles'; //This is the Linux standard region format<br />
      \$dbhost = 'localhost'; // should be left as \"localhost\"<br />
      \$dbuser = 'licenseman-dev';  // The desired DB username<br />
      \$dbpass = 'mydevpassword';  // The desired DB Password<br />
      \$dbname = 'LicenseNinja-dev';  // The desired DB Name<br />
      \$p_email = \"LicenseNinjaDEV@messagesystems.com\";  // The email address used for panic mode license generation<br />
      \$p_passwd = \"MyPrivatePassword\";  // The password for panic mode license generation<br />
      <br />
      If the above is set, continue below:<br />
      <br />
      <form name=\"dbinit\" method=\"POST\" action=\"#\">
      <table>
       <tr>
        <td>Root or authorized username for MySQL administration<br /> (must have GRANT priv):</td>
        <td><input type=\"text\" name=\"user\"></td>
       </tr>
       <tr>
        <td>Root or authorized user password for MySQL administration<br /> (will not be saved):</td>
        <td><input type=\"password\" name=\"pass\"><input type=\"hidden\" name=\"first\" value=\"false\"></td>
       </tr>
       <tr>
        <td><input type=\"submit\" value=\"Create DB\"></td>
        <td>&nbsp;</td>
       </tr>
     </table>
     </form>

   ";

// continue with DB Build-out

   }
   else{
 
   echo "
    <html><body>
    <p>
     Creating Database ".$dbname." as user ".$a_user." <br />";

    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
    try
    {
        $db = new PDO("mysql:host={$dbhost};charset=utf8", $a_user, $a_pass, $options);
    }
    catch(PDOException $ex)
    {
        // Note: On a production website, you should not output $ex->getMessage().
        // It may provide an attacker with helpful information about your code
        die("Failed to connect to the database: " . $ex->getMessage());
    }

    $query = "CREATE database ".$dbname."";
    $query_params = array(':DB' => $dbname);

    echo "<font color=red>". $query ."</font><br>\r\n";

      try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
            if ($result == 1){$result_en = "success";
              echo "<font color=green>". $result_en ."</font><br>\r\n";
            }
        }
      catch(PDOException $ex)
        {
            // Note: On a production website, you should not output $ex->getMessage().
            // It may provide an attacker with helpful information about your code.
           die("Failed to create DB: " . $ex->getMessage());
        }


// Function maeTable()
// takes vars in to build tables
function makeTable($db1,$dbname1,$tblName,$tblQry){

    echo "Creating $tblName Table <br />";
    $query = "CREATE TABLE ".$dbname1.".".$tblName." ( ".$tblQry." ) ENGINE=INNODB";
    $query_params = array(':DB' => $dbname1);
    echo "<font color=red>". $query ."</font><br>\r\n";
     try
        {
            $stmt = $db1->prepare($query);
            $result = $stmt->execute($query_params);
            if ($result == 1){$result_en = "success";
              echo "<font color=green>". $result_en ."</font><br>\r\n";
            }
        }
        catch(PDOException $ex)
        {
            // Note: On a production website, you should not output $ex->getMessage().
            // It may provide an attacker with helpful information about your code.
           die("Failed to History table: " . $ex->getMessage());
        }
}

 

  makeTable($db,$dbname,"Users","id INT NOT NULL AUTO_INCREMENT,
      Email VARCHAR(100) NOT NULL,
      FullName VARCHAR(100) NOT NULL,
      PassKey VARCHAR(100) NOT NULL,
      Role VARCHAR(50) NOT NULL,
      PRIMARY KEY (id)");


 makeTable($db,$dbname,"MyTemplates","id INT NOT NULL AUTO_INCREMENT,
      tmpID VARCHAR(100) NOT NULL,
      Name VARCHAR(100) NOT NULL,
      Owner VARCHAR(100) NOT NULL,
      Description VARCHAR(250) NOT NULL,
      GitRef VARCHAR(100) NOT NULL,
      PRIMARY KEY (id)");

 makeTable($db,$dbname,"MyProjects","id INT NOT NULL AUTO_INCREMENT,
      Name VARCHAR(100) NOT NULL,
      Owner VARCHAR(100) NOT NULL,
      Description VARCHAR(250) NOT NULL,
      PRIMARY KEY (id)");

 makeTable($db,$dbname,"Mailings","id INT NOT NULL AUTO_INCREMENT,
      ProjectID BigINT NOT NULL,
      User VARCHAR(100) NOT NULL,
      Description VARCHAR(250) NOT NULL,
      Template VARCHAR(100) NOT NULL,
      StartTime VARCHAR(100) NOT NULL,
      BuildTime VARCHAR(100) NOT NULL,
      Query VARCHAR(100) NOT NULL,
      PRIMARY KEY (id)");


 makeTable($db,$dbname,"MyQueries","id INT NOT NULL AUTO_INCREMENT,
      Name VARCHAR(100) NOT NULL,
      Owner VARCHAR(100) NOT NULL,
      Description VARCHAR(250) NOT NULL,
      Query TEXT NOT NULL,
      PRIMARY KEY (id)");

makeTable($db,$dbname,"Contacts","id INT NOT NULL AUTO_INCREMENT,
      Email VARCHAR(100) NOT NULL,
      FirstName VARCHAR(100),
      MiddleName VARCHAR(100),
      LastName VARCHAR(100),
      Company VARCHAR(100),
      Country VARCHAR(100),
      TZ VARCHAR(10),
      HasMomentum BOOL,
      HasPMTA BOOL,
      HasSparkPost BOOL,
      HasSignals BOOL,
      HasRV BOOL,
      HasIT BOOL,
      HasCT BOOL,
      OptOut BOOL,
      LastContact DATETIME,
      PRIMARY KEY (id)");




 
echo "ADDING SUPERUSER ACCOUNT <br>";
     $rndpass = substr(md5(microtime()),rand(0,26),10);

     $query = "INSERT INTO ".$dbname.".Users (Email,FullName,PassKey,Role) Values(:US,'Sys Admin',:PS,'Superuser')";
     $query_params = array(':US' => $sysadmin,':PS' => $rndpass);
        try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
          if ($result == 1){$result_en = "success";}
        }

        catch(PDOException $ex)
        {
            // Note: On a production website, you should not output $ex->getMessage().
            // It may provide an attacker with helpful information about your code.
           die("Failed to create User: " . $ex->getMessage());
        }



     echo "Creating DB User <br />";
     $query = "CREATE USER :US@'localhost' IDENTIFIED BY :PA";
     $query_params = array(':US' => $dbuser, ':PA' => $dbpass);
        try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
          if ($result == 1){$result_en = "success";
            echo "<font color=green>". $result_en ."</font><br>\r\n";
          }
        }

        catch(PDOException $ex)
        {
            // Note: On a production website, you should not output $ex->getMessage().
            // It may provide an attacker with helpful information about your code.
           die("Failed to create User: " . $ex->getMessage());
        }

     echo "Creating Grant <br />";
     $query = "GRANT ALL PRIVILEGES ON *.* TO '".$dbuser."'@'localhost'";
     $query_params = array(':DB => $dbname, :US' => $dbuser, ':HO' => $dbhost);
     echo "<font color=red>". $query ."</font><br>\r\n";

        try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
          if ($result == 1){$result_en = "success";}
    echo "<font color=green>". $result_en ."</font><br>\r\n";
        }

        catch(PDOException $ex)
        {
            // Note: On a production website, you should not output $ex->getMessage().
            // It may provide an attacker with helpful information about your code.
           die("Failed to create Grant: " . $ex->getMessage());
        }

     $query = "FLUSH PRIVILEGES";

        try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
          if ($result == 1){$result_en = "success";}
        }

        catch(PDOException $ex)
        {
            // Note: On a production website, you should not output $ex->getMessage().
            // It may provide an attacker with helpful information about your code.
           die("Failed to create Grant: " . $ex->getMessage());
        }


     echo "Initialization complete.  If there are no errors above, click <a href=\"".$LNHome."\">HERE</a> to continue: <br />";

}



?>


