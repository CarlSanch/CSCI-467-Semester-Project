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

         $id_full = $_POST["quoten"];
         $id_array = preg_split("/-/", $id_full);
         //id_array[0] = Company ID
         //id-array[1] = Quote ID
         $data = "SELECT Date_of_Quote, Associate_Name, Company_Name, Company_City, Company_Street, Contact_Info, Email 
         FROM quote_info WHERE COMPANY_ID = '".$id_array[0]."' AND QUOTE_ID = '".$id_array[1]."'";
         $quote_data = $mysqli->query($data);

         if($quote_data->num_rows > 0)
         {
              while($row = $quote_data->fetch_assoc())
              {
                  echo "<h4> " . $row['Company_Name'] . "</h4>";
                  echo "<h5> " . $row['Company_Street'] . "</h5>";
                  echo "<h5> " . $row['Company_City'] . "</h5>";
                  echo "<p></p>";
                  echo "<h5> " . "Contact: " . $row['Contact_Info'] . "</h5>";
                  echo "<h5> " . "Email: " . $row['Email'] . "</h5>";
                  echo "<p></p>";
                  echo "<h5> " . "Quote Author: " . $row['Associate_Name'] . "</h5>";
                  echo "<h5> " . "Quote Date: " . $row['Date_of_Quote'] . "</h5>";
                  echo "<p></p>";
                  
              }
         }

         $data = "SELECT Company_ID, Quote_ID, Line_ID, Item_Description, Item_Price FROM quote_item_info
          WHERE COMPANY_ID = '".$id_array[0]."' AND QUOTE_ID = '".$id_array[1]."'";
         $quote_line_data = $mysqli->query($data);

         $final_price = 0;
         echo "<form action='send_quote_table.php' method='post' name='megaForm'><table style='margin-bottom:50px;' id='itemTable'><tr><th>Item Description</th><th>Price</th><th>Delete</th>";
         if($quote_line_data->num_rows > 0)
         {
              
              while($row = $quote_line_data->fetch_assoc())
              {
                  $companyida = $row['Company_ID'];
                  //printf('"'.$companyida.'"');
                  $quoteida = $row['Quote_ID'];
                  echo "<input type = 'hidden' name='companyID' value='".$companyida."'>";
                  echo "<input type = 'hidden' name='quoteID' value='".$quoteida."'>";
                  echo "<tr class='center_text'><td><textarea name='item[]' class='textboxes'>" . $row['Item_Description'] . "</textarea></td>";
                  echo "<td><textarea name='price[]' class='textboxes' onfocusout='updateFinal(this)' onfocus='subFinal(this)'>" . $row['Item_Price'] . "</textarea></td>";
                  echo "<td><button type='button' onclick='removeLine(this)'>Remove Item</button></td></tr>";
                  $final_price += $row['Item_Price'];
              }
              echo "<tr id='newItem'><td><button type='button' onclick='addLine()'>Add Item</button></td><td><button type='button' onclick='addLine()'>Add Item</button></td><td><button type='button' onclick='addLine()'>Add Item</button></td></tr>";
                          
         }
         echo "<tr class='center_text'><td><textarea id='finalPrice' name='finalPrice' class='textboxes'>Final Price: " . $final_price ."</textarea></td><td><button type='button' onclick='this.form.submit()'>Upload Quote</button></td>";
         echo "<td><button type='button' onclick='this.form.submit()'>Upload and Sanction</button></td>";
         echo "</tr>";
         echo "</table>"; 
         //echo "<table><tr><td>space</td></tr></table>";
         $data = "SELECT Company_ID, Quote_ID, Secret_Note FROM note_info
          WHERE COMPANY_ID = '".$id_array[0]."' AND QUOTE_ID = '".$id_array[1]."'";
         $note_data = $mysqli->query($data);

         echo "<table id='noteTable'><tr><th>Notes</th><th>Delete</th></tr>";
         if($note_data->num_rows > 0)
         {
              
              while($row = $note_data->fetch_assoc())
              {
                  echo "<tr><td><textarea name='note[]' class='textboxes'> " . $row['Secret_Note'] . "</textarea></td><td><button type='button' onclick='removeNoteLine(this)'>Remove Note</button></td></tr>";        
              }
              
                            
         }
         echo "<tr id='newNote'><td><button type='button' onclick='addNote()'>Add Note</button></td><td><button type='button' onclick='addNote()'>Add Note</button></td></tr>";
         echo "</table></form>";
        
         

?> 