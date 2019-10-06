<?php


session_start();
 


$con = mysqli_connect('localhost','root');

mysqli_select_db($con, 'userregistration');

$username = $password = "";
$username_err = $password_err = "";
 
// Define variables and initialize with empty values

 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username1"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username1"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password1"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password1"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT user_id, user_username, user_password FROM users WHERE user_username = ? AND user_password = ?";
        
        if($stmt = mysqli_prepare($con, $sql)){
            // Bind variables to the prepared statement as parameters

            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = $password;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $password);
                    if(mysqli_stmt_fetch($stmt)){
                        // if(password_verify($password, $password)){
                    		
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["user_id"] = $id;
                            $_SESSION["user_username"] = $username; 

                            
                            // Redirect user to welcome page
                            header("location: calculator.php");
                        // }
                        
                    }
                } 

                else{
                    // Display an error message if username doesn't exist
                    $password_err = "Incorrect Login Details.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($con);
}




// }

?>

<!DOCTYPE html>
<html>

<head lang="en">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/signup.css">
	<link href="https://fonts.googleapis.com/css?family=Quicksand&display=swap" rel="stylesheet">
	<title>Login & Signup Page</title>


</head>

<body>

	<nav class="navbar navbar-expand-md text-white navbar-light">
		<div class="container">
			<a class="navbar-brand" href="index.html"><img src="css/img/nav.png" id='nav-logo' alt="Brand"></a>
			<button class="navbar-toggler bg-white" type="button" data-toggle="collapse" data-target="#navbarResponsive">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse text-white" id="navbarResponsive">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item">
						<a class="nav-link" href="index.php">HOME</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="signup.php">SIGN UP</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="calculator.php">CALCULATOR</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="about.php">ABOUT</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>



	<div id="container">


		<div id="form-container">


			<!-- <div class="header-links">
				<a href="#">What?</a>
			</div> -->

			<div class="img-form">
			   <img class="img" src="css/img/dollar-resized3.jpg"/>
			   
				<form id="log-in" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"  method="post" name="uform">
					<div id="username1_div">
						<input type="text" name="username1" placeholder=" Username" value="<?php echo $username; ?>" >
						 <div id="username1_error" style="color:red;"><?php echo $username_err; ?></div>
					</div>

					<div id="password1_div">
						<input type="password" name="password1" placeholder=" Password">
						 <div id="pass1_error" style="color:red;"><?php echo $password_err; ?></div>

					</div>

					<input id="login" type="Submit"  value="Sign in">

					<div id="login-alert">
						<p>Don't have an account?</p>
						<button type="button" onclick="window.location.href='signup.php'" value="signup">Sign up</button>
					</div>
				</form>
			</div>

			<!-- <form id="register" action="registration.php" method="post" onsubmit="return Validate1()" name="vform">
				<div id="firstname_div">
					<input type="text" name="firstname" placeholder=" Firstname">
					<div id="fname_error"></div>
				</div>

				<div id="lastname_div">
					<input type="text" name="lastname" placeholder=" Lastname">
					 <div id="lname_error"></div>
				</div>

				<div id="username_div">
					<input type="text" name="username" placeholder=" Username">
					 <div id="username_error"></div>
				</div>

				<div id="email_div">
					<input type="email" name="email" placeholder=" Email address">
					 <div id="email_error"></div>
				</div>

				<div id="password_div">
					<input type="password" name="password" placeholder=" Password">
					<div id="pass_error"></div>
				</div>


				<div id="pass_confirm_div">
					<input type="password" name="password_confirm" placeholder="Confirm Password">
					<div id="password_error"></div>
				</div>
				
				

				<input id="reg" type="Submit"  value="Sign up">

				<div id="register-alert">
					<p>Have an account?</p>
					<button type="button" onclick="hideRegister()" value="signin">Sign in</button>
				</div>
			</form> -->

		</div>

	</div>



	<!-- <div class="container">
		<div class="row">
			<div class="col-md-4"></div>
			<div class="col-md-7">

				<img src="css/img/resized.jpg" class='img-fluid mt-3'>
				<form id="log-in" action="login.php" method="post">
					<input type="text" name="username" placeholder=" Username" required>
					<input type="password" name="password" placeholder=" Password" required>

					<input id="login" type="Submit" value="Sign in">

					<div id="login-alert">
						<p>Don't have an account?</p>
						<button type="button" onclick="showRegister()" value="signup">Sign up</button>
					</div>
				</form>

				<form id="register" action="signup.php" method="post">
					<input type="text" name="firstname" placeholder=" Firstname" required>
					<input type="text" name="lastname" placeholder=" Lastname" required>
					<input type="text" name="username" placeholder=" Username" required>
					<input type="email" name="email" placeholder=" Email address" required>
					<input type="password" name="password" placeholder=" Password" required>

					<input id="reg" type="Submit" value="Sign up">

					<div id="register-alert">
						<p>Have an account?</p>
						<button type="button" onclick="hideRegister()" value="signin">Sign in</button>
					</div>
				</form>

			</div>
			<div class="col-md-1"></div>

		</div>
	</div> -->


	<footer>
        <div class="container footer-height">
            <div class="row">
                <div class="col-md-5">
                    <h3>About Us</h3>
                    <p>Charites Finance is a start up that aims to create a stable financial life for its users. We are
                        interested in helping you have a knowledge of your current financial status and assist you in
                        maximizing your money for a secure future.</p>
                </div>
                <div class="col-md-4">
                    <h3>About the App</h3>
                    <p>This free calculator helps you get a view of your financial positon by adding the values of your
                        assets and substracting your liabilites.</p>
                </div>
                <div class="col-md-3">
                    <h3>Contact Us</h3>
                    <address style="margin-bottom:30px;">
                        Team Charites <br>
                        3, Remote House,<br>
                        HNG Avenue, Nigeria <br>
                        +234-1111-0000 <br>
                        info@charitesfinance.com
                    </address>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                <p style='text-align: center' class='animated rollIn'>
                    CharitesFinance.com &copy;  2019
                </p>
            </div>
            </div>
        </div>
        </footer>

 <!--        <script>
	
        	// SELECTING ALL TEXT ELEMENTS

				var username1 = document.forms['uform']['username1'];
				var password1 = document.forms['uform']['password1'];



				var firstname = document.forms['vform']['firstname'];
				var lastname = document.forms['vform']['lastname'];
				var username = document.forms['vform']['username'];
				var email = document.forms['vform']['email'];
				var password = document.forms['vform']['password'];
				var password_confirm =document.forms['vform']['password_confirm'];

				//  password between 6 to 20 characters which contain at least one numeric digit, one uppercase and one lower case character
				var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/;




				// SELECTING ALL ERROR DISPLAY ELEMENTS

				var username1_error = document.getElementById('username1_error');
				var pass1_error=  document.getElementById('pass1_error');


				var fname_error = document.getElementById('fname_error');
				var lname_error = document.getElementById('lname_error');
				var username_error = document.getElementById('username_error');
				var email_error = document.getElementById('email_error');
				var pass_error=  document.getElementById('pass_error');
				var password_error = document.getElementById('password_error');


				

				function Validate2(){

					if (username1.value == "") {
				    username1.style.border = "1px solid red";
				    document.getElementById('username1_div').style.color = "red";
				    username1_error.textContent = "Your username is required";
				    username1.focus();
				    return false;
				  } else{
				  		username1.style.border = "1px solid #5e6e66";
					  	document.getElementById('username1_div').style.color = "green";
					  	username1_error.innerHTML = "";
				  }



				  if (password1.value == "") {
				    password1.style.border = "1px solid red";
				    document.getElementById('password1_div').style.color = "red";
				    // password_confirm.style.border = "1px solid red";
				    pass1_error.textContent = "Password is required";
				    password1.focus();
				    return false;
				  }else{
				  	password1.style.border = "1px solid #5e6e66";
				  	document.getElementById('password1_div').style.color = "green";
				  	pass1_error.innerHTML = "";
				  	return true;
				  }

				

			}


				// validation function
				function Validate1() {
					


				  // validate username
				  if (firstname.value == "") {
				    firstname.style.border = "1px solid red";
				    document.getElementById('firstname_div').style.color = "red";
				    fname_error.textContent = "Your First name is required";
				    firstname.focus();
				    return false;
				  } else{
				  	firstname.style.border = "1px solid #5e6e66";
				  	document.getElementById('firstname_div').style.color = "green";
				  	fname_error.innerHTML = "";
				  	// return true;
				  }

				  // validate username
				  if (firstname.value.length < 3) {
				    firstname.style.border = "1px solid red";
				    document.getElementById('firstname_div').style.color = "red";
				    fname_error.textContent = "Your name must be at least 3 characters";
				    firstname.focus();
				    return false;
				  }else{
				  	firstname.style.border = "1px solid #5e6e66";
				  	document.getElementById('firstname_div').style.color = "green";
				  	fname_error.innerHTML = "";
				  	// return true;
				  }

				  


				  if (lastname.value == "") {
				    lastname.style.border = "1px solid red";
				    document.getElementById('lastname_div').style.color = "red";
				    lname_error.textContent = "Your Last name is required";
				    lastname.focus();
				    return false;
				  }else{
				  	lastname.style.border = "1px solid #5e6e66";
				  	document.getElementById('lastname_div').style.color = "green";
				  	lname_error.innerHTML = "";
				  	// return true;
				  }
				  

				  // validate username
				  if (lastname.value.length < 3) {
				    lastname.style.border = "1px solid red";
				    document.getElementById('lastname_div').style.color = "red";
				    lname_error.textContent = "Your name must be at least 3 characters";
				    lastname.focus();
				    return false;
				  }else{
				  	lastname.style.border = "1px solid #5e6e66";
				  	document.getElementById('lastname_div').style.color = "green";
				  	lname_error.innerHTML = "";
				  	// return true;
				  }

				  if (username.value == "") {
				    username.style.border = "1px solid red";
				    document.getElementById('username_div').style.color = "red";
				    username_error.textContent = "Your username is required";
				    username.focus();
				    return false;
				  }else{
				  	username.style.border = "1px solid #5e6e66";
				  	document.getElementById('username_div').style.color = "green";
				  	username_error.innerHTML = "";
				  	// return true;
				  }

				  // validate email
				  if (email.value == "") {
				    email.style.border = "1px solid red";
				    document.getElementById('email_div').style.color = "red";
				    email_error.textContent = "Email is required";
				    email.focus();
				    return false;
				  }else{
				  	email.style.border = "1px solid #5e6e66";
				  	document.getElementById('email_div').style.color = "green";
				  	email_error.innerHTML = "";
				  	// return true;
				  }

				
				  // validate password
				  if (password.value == "") {
				    password.style.border = "1px solid red";
				    document.getElementById('password_div').style.color = "red";
				    // password_confirm.style.border = "1px solid red";
				    pass_error.textContent = "Password is required";
				    password.focus();
				    return false;
				  }else{
				  	password.style.border = "1px solid #5e6e66";
				  	document.getElementById('password_div').style.color = "green";
				  	pass_error.innerHTML = "";
				  	// return true;
				  }

				  if(!password.value.match(passw))
				  {
				  	password.style.border = "1px solid red";
				    document.getElementById('password_div').style.color = "red";
				    // password_confirm.style.border = "1px solid red";
				    pass_error.textContent = "Your password must be between 6 to 20 chacters. It should contain at least one numeric digit, one uppercase and one lower case letter";
				    password.focus();
				    return false;
				  } else if(password.value.match(passw)){
				  	password.style.border = "1px solid #5e6e66";
				  	document.getElementById('password_div').style.color = "green";
				  	pass_error.innerHTML = "";
				  }


				  
				  // check if the two passwords match
				  if (password.value != password_confirm.value) {
				  	 password.style.border = "1px solid red";
				    document.getElementById('pass_confirm_div').style.color = "red";
				    // password_confirm.style.border = "1px solid red";
				    password_error.innerHTML = "The two passwords do not match";

				    return false;
				  }else{
				  	password.style.border = "1px solid #5e6e66";
				  	document.getElementById('pass_confirm_div').style.color = "green";
				  	password_error.innerHTML = "";
				  }

				}




		</script> -->

    <!-- <script type="text/javascript" src="js/script.js"></script> -->
	<script type="text/javascript" src="js/signup.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/popper.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
</body>

</html>

