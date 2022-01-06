<?php
// Initialize the session
session_start();
 

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 
// Include database
require_once "database.php";
 
// Define variables 
$username = $password = "";
$username_err = $password_err = $login_err = "";
 


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    

    if(empty($username_err) && empty($password_err)){

        // Prepare a select statement
        
        $sql = "SELECT ID, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($con, $sql)){
           
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            //execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                

                mysqli_stmt_store_result($stmt);
                
                // Check username and verify password

                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);

                    if(mysqli_stmt_fetch($stmt)){
                    
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: index.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } 
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($con);
}
?>

<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title> LOG IN</title>

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
				<a href="signup.php" class="btn btn-dark" type="submit">Create account</a>
			</form>
		</div>
	</nav>

	<div class="container my-4">
		<div class="card mx-auto" style="width: 20rem;"><br>

			<img class="card-img-top mx-auto" src="https://icon-library.com/images/admin-login-icon/admin-login-icon-15.jpg" style="width: 60%; " alt="Card image cap">


			<div class="card-body">
				<h2 style="text-align: center;">Log In form</h2>
				<hr>
			
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" >
		
		
		<div class="form-group <?php echo (!empty($username_err))? 'has-error':''; ?>">

			<label> <font size="4px"> Username</label>
			<input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
			<span class="block"> <?php echo $username_err; ?>  </span>
			
		</div>

		<div class="form-group <?php echo (!empty($password_err))? 'has-error':''; ?>">

			<label>Password</label>
			<input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
			<span class="block"> <?php echo $password_err; ?>  </span>
			
		</div>

		<div class="form-group">
			<input type="submit" name="" class="btn btn-primary" value="Log In">

			<input type="reset" name="" class="btn btn-danger" value="Reset">
		</div>


	</form>
	
	</div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  


	</body>
	</html>		