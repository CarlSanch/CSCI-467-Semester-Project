<?php
	session_start();
	
	// initialize variables
	$Sort = 'Quote_ID';
	
	// connect to database (servername, username, password, dbname)
	$db = new mysqli('localhost', 'root', '', 'quote_database');
	if ($db->connect_error){die("Connection failed: " . $db->connect_error);}
	
	// get Sort variable
	$Sort = mysqli_real_escape_string($db, $_POST['Sort']);
?>

<!DOCTYPE html>
<html>
<body>
<!-- links to other pages -->
<a href="adminpanel.php">Associates</a>

<!-- drop down panel for quote sort -->
<form action="quoteview.php" method="post">
	<select name="Sort">
		<option value="Date_of_Quote">Date of Quote</option>
		<option value="Associate_Name">Associate Name</option>
		<option value="Sanctioned">Sanctioned</option>
		<option value="Ordered">Ordered</option>
		<option value="Process_Date">Process Date</option>
	</select>
	<button type="submit" name="sort_button">Sort</button>
</form>

<!-- Display Sales Associates-->
<b>List of all quotes:</b>
<?php	
	// get associates from database
	$sql = "SELECT * FROM quote_info ORDER BY $Sort ASC";
	$result = $db->query($sql);
		
	// create a table
	echo "<table><tr><td>Quote ID</td><td>Date of Quote</td><td>Associate Name</td><td>Company Name</td><td>Commission</td><td>Sanctioned</td><td>Ordered</td><td>Process Date</td></tr>";
	// output data for each quote
	while($row = $result->fetch_assoc())
	{
		echo "<tr><td>" . $row['Quote_ID'] . "</td><td>" . $row['Date_of_Quote'] . "</td><td>" . $row['Associate_Name'] . "</td><td>" . $row['Company_Name'] . "</td><td>$"
			. $row['Commission'] . "</td><td>" . $row['Sanctioned'] . "</td><td>" . $row['Ordered'] . "</td><td>" . $row['Process_Date']	. "</td></tr>";
	}
	echo "</table>";
?>
</body>
</html>
