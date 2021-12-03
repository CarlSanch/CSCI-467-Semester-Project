<html>
    <head>
        <h1>Revise/Edit your Quote</h1>
        <a href='CustomerLandingPage.php'>Return to Landing Page</a>
    </head>
    <body bgcolor =  #00FA9A>
        <?php
        include("CustomerCreateQuote.php");
	include("QuoteCreator.php");

	if ($_POST['Update'] == "Update")
	{
            runQUpdate();
	}
	else
       	{
            editQuote();
        }


function editQuote()
{
    $link = dbConnect();
    
    $quoteID = $_POST['select'];
    $sql = "select * from Quote where QID = $quoteID;";
    $result = mysqli_query($link, $sql);

    $row = mysqli_fetch_array($result);

    echo "<form method = 'post' action = 'CustomerQuotesEdit.php'>";
    echo "<input type = 'hidden' name = 'quoteID' value = $quoteID>";
    
    	echo "<table>";
        echo "<tr>";
            echo "<td>Line Items:</td>";
            echo "<td>", ($row[3]), "</td>";
            echo "<td><textarea row='4' cols = '22' name = 'lineItemTxt'></textarea></td>";
	echo"</tr>";
	
	echo "<tr>";
            echo "<td>Secrect Note:</td>";
            echo "<td>",($row[4]),"</td>";
            echo "<td><input type = 'text' name = 'Note'></td>";
	echo "</tr>";

        echo "<tr>";
            echo "<td>Price: </td>";
            echo "<td>",($row[1]),"</td>";
            echo "<td><input type = 'text' name = 'Price'></td>";
        echo "</tr>";
	   
	 echo "<tr>";
            echo "<td>Discounts: </td>";
            echo "<td>", ($row[2]), "</td>";
            echo "<td><input type = 'text' name = 'Discounts'></td>";
	 echo "</tr>";
	 echo "</table>";

    echo "<table>";
        echo "<tr>";
            echo "<td><input type = 'submit' name = 'Update' value = 'Update'></td>";
        echo "</tr>";
    echo "</table>";
    echo "</form>";
}

        ?>
    </body>
</html>
