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
    echo($result);

    $to = "z1861317@students.niu.edu";
            $subject = "This is subject";
            
            $message = "<b>This is HTML message.</b>";
            $message .= "<h1>This is headline.</h1>";
            
            $header = "From:abc@somedomain.com \r\n";
            $header .= "Cc:afgh@somedomain.com \r\n";
            $header .= "MIME-Version: 1.0\r\n";
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