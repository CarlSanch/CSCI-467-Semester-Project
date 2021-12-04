<?php
include ("basichtml.html");
?>

<?php /*
	     $dbhost = 'enter host here (localhost for local servers)';
         $dbuser = 'root (select a database user. root has access to everything usually)';
         $dbpass = 'Enter the password for the selected user';
         $dbname = 'Enter the name of your database here';
         $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
         
         if($mysqli->connect_errno ) {
            printf("Connect failed: %s<br />", $mysqli->connect_error);
            exit();
         }
         printf('Connected successfully.<br />');

         $data = "SELECT Company_ID, Date_of_Quote, Associate_Name, Company_Name FROM quote_info";
         $quote_data = $mysqli->query($data);
         
         if($quote_data->num_rows > 0)
         {
              while($row = $quote_data->fetch_assoc())
              {
                  echo "<option value ='" . $row['Company_ID'] . " " . "'>" .
					  $row['Company_ID'] . " " . $row['Date_of_Quote'] . " " .
					  $row['Associate_Name'] . " " . $row['Company_Name'] . "</option>";
              }
         }
         /*if ($mysqli->query("CREATE DATABASE TESTDATA")) {
            printf("Database TUTORIALS created successfully.<br />");
         }
         if ($mysqli->errno) {
            printf("Could not create database: %s<br />", $mysqli->error);
         }
         */
         //$mysqli->close();
         /*
         echo "<option value ='" . $row['Company_ID'] . " " . $row['Quote_ID'] . "'>" .
					  $row['Company_ID'] . " " . $row['Date_of_Quote'] . " " .
					  $row['Associate_Name'] . " " . $row['Company_Name'] . "</option>";
         */
?>