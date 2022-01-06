<?php
//database file
require_once "database.php";

//define variables

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

//process form data submission

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	//validate username
	if (empty(trim($_POST["username"]))) {
		$username_err="please enter a username";
	}

	elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
		$username_err = "username can only contain letters, numbers, and underscores.";
	}

	else{
		//prepare select statement
		$sql= "SELECT id FROM users WHERE username = ?";



		if ($stmt=mysqli_prepare($con,$sql)) {
			
			mysqli_stmt_bind_param($stmt,"s",$param_username);

			//set parameters
			$param_username= trim($_POST["username"]);

			//execute prepared statement

			if (mysqli_Stmt_execute($stmt)) {
				
				mysqli_Stmt_store_result($stmt);

				if (mysqli_stmt_num_rows($stmt) == 1){
					$username_err="This username already taken";
				}
				else{
					$username = trim($_POST["username"]);
				}
			}
			else{
				echo "Oops! somthing went wrong. please try again later.";
			}
		}

		//close statement
		mysqli_stmt_close($stmt);
	

	}

	//validate password
	if (empty(trim($_POST["password"]))) {
		$password_err = "please enter a password";
	}
	elseif (strlen(trim($_POST["password"])) <6) {
		$password_err = "password must have atleast 6 characters";
	}
	else{
		$password = trim($_POST["password"]);
	}

	//validate confirm password
	if (empty(trim($_POST["confirm_password"]))) {
		$confirm_password_err = "please confirm password";
	}
	else{
		$confirm_password = trim($_POST["confirm_password"]);

		if(empty($password_err) && ($password!=$confirm_password)){
			$confirm_password_err = "password did not match";
		}
	}

	//check input before inserting database

	if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
		
		//prepare insert statement
		$sql = "INSERT INTO users (username, password) VALUES (?,?)";

		if($stmt = mysqli_prepare($con,$sql)){

			mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

			//set parameters
			$param_username=$username;
			$param_password= password_hash ($password, PASSWORD_DEFAULT);

			//execute the prepared statement
			if (mysqli_Stmt_execute($stmt)) {
				
				header ("location: login.php");
			}
			else {
				echo "somthing went wrong. please try again later.";
			}
		}

		//close statement
		mysqli_stmt_close($stmt);
	}

	//close connection
	mysqli_close($con);


}



?>


<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>sign up</title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">


		<style type="text/css">
			body{
				font: 14px sans-serif;
			}
			.nav-item{
				display: inline-block;
			}
		</style>

	</head>
	<body>

		<center><font size="10px" face="travia">Coding School</font></center>


		<!--nav bar-->

		<nav class="navbar navbar-expand-lg navbar-dark bg-secondary" style="margin-top:20px;" >

			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>


		
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				
				<li class="nav-item active">
				 <a class="nav-link" href="#"><b> <font face="travia" size="5px">Home</font> </b></a>
				</li>

				<li class="nav-item active">
                  <a class="nav-link" href="#"><b> <font face="travia" size="5px">Frontend</font></b></a>
                </li>
      
                <li class="nav-item active">
                 <a class="nav-link" href="#"><b> <font face="travia" size="5px">Backend</font></b></a>
                </li>

			</ul>

			<form class="form-inline">
				<a href="login.php" class="btn btn-dark" type="submit">LogIn account</a>
			</form>
		</div>
	</nav>

	<div class="container my-4">
		<div class="card mx-auto" style="width: 20rem;"><br>
			<div class="card-body">
				<h2 style="text-align: center;">sign up form</h2>
				<hr>
			
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" >
		
		<div class="form-group <?php echo (!empty($username_err))? 'has-error':''; ?>">

			<label> <font size="4px">Username</label>
			<input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
			<span class="block"> <?php echo $username_err; ?>  </span>
			
		</div>

		<div class="form-group <?php echo (!empty($password_err))? 'has-error':''; ?>">

			<label>Password</label>
			<input type="text" name="password" class="form-control" value="<?php echo $password; ?>">
			<span class="block"> <?php echo $password_err; ?>  </span>
			
		</div>

		<div class="form-group <?php echo (!empty($confirm_password_err))? 'has-error':''; ?>">

			<label>Confirm Password</label>
			<input type="text" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
			<span class="block"> <?php echo $confirm_password_err; ?>  </span>
			
		</div>

		<div class="form-group">
			<input type="submit" name="" class="btn btn-primary" value="Submit">

			<input type="reset" name="" class="btn btn-success" value="Reset">
		</div>

		<p>Already have an account? <a href="login.php">Login Here</a> </p>


	</form>
	
	</div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

	</body>
	</html>		