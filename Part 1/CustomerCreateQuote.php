<html>
    <head>
        <h1>Quote Creation Form</h1>
        <a href = "CustomerLandingPage.php">Return to Landing Page</a>
        <style>
            div {
                border: 1px solid black;
                width: 450px;
                height: 450px;
                overflow: auto;
            }
        </style>
    </head>
    <body bgcolor = "#00FA9A">
        <?php
            include('CustomerReviseQuote.php');
            include('QuoteCreator.php');
            createNewQuote();
            $newQuote = array($_POST['lineItemTxt'], $_POST['Note'], $_POST['Price'], $_POST['Discounts'], $_POST['custID']);
            newQuoteCreation($newQuote);

function newQuoteCreation()
{
    if($custLink = mysqli_connect('blitz.cs.niu.edu', 'student', 'student', 'csci467', '3306')){

    $custSQL = "Select * from customers;";
    $custResult = mysqli_query($custLink, $custSQL);
    
    if (mysqli_connect_errno())
    {
        echo "Could not execute search. Error: " . mysqli_error($custLink);
    }
    else
    {
        echo "<form method = 'post' action = 'CustomerQuotesCreate.php'>";
    }

?>

</br>
        Select Customer:
<div>
        <table border = 1>
            <th></th>
            <th>ID</th>
            <th>Name</th>
            <th>City</th>
            <th>Street</th>
            <th>Contact</th>
<?php
        $custRows = mysqli_num_rows($custResult);
            echo "<tbody>";

    	for ($i = 0; $i < $custRows; $i++)
    	{
            $array = mysqli_fetch_array($custResult);
                echo "<tr>";
                    echo "<td><input type = 'radio' name = 'custID' value = ", ($array[0]), "</td>";
                    echo "<td>",($array[0]),"</td>";
                    echo "<td>",($array[1]),"</td>";
                    echo "<td>",($array[2]),"</td>";
                    echo "<td>",($array[3]),"</td>";
                    echo "<td>",($array[4]),"</td>";
                echo "</tr>";
        }
            echo "</tbody>";
?>
        </table>
</div>

<?php
        echo "<table>";
            echo "<tr>";
                echo "<td>Line Items: </td>";
                echo "<td><textarea row='4' cols = '22' name = 'lineItemTxt'></textarea></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td>Secret Note: </td>";
                echo "<td><input type = 'text' name = 'Note'></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td>Price: </td>";
                echo "<td><input type = 'text' name = 'Price'></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td>Discounts: </td>";
                echo "<td><input type = 'text' name = 'Discounts'></td>";
            echo "</tr>";
        echo "</table>";
        echo "<input type = 'submit' name = 'createNewSubmit' value = 'Submit'>";
    echo "</form>";
    }
}

elseif(mysqli_connect_errno())
{
    echo "Connection error:". mysqli_connect_error();
}
}

        ?>
    </body>
</html>
