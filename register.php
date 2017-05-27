<?php
//start a session as soon as page load // not a good practice, 
// start a session/keep it active only if contents in the page really needs it
// You can use isset($_SESSION) to check if it exists or needs starting
if(!isset($_SESSION)){
	session_start();
}

	//enter database connection credentials
//should be kept in a different file, named someting like config.php
$db = new mysqli('localhost', 'root', '', 'gdg1') or mysqli_connect_errno();

//if connection failed, show error, and stop loading the page
if($db->connect_error){
	die('Connect Error: ' . $db->connect_error);
}

$errors = [];
$messages = [];
//check if Login form has been submitted
//user authentication should be done in a separate file which can be included in the page
if(isset($_POST['register']) || (isset($_GET['action']) && 'register' == $_GET['action'])){
	$name = $_POST['name']?$_POST['name']:"";
	$email = $_POST['email']?$_POST['email']:"";
	$password = $_POST['password']?$_POST['password']:"";
	//validate input, in this case, not empty
	if(empty($email) || empty($password) || empty($name)){
		$errors[] = "Fields cannot be blank";
		//validate email
	}else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		//show email validation error
		$errors[] = "Email not valid";
		//similar validation can be done for password strength
	}else{
		//search the database if user already registered
		
		//sanitize input to save from sql injection
		$email = mysqli_real_escape_string($db, $email);
		$query = "	SELECT email 
					FROM users
					WHERE email = '" . $email ."'";
					
		$check = $db->query($query) or trigger_error($db->error);
		if($check->num_rows > 0){
			//email exists in our db, invoke error
			$errors[] = "Email already registered. Please try <a href='index.php'>Signing In</a>";
		}else{
			//insert user data into db
			$name = mysqli_real_escape_string($db, $name);
			//saving text password directly is bad idea, use hash('sha512', $password); instead
			$password = mysqli_real_escape_string($db, $password);
			
			//id and timestamp will auto generate
			$query = "INSERT INTO users (name, email, password) VALUES 
			('$name', '$email', '$password')";
			
			$db->query($query) or trigger_error($db->error);
			
			//you may send a validation email to user
			$messages[] = 'Registration successful. Login to continue';
		}
	}		
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Welcome to GDG, Bhubaneswar</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="jumbotron.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Web Application Basics - GDG</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
			<div class="navbar-right">
			<a href="index.php" class="btn btn-success">Sign in</a>
			</div>
			
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

    <div class="container">
      <!-- Example row of columns -->
	  <?php
	  //you can insert php codes in between html like this
	  if(!empty($errors)){
		  foreach($errors as $error){
			  //printing html from php
			  echo "<div class='alert alert-danger'>" . $error . "</div>";
		  }
	  }
	  if(!empty($messages)){
		  foreach($messages as $message){
			  //printing html from php
			  echo "<div class='alert alert-success'>" . $message . "</div>";
		  }
	  }
	  ?>
      <div class="row">
        <div class="col-md-4 col-md-offset-4">
		<h2>Register</h2>
		<small>and we will show you how awesome web programming can be.</small>
		<form class="navbar-form navbar-right" id="registration-form" method="post" action="">
			<div class="form-group">
              <input id="name" type="text" placeholder="Name" class="form-control" name="name">
            </div>
            <div class="form-group">
              <input id="email" type="text" placeholder="Email" class="form-control" name="email">
            </div>
            <div class="form-group">
              <input id="password" type="password" placeholder="Password" class="form-control" name="password">
            </div>
			<div class="form-group">
            <input type="submit" id="register" name="register" class="btn btn-success" value="Register" />
			</div>
		</form>
       </div>
      </div>

      <hr>

      <footer>
        <p>&copy; 2016 Company, Inc.</p>
      </footer>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
	<script>
	
	
	$('#register').click(function(e){
		e.preventDefault(); // to stop auto submission of form
		//different way to getting form data
		//plain javascript
		var email = document.getElementById('email').value;
		var password = document.getElementById('password').value;
		//jquery
		var name = $('#name').val();
		
		if('' == email.trim()){ //trim to drop all whitespaces
			alert('Email Cannot be blank');
			return;
		}else if('' == password.trim()){
			alert('Password Cannot be blank');
			return;
		}else if('' == name.trim()){
			alert('Name Cannot be blank');
			return;
		}else{
			//su\bmit button data wont get submitted with js
		
			var new_action = $("#registration-form").attr('action') + '?action=register';
			$("#registration-form").attr('action', new_action)
			$("#registration-form").submit();
		}
		
	});
	
	</script>
	
  </body>
</html>
