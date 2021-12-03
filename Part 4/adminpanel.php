<!-- update user data -->
<?php
	session_start();
	
	// initialize variables
	$UPDATE_User_ID = $_SESSION['UPDATE_User_ID'];
	$Name = "";
	$User_ID = "";
	$Password = "";
	$Total_Commission = "";
	$City = "";
	$Street = "";
	$errors = array();

	// connect to database (servername, username, password, dbname)
	$db = new mysqli_connect('localhost', 'root', '', 'quote_database');
	if ($db->connect_error){die("Connection failed: " . $db->connect_error);}
	
	// register associate data
	$Name = mysqli_real_escape_string($db, $_POST['Name']);
	$User_ID = mysqli_real_escape_string($db, $_POST['User_ID']);
	$Password = mysqli_real_escape_string($db, $_POST['Password']);
	$Total_Commission = mysqli_real_escape_string($db, $_POST['Total_Commission']);
	$City = mysqli_real_escape_string($db, $_POST['City']);
	$Street = mysqli_real_escape_string($db, $_POST['Street']);
	
	// update associates
	if(!empty($Name))
	{
		$query = "UPDATE associate_info SET Name = '$Name' WHERE User_ID = 'UPDATE_User_ID' LIMIT 1";
		mysqli_query($db,$query);
	}
	if(!empty($User_ID))
	{
		// check db for existing associate with same User ID
		$user_check_query = "SELECT * FROM associate_info WHERE User_ID = '$User_ID' LIMIT 1";
		$results = mysqli_query($db, $user_check_query);
		$user = mysqli_fetch_assoc($results);
		
		if($user)
		{
			// update if no match found
			if ($user['User_ID'] == $User_ID)
			{
				array_push($errors, "User ID already exists");
			}
			else
			{
				$query = "UPDATE associate_info SET User_ID = '$User_ID' WHERE User_ID = 'UPDATE_User_ID' LIMIT 1";
				mysqli_query($db,$query);
			}
		}
	}
	if(!empty($Password))
	{
		$query = "UPDATE associate_info SET Password = '$Password' WHERE User_ID = 'UPDATE_User_ID' LIMIT 1";
		mysqli_query($db,$query);
	}
	if(!empty($Total_Commission))
	{
		$query = "UPDATE associate_info SET Total_Commission = '$Total_Commission' WHERE User_ID = 'UPDATE_User_ID' LIMIT 1";
		mysqli_query($db,$query);
	}
	if(!empty($City))
	{
		$query = "UPDATE associate_info SET City = '$Password' WHERE City = 'UPDATE_User_ID' LIMIT 1";
		mysqli_query($db,$query);
	}
	if(!empty($Street))
	{
		$query = "UPDATE associate_info SET Street = '$Street' WHERE User_ID = 'UPDATE_User_ID' LIMIT 1";
		mysqli_query($db,$query);
	}
?>

<!-- register new user -->
<?php
	session_start();
	
	// initialize variables
	$username = "";
	$password = "";
	$errors = array();

	// connect to database (servername, username, password, dbname)
	$db = new mysqli_connect('localhost', 'root', '', 'quote_database');
	if ($db->connect_error){die("Connection failed: " . $db->connect_error);}
	
	// register users
	$username = mysqli_real_escape_string($db, $_POST['username']);
	$password = mysqli_real_escape_string($db, $_POST['password']);
	
	// form validation
	if(empty($username)){array_push($errors, "Username is required");}
	if(empty($password)){array_push($errors, "Password is required");}

	//check db for existing user with same username
	$user_check_query = "SELECT * FROM associate_info WHERE User_ID = '$username' LIMIT 1";

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
<a href="associates.php">Associates</a>
<a href="quotes.php">Quotes</a>

<!-- Display Sales Associates-->
<b>Our Sales Associates</b>
<?php	
	// get associates from database
	$sql = "SELECT Name, User_ID, Total_Commission FROM associate_info";
	$result = $db->query($sql);
	
	// go through each associate
	if($result->num_rows > 0 )
	{
		while($row = $result->fetch_assoc())
		{
			$_SESSION['UPDATE_User_ID'] = $row["User_ID"];
			// output data for each associate
			echo "Name: " . $row["Name"] . " - User ID: " . $row["User_ID"] . " - Total Commission: " . $row["Total_Commission"] . " - City: " . $row["City"] . " - Street: " . $row["Street"] . "<br>";
			
			// form for each associate
			?>
				<div>
					<!-- form to edit data -->
					<form action="adminpanel.php" method="post">
						Name: <input type="text" name="Name">
						User ID: <input type="text" name="User ID">
						Password: <input type="text" name="Password">
						Total Commission: <input type="text" name="Total_Commission">
						City: <input type="text" name="City">
						Street: <input type="text" name="Street">
						<button type="submit" name="edit_user">Edit</button>
					</form>
					<!-- button to delete associate -->
					<form action="adminpanel.php" method="post">
						<button type="submit" name="delete_user">Delete</button>
					</form>
				</div>
			<?php
		}
	}
	
	// display number of associates found
	echo $result->num_rows . "associates found";
?>

<!-- form to add new associate -->
<form action="adminpanel.php" method="post">
	User ID: <input type="text" name="username" required>
	Password: <input type="text" name="password" required>
	<button type="submit" name="register_user">Add new associate</button>
</form>
</body>
</html>