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
        $username = "username";
        $password = "password";
        $dbname = "myDB";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "SELECT company_id, quote_id, email, associate_name FROM quote_info WHERE quote_id=" . $id;
        $sql_quote_data = mysqli_query($conn, $sql);

        $sql = "SELECT quote_id, final_price FROM quote_final_price WHERE quote_id=" . $id;
        $sql_quote_price = mysqli_query($conn, $sql);

        //Each quote value should be unique
        if ($sql_quote_data->num_rows == 1 && $sql_quote_price->num_rows == 1) {
            //Quote was found, we may process it.
            $order = $sql_quote_data["quote_id"];
            $associate = $sql_quote_data["associate_name"];
            $id = $sql_quote_data["company_id"];
            $amount = $sql_quote_price["final_price"] - $discount;

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
                $sql = "UPDATE quote_final_price SET quote_price=" . $amount . " WHERE id=" . $id;
                if (mysqli_query($conn, $sql)) {
                    echo "Quote price updated successfully";
                } else {
                    echo "Error updating quote: " . mysqli_error($conn);
                }

                //Compute the Commission
                $commission = round((int)substr($output['commission'], 0, strlen($output['commission'])) * $output['amount'] / 100, 2);
                echo("Associate " . $output['associate'] . " earned $" . number_format($commission, 2) . "\r\n");
                //Save the commission in the quote
                $sql = "UPDATE quote_info SET Ordered=1, Commission=" . $commission . ", Process_Date='" . $output['processDay'] . "' WHERE id=" . $id;
                if (mysqli_query($conn, $sql)) {
                    echo "Comission updated successfully in quote";
                } else {
                    echo "Error updating quote: " . mysqli_error($conn);
                }
                
                //Save the commission for the associate
                $sql = "SELECT Total_Commission FROM associate_info WHERE Name=" . $output['associate'];
                $sql_associate = mysqli_query($conn, $sql);
                $sql = "UPDATE associate_info SET Total_Commission=" . ($sql_associate["Total_Commission"]+ $commission) . " WHERE Name=" . $output['associate'];
                if (mysqli_query($conn, $sql)) {
                    echo "Comission updated successfully in associate info";
                } else {
                    echo "Error updating quote: " . mysqli_error($conn);
                }

                //Send the Email
                $to = $sql_quote_data["email"];
                $subject = "Order " . $output['order'] . "has been processed";
                        
                $message = ("Your order #" . $output['order'] . " for $" . $output['amount'] . " has been processed on " . $output['processDay'] . "\r\n");
                echo($message);
                        
                $header = "From:abc@somedomain.com \r\n";
                $header .= "Content-type: text/html\r\n";
                        
                $retval = mail ($to,$subject,$message,$header);
                        
                if( $retval == true ) {
                    echo "Email sent successfully to " . $to . ".";
                }
                else {
                    echo "Email failed to send to " . $to . ".";
                }
            }
            //Cancel processing, the system returned an error
            else{
                echo "Transaction was not processed due to:";
                foreach($output['errors'] as $error) {
                    echo " " . $error;
                }
            }
        }
        //The quote was not found in the database
        else {
            die("Error: quote #". $id . " not found.");
        }
?>

</body>

</html>