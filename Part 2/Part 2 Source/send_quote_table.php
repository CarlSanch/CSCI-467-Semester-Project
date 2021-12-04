<?php
         include ("basichtml.html");

         $dbhost = 'enter host here (localhost for local servers)';
         $dbuser = 'root (select a database user. Root has access to everything usually)';
         $dbpass = 'Enter the password for the selected user';
         $dbname = 'Enter the name of your database here';
         $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
         
         if($mysqli->connect_errno ) {
            printf("Connect failed: %s<br />", $mysqli->connect_error);
            exit();
         }
         //printf('Connected successfully.<br />');

         //$description_array
         //$price_array
         //$note_array
         //$final_cost

         $company_id = $_POST["companyID"];
         $quote_id = $_POST["quoteID"];
         $items = $_POST["item"];
         $prices = $_POST["price"];
         $data = "DELETE FROM quote_item_info WHERE Company_ID = '".$company_id."' AND Quote_ID = '".$quote_id."'";
         $mysqli->query($data);
         $data = "DELETE FROM note_info WHERE Company_ID = '".$company_id."' AND Quote_ID = '".$quote_id."'";
         $mysqli->query($data);
         $data = "DELETE FROM quote_final_price WHERE Company_ID = '".$company_id."' AND Quote_ID = '".$quote_id."'";
         $mysqli->query($data);
         $data = "INSERT INTO quote_item_info () VALUES ()";

         $item_length = count($items);
         for($i=0;$i<$item_length;$i++)
         {
            $data = "INSERT INTO quote_item_info (Company_ID,Quote_ID,Line_ID,Item_Description,Item_Price) VALUES (" . "'".$company_id."'" . "," . "'".$quote_id."'" . "," . "'".$i."'"
            . "," . "'".$items[$i]."'" . "," . "'".$prices[$i]."'" . ");";
            $mysqli->query($data);
         }

         $notes = $_POST["note"];
         $notes_length = count($notes);
         for($i=0;$i<$notes_length;$i++)
         {
            $data = "INSERT INTO note_info (Company_ID,Quote_ID,Secret_Note) VALUES (" . "'".$company_id."'" . "," . "'".$quote_id."'" . "," . "'".$notes[$i]."'" . ");";
            $mysqli->query($data);
         }

         $final_pricing = $_POST["finalPrice"];
         $final_prici = explode(" ",$final_pricing);
         $final_pricin = (float)$final_prici[1];
         $data = "INSERT INTO quote_final_price (Company_ID,Quote_ID,Final_Price) VALUES (" . "'".$company_id."'" . "," . "'".$quote_id."'" . "," . "'".$final_pricin."'" . ");";
         $mysqli->query($data);
         

         


         
?> 