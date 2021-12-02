<html>

<body>

    <?php
    $url = 'http://blitz.cs.niu.edu/PurchaseOrder/';
    $order = $_POST["Order"];
    $associate = $_POST["Associate"];
    $id = $_POST["ID"];
    $amount = $_POST["Amount"];
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

    $commission = round((int)substr($output['commission'], 0, strlen($output['commission'])) * $output['amount'] / 100, 2);
    echo("Associate " . $output['associate'] . " earned $" . $commission . "\r\n");

    $to = "z1861317@students.niu.edu";
            $subject = "Order " . $output['order'] . "has been processed";
            
            $message = ("Your order #" . $output['order'] . " for $" . $output['amount'] . " has been processed on " . $output['processDay'] . "\r\n");
            echo($message);
            
            $header = "From:abc@somedomain.com \r\n";
            $header .= "Content-type: text/html\r\n";
            
            $retval = mail ($to,$subject,$message,$header);
            
            if( $retval == true ) {
                echo "Message sent successfully...";
            }else {
                echo "Message could not be sent...";
            }
?>

    <html>

    <body>