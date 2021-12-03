<?php
function runQUpdate(){
    $link = dbConnect();
    $lineItem = $_POST['lineItemTxt'];
    $Note = $_POST['Note'];
    $Price = $_POST['Price'];
    $Discount = $_POST['Discounts'];
    $custID = $_POST['custID'];
    $quoteID = $_POST['quoteID'];
    $updateSQL = "UPDATE Quote SET lineItem = '$lineItem', secretNote = '$Note', price = '$Price', discount = '$Discount' WHERE QID = $quoteID;";
    if (!$result = mysqli_query($link, $updateSQL))
    {
        echo "Error: " . mysqli_error($link);
    }
    
    mysqli_close($link);
}

function createNewQuoteControl($newQuoteArray)
{
    $link = dbConnect();
    $lineItem = $newQuoteArray[0];    
    $Note = $newQuoteArray[1];
    $Price = $newQuoteArray[2];
    $Discounts = $newQuoteArray[3];
    $Sanctioned = 0;

    if ($newQuoteArray[4] == "on")
    {
        $Sanctioned = 1;
    }

    $CustID = $newQuoteArray[5];

    $insertSQL = "insert into Quote (price, discount, lineItem, secretNote, sanctioned) values ('$Price', '$Discounts', '$lineItem', '$Note','$Sanctioned')";

    if ( mysqli_query($link, $insertSQL))
    {
        echo "Your Quote ID number is: " . mysqli_insert_id($link);
    }
    else
    {
       echo "Error:" . mysqli_error($link);
    }

    mysqli_close($link);
}
?>
