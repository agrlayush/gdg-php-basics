<?php
require_once('config.php');

//start a session as soon as page load // not a good practice, 
// start a session/keep it active only if contents in the page really needs it
if(!isset($_SESSION)){
	session_start();
}


$errors = [];
$messages = [];
//check if Login form has been submitted
//user authentication should be done in a separate file which can be included in multiple pages
if(isset($_POST['sign-in']) || (isset($_GET['action']) && 'sign-in' == $_GET['action'])){
	//var_dump($_POST);
	$email = $_POST['email']?$_POST['email']:"";
	$password = $_POST['password']?$_POST['password']:"";
	//validate input, in this case, not empty
	if(empty($email) || empty($password)){
		$errors[] = "Email or Password cannot be blank";
	}else{
		//search the database if user exists for the entered data
		$email = mysqli_real_escape_string($db, $email);
		$password = mysqli_real_escape_string($db, $password);
		//sanitize input -- save from sql injections
		$query = "SELECT id, name FROM users WHERE email='$email' AND password = '$password'";
		
		$check = $db->query($query) or die();
		if($check->num_rows > 0 ){
			$data = $check->fetch_array();
			$name = $data['name'];
			$_SESSION['name'] = $name;
			$_SESSION['loggedIn'] = true;
			$_SESSION['email'] = $email;
			$_SESSION['user_id'] = $data['id'];
		}	
	}
}
///var_dump($_SESSION);
if(isset($_GET['logout'])){
	session_unset();
	//session_destroy();
	$messages[] = 'You are logged out';
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
		
		<?php if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true){
			?>
			<a href="?logout" class="navbar-right btn btn-danger">Logout</a>
			<?php
		}else{?>
			
			<form class="navbar-form navbar-right" id="login-form" method="post" action="">
            <div class="form-group">
              <input id="email" type="text" placeholder="Email" class="form-control" name="email">
            </div>
            <div class="form-group">
              <input id="password" type="password" placeholder="Password" class="form-control" name="password">
            </div>
            <button type="submit" id="sign-in" name="sign-in" class="btn btn-success">Sign in</button>
			<a href="register.php" class="btn btn-primary">Register</a>
          </form>
		<?php } ?>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>
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
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>Hello 
		<?php 
		if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']){
			echo $_SESSION['name'];
		}else{
			echo 'Developer';
		}
		?>,</h1>
        <p>Web Applications are awesome. It supports various devices. All they need are internet connection and web-browser. No computational load on the client and secure as HELL.</p>
        <p><a class="btn btn-primary btn-lg" href="fileupload.php" role="button">Upload a file</a></p>
	
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
		
          <h2>Anytime Access</h2>
          <p> Web apps are that much flexible which can be operated anytime & anywhere. A PC with an internet connection is enough to access the website</p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
        <div class="col-md-4">
          <h2>Supports Various Devices</h2>
          <p>A range of devices and their versions are available in the market. Web apps easily support all the devices connected with the internet. Operate the web apps simply in PDAs and Smart Phones. With the web applications development services, it has turned trouble free to send and receive information with the extended performance</p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
       </div>
        <div class="col-md-4">
          <h2>Customization</h2>
          <p>The web application developers share that it is easy to customize the web-based applications with compare to the desktop applications. Altering the look and feel of the web app is facile with the web apps. Smart developers can formulate the highly customized products with fewer efforts through web app development.</p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
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
	
	$('#sign-in').click(function(e){
		var email = document.getElementById('email').value;
		var password = document.getElementById('password').value;
		e.preventDefault();
		if('' == email.trim()){
			alert('Email Cannot be blank');
			return;
		}else if('' == password.trim()){
			alert('Password Cannot be blank');
			return;
		}else{
			var new_action = $("#login-form").attr('action') + '?action=sign-in';
			$("#login-form").attr('action', new_action)
			$('#login-form').submit();
		}
	});
	</script>
	
  </body>
</html>
