<html>
    <head>
        <style>
            table.buttons
		 {
                	position: relative;
                	left: 250px;
                	top: 10px
		}
		 body{
		 background:#7bd19c;
		 font-family: serif;
		}
		.cool{
		clear:both;
		border: 5px solid black;
		background: #f5f4d7;
		padding: 20px;
		max-width: 1060px;
		margin: 2px auto;
		overflow: auto;
		}
		.button {
		background-color: #7bd19c;
        	border: 2px solid black;
        	color: black;
		padding: 5px 14px;
		text-align: center;
		text-decoration: none;
		display: inline-block;
		font-size: 14px;
        margin: 4px 2px;
        cursor: pointer;
	}
        </style>

        <h1>Customer Quote Selection</h1>
    <br/><br/>
    <!--Links go here for everything else-->
    </head>

    <body bgcolor = "#00FA9A">
    <center>
    <!--Quote entry form-->
    <?php
    include 'CustomerCreateQuote.php';
    defaultDisplay();

function defaultDisplay(){
    $link = dbConnect();

    echo "<table border = 0.8>";
        echo "<thead>";
            echo "<th></th>";
	    echo "<th>ID</th>";
	    echo "<th>Price</th>";
	    echo "<th>Discount</th>";
	    echo "<th>Line Item</th>";
	    echo "<th>Secret Note</th>";
            echo "<th>Customer</th>";
	echo "</thead>";
    
  $custLink = mysqli_connect('blitz.cs.niu.edu', 'student', 'student', 'csci467', '3306');

  
  $sql = "Select * from Quote;";
  
 	if ($result = mysqli_query($link, $sql))
    	{
	        $rowNum = mysqli_num_rows($result);

        	for ($i = 0; $i < $rowNum; $i++)
		{
           		$array = mysqli_fetch_array($result);
            		echo "<form method = 'post' action='CustomerQuotesEdit.php' id = 'editForm'>";
            		echo "<tr>";
	        	echo "<td><input type = 'radio' name = 'select' value = ", ($array[0]),"></td>";
                	echo "<td>",($array[0]),"</td>";
                	echo "<td>",($array[1]),"</td>";
                	echo "<td>",($array[2]),"</td>";
                	echo "<td>",($array[3]),"</td>";
                	echo "<td>",($array[4]),"</td>";
                
                	if ($array[6] != '')
			{
               			$custSQL = "Select name from customers where id = $array[6];";
               			$custResult = mysqli_query($custLink, $custSQL);
                
               			$custArray = mysqli_fetch_array($custResult);
                		echo "<td>", ($custArray[0]), "</td>";
                	}
                	else
			{
                		echo "<td>N/A</td>";
                	}

            		echo "</tr>";
        }

  	 mysqli_close($custLink);
   	}
	 
    else
    {
        echo "Failed to execute: " . mysqli_error($link);
    }

    echo "</table>";
    echo "<table class = 'buttons' >";
            echo "<tr>";
                echo "<td>";
                    echo "<input type = 'submit' name = 'editQuote' value = 'Edit Quote' >";
                echo "</form>";
                echo "</td>";
                echo "<td>";
                echo "</br>";
                echo "<form  method = 'post' action='CustomerQuotesCreate.php'>";
                    echo "<input type = 'submit' name = 'createNew' value = 'Create New'>";
                echo "</form>";
                echo "</td>";
            echo "</tr>";
    echo "</table>";
    mysqli_close($link);
}

    ?>
    </center>
    </body>
</html>
