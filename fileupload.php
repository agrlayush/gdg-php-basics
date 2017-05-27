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
$file_path = false;
if(isset($_POST['upload'])){
	{
		if(isset($_FILES['file'])){
			//process file upload
			$name = $_FILES["file"]["name"];
			$ext = end((explode(".", $name)));
			$target_dir = "uploads/";
			$target_name = $_SESSION['name'] . time() . '.' . $ext;
			//check in file is an image
			$check = getimagesize($_FILES["file"]["tmp_name"]);
			if(false !== $check){
				if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir . $target_name)) {
					
					//update database
					$target_name = mysqli_real_escape_string($db, $target_name);
					$id = mysqli_real_escape_string($db, $_SESSION['user_id']);
					$query = "UPDATE users SET avatar='$target_name' 
					WHERE id='$id'";
					
					if($db->query($query)){
						$messages[] = "The file has been uploaded.";
						$file_path = $target_dir . $target_name;
					}else{
						$errors[] = "Couldn't set the profile picture";
					}
				}else{
					$errors[] = "Something is wrong at our end";
				}
			}else{
				$errors[] = "File is not an image";
			}
		}else{
			$errors[] = "File not received";
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
          <a class="navbar-brand" href="index.php">Web Application Basics - GDG</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
			<div class="navbar-right">
			<?php
				if(isset($_SESSION['loggedIn'])){
					echo "<a href='index.php?logout' class='btn btn-danger'>Logout</a>";
				}else{
					echo "<a href='index.php' class='btn btn-success'>Sign in</a>";
				}
				?>
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
	  <?php
		if($file_path){
			echo "<img src='$file_path' width='400px' />";
		}
	  ?>
        <div class="col-md-4 col-md-offset-4">
		<?php
			if(!isset($_SESSION['loggedIn'])){
				echo "<h2>Please <a href='index.php' >Sign in</a> to upload file.</h2>";
			}else{
				//how html can be displayed within a php control block 
		?>
		<h2>Upload your Image</h2>
		<small>and the world will see who you are. </small>
		<form class="navbar-form navbar-right" id="registration-form" method="post" action="" enctype="multipart/form-data">
            <div class="form-group">
              <input id="file" type="file" class="form-control" name="file"/>
            </div>
			<div class="form-group">
            <input type="submit" id="upload" name="upload" class="btn btn-success" value="Upload" />
			</div>
		</form>
		<?php
		
			}
		?>
		
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
  </body>
</html>
