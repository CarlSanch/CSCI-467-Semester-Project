<html>

<style>
    h2 {text-align: center;}
</style>

<h2>
    Quote Processing Page
</h2>

<body>

    <?php    
    $url = 'http://blitz.cs.niu.edu/PurchaseOrder/';
    $order = $_POST["Order"];
    $discount = $_POST["Discount"];

    //Retrieve Data from the database
    
        $servername = "localhost";
        $username = "geads";
        $password = "geads";
        $dbname = "quote_database";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "SELECT Company_ID, Quote_ID, Email, Associate_Name FROM quote_info WHERE Quote_ID=" . $order;
        $sql_quote_data = mysqli_query($conn, $sql);

        $sql = "SELECT Quote_ID, Final_Price FROM quote_final_price WHERE Quote_ID=" . $order;
        $sql_quote_price = mysqli_query($conn, $sql);

        //Each quote value should be unique
        if ($sql_quote_data->num_rows == 1 && $sql_quote_price->num_rows == 1) {
            //Quote was found, we may process it.
            $sql_quote_data = $sql_quote_data->fetch_assoc();
            $sql_quote_price = $sql_quote_price->fetch_assoc();
            $order = $sql_quote_data["Quote_ID"];
            $associate = $sql_quote_data["Associate_Name"];
            $id = $sql_quote_data["Company_ID"];
            $amount = $sql_quote_price["Final_Price"] - $discount;

            //Send to processing system
            $data = array(
                'order' => $order, 
                'associate' => $associate,
                'custid' => $id, 
                'amount' => $amount);

            $options = array(
                'http' => array(
                    'header' => array('Content-type: application/json', 'Accept: application/json'),
                    'method'  => 'POST',
                    'content' => json_encode($data)
                )
            );

            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            $output = json_decode($result, true);

            //Process the returned values from the processing system if there was no error
            if( !array_key_exists('errors', $output) ){
 
                //Update the final price
                $sql = "UPDATE quote_final_price SET Final_Price=" . $amount . " WHERE Quote_ID=" . $order;
                if (mysqli_query($conn, $sql)) {
                    echo "Quote price updated successfully". "<br>";
                } else {
                    echo "Error updating quote: " . mysqli_error($conn). "<br>";
                }

                //Compute the Commission
                $commission = round((int)substr($output['commission'], 0, strlen($output['commission'])) * $output['amount'] / 100, 2);
                echo("Associate " . $output['associate'] . " earned $" . number_format($commission, 2) . "<br>");

                //Save the commission in the quote  
                $sql_associate = mysqli_query($conn, $sql);
                $sql = "UPDATE quote_info SET Ordered=1, Comission=" . $commission . ", Process_Date='" . $output['processDay'] . "' WHERE Quote_ID=" . $order;
                if (mysqli_query($conn, $sql)) {
                    echo "Comission updated successfully in quote". "<br>";
                } else {
                    echo "Error updating quote: " . mysqli_error($conn). "<br>";
                }

                //Save the commission for the associate
                $sql = "SELECT Total_Commission FROM associate_info WHERE Name='" . $output['associate'] . "'";
                $sql_associate = mysqli_query($conn, $sql);
                $sql_associate = $sql_associate->fetch_assoc();
                $sql = "UPDATE associate_info SET Total_Commission=" . $sql_associate["Total_Commission"] + $commission . " WHERE Name='" . $output['associate'] . "'";
                if (mysqli_query($conn, $sql)) {
                    echo "Comission updated successfully in associate info". "<br>";
                } else {
                    echo "Error updating comission: " . mysqli_error($conn). "<br>";
                }

                //Send the Email
                $to = $sql_quote_data["Email"];
                $subject = "Order " . $output['order'] . "has been processed";
                        
                $message = ("Your order #" . $output['order'] . " for $" . $output['amount'] . " has been processed on " . $output['processDay'] . "<br>");
                
                        
                $header = "From:processingsystem@company.com \r\n";
                $header .= "Content-type: text/html\r\n";
                        
                $retval = mail ($to,$subject,$message,$header);
                        
                if( $retval == true ) {
                    echo "Email sent successfully to " . $to . ".". "<br>";
                    echo "Message:" . "<br>";
                    echo($message. "\r\n");
                }
                else {
                    echo "Email failed to send to " . $to . ".". "<br>";
                }
            }
            //Cancel processing, the system returned an error
            else{
                echo "Transaction was not processed due to:". "<br>";
                foreach($output['errors'] as $error) {
                    echo " " . $error. "<br>";
                }
            }
        }
        //The quote was not found in the database
        else {
            die("Error: quote #". $order . " not found.". "<br>");
        }
?>

</body>

</html>