<!-- update user data -->
<?php
	session_start();
	
	// initialize variables
	$Name = "";
	$User_ID = "";
	$Password = "";
	$Total_Commission = "";
	$City = "";
	$Street = "";
	$errors = array();

	// connect to database (servername, username, password, dbname)
	$db = new mysqli('localhost', 'root', '', 'quote_database');
	if ($db->connect_error){die("Connection failed: " . $db->connect_error);}
	
	// register associate data
	$Name = mysqli_real_escape_string($db, $_POST['Name']);
	$User_ID = mysqli_real_escape_string($db, $_POST['User_ID']);
	$Password = mysqli_real_escape_string($db, $_POST['Password']);
	$Total_Commission = mysqli_real_escape_string($db, $_POST['Total_Commission']);
	$City = mysqli_real_escape_string($db, $_POST['City']);
	$Street = mysqli_real_escape_string($db, $_POST['Street']);
	
	// delete associate, otherwise update associate data
	if(isset($_POST['Delete']) && $_POST['Delete'] == 'Yes')
	{
		$query = "DELETE FROM associate_info WHERE User_ID = $User_ID";
		mysqli_query($db,$query);
	}
	else
	{
		//if($Name != "")
		{
			$query = "UPDATE associate_info SET Name = $Name WHERE User_ID = $User_ID";
			mysqli_query($db,$query);
		}
		if(!empty($Password))
		{
			$query = "UPDATE associate_info SET Password = $Password WHERE User_ID = $User_ID";
			mysqli_query($db,$query);
		}
		if(!empty($Total_Commission))
		{
			$query = "UPDATE associate_info SET Total_Commission = $Total_Commission WHERE User_ID = $User_ID";
			mysqli_query($db,$query);
		}
		if(!empty($City))
		{
			$query = "UPDATE associate_info SET City = $Password WHERE City = $User_ID";
			mysqli_query($db,$query);
		}
		if(!empty($Street))
		{
			$query = "UPDATE associate_info SET Street = $Street WHERE User_ID = $User_ID";
			mysqli_query($db,$query);
		}
	}
/*?>

<!-- register new user -->
<?php
	session_start();*/
	
	// initialize variables
	$username = "";
	$password = "";
	$errors = array();

	// connect to database (servername, username, password, dbname)
	$db = new mysqli('localhost', 'root', '', 'quote_database');
	if ($db->connect_error){die("Connection failed: " . $db->connect_error);}
	
	// register users
	$username = mysqli_real_escape_string($db, $_POST['username']);
	$password = mysqli_real_escape_string($db, $_POST['password']);
	
	// form validation
	if(empty($username)){array_push($errors, "User ID is required");}
	if(empty($password)){array_push($errors, "Password is required");}

	//check db for existing user with same username
	$user_check_query = "SELECT * FROM associate_info WHERE User_ID = '$username'";

	$results = mysqli_query($db, $user_check_query);
	$user = mysqli_fetch_assoc($results);

	if($user)
	{
		if ($user['User_ID'] == $username)
		{
			array_push($errors, "User ID already exists");
		}
	}

	// register the user if no error
	if(count($errors) == 0)
	{
		$query = "INSERT INTO associate_info (User_ID, Password) VALUES ('$username', '$password')";
		mysqli_query($db,$query);
	}
?>

<!DOCTYPE html>
<html>
<body>
<!-- links to other pages -->
<div>
	<a href="quoteview.php">Quotes</a>
</div>
<!-- Display Sales Associates-->
<div>
	<b>Our Sales Associates:</b>
</div>
<div>
<?php	
	// get associates from database
	$sql = "SELECT * FROM associate_info";
	$result = $db->query($sql);
	
	// create a table
	echo "<table><tr><td>Name</td><td>User ID</td><td>Password</td><td>Total Commission</td><td>City</td><td>Street</td></tr>";
	// go through each associate
	if($result->num_rows > 0 )
	{
		// output data for each associate
		while($row = $result->fetch_assoc())
		{
			echo "<tr><td>" . $row['Name'] . "</td><td>" . $row['User_ID'] . "</td><td>" . $row['Password'] . "</td><td>"
				. $row['Total_Commission'] . "</td><td>" . $row['City'] . "</td><td>" . $row['Street'] . "</td></tr>";
		}
	}
	echo "</table>";
	
	// display number of associates found
	echo $result->num_rows . " associates found";
?>
</div>
<!-- form to edit associate -->
<div>
	<!-- form to edit data -->
	<form action="adminpanel.php" method="post">
		Name: <input type="text" name="Name">
		User ID: <input type="text" name="User ID" required>
		Password: <input type="text" name="Password">
		Total Commission: <input type="text" name="Total_Commission">
		City: <input type="text" name="City">
		Street: <input type="text" name="Street">
		Delete: <input type="checkbox" name="Delete" value="Yes">
		<button type="submit" name="edit_user">Edit Associate</button>
	</form>
</div>
<!-- form to add associate -->
<div>
	<form action="adminpanel.php" method="post">
		User ID: <input type="text" name="username" required>
		Password: <input type="text" name="password" required>
		<button type="submit" name="register_user">Add Associate</button>
	</form>
</div>
</body>
</html>
