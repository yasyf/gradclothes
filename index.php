<?php
session_start();
require "credentials.php";
$con = mysql_connect("localhost",$databaseuser,$databasepass);

	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }
	mysql_select_db("yasyf_gradclothes", $con);
$tbl_name = "users";
if ($_POST['action'] == "login")
	{
	$myuser = $_POST['user'];
	$mypass = $_POST['pass'];
	$myuser = mysql_real_escape_string($myuser);
	$mypass = sha1($mypass);
	$sql = "SELECT id,email FROM $tbl_name WHERE username='$myuser' and password='$mypass'";
	$result = mysql_query($sql) or die(mysql_error());
	while ($row = mysql_fetch_assoc($result))
		{
		$id = $row['id'];
		$useremail = $row['email'];
		}

	if (isset($id) && !empty($id))
		{
		$_SESSION['id'] = $id;
		}
	  else
		{
		session_destroy();
		header("Location: http://gradclothes.yasyf.com/index.php?error=true&issue=credentials");
		exit();
		}
	if (isset($useremail) && !empty($useremail))
		{
		$_SESSION['useremail'] = $useremail;
		}
	if (isset($_REQUEST['next'])) {
				header("Location: http://gradclothes.yasyf.com/".$_REQUEST['next']);
			}else{
				header("Location: http://gradclothes.yasyf.com/index.php");
			}		
	} 
else if ($_REQUEST['action'] == "logout")
	{
	session_destroy();
	header("Location: http://gradclothes.yasyf.com/");
	exit();
	}
else if ($_POST['action'] == "register")
	{
	if(empty($_POST['user']) || $_POST['user'] == "" || $_POST['user'] == " "){
		session_destroy();
		header("Location: http://gradclothes.yasyf.com/index.php?register=true&error=true&issue=username");
		exit();
	}
	if(empty($_POST['pass']) || $_POST['pass'] == "" || $_POST['pass'] == " "){
		session_destroy();
		header("Location: http://gradclothes.yasyf.com/index.php?register=true&error=true&issue=password");
		exit();
	}	
	if(empty($_POST['email']) || $_POST['email'] == "" || $_POST['email'] == " " || strpos($_POST['email'],'@brentwood.bc.ca') == false){
		session_destroy();
		header("Location: http://gradclothes.yasyf.com/index.php?register=true&error=true&issue=email");
		exit();
	}
	$myuser = $_POST['user'];
	$mypass = $_POST['pass'];
	$myemail = $_POST['email'];
	$myuser = mysql_real_escape_string($myuser);
	$mypass = sha1($mypass);
	$myemail = mysql_real_escape_string($myemail);
	$sql = "SELECT id FROM $tbl_name WHERE username='$myuser'";
	$result = mysql_query($sql) or die(mysql_error());
	while ($row = mysql_fetch_assoc($result))
		{
		$badid = $row['id'];
		}

	if (isset($badid) && !empty($badid))
		{
		session_destroy();
		header("Location: http://gradclothes.yasyf.com/index.php?register=true&error=true&issue=username");
		exit();
		}

	$sql = "SELECT id FROM $tbl_name WHERE email='$myemail'";
	$result = mysql_query($sql) or die(mysql_error());
	while ($row = mysql_fetch_assoc($result))
		{
		$badid = $row['id'];
		}

	if (isset($badid) && !empty($badid))
		{
		session_destroy();
		header("Location: http://gradclothes.yasyf.com/index.php?register=true&error=true&issue=email");
		exit();
		}

	$sql = "INSERT INTO $tbl_name (username, password, email)VALUES('$myuser', '$mypass', '$myemail')";
	$result = mysql_query($sql) or die(mysql_error());
	if ($result)
		{
		$id = mysql_insert_id();
		$_SESSION['id'] = $id;
		$_SESSION['useremail'] = $myemail;
		header("Location: http://gradclothes.yasyf.com/");
		}
	}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Grad Clothes Order Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="//netdna.bootstrapcdn.com/bootswatch/2.3.1/cyborg/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
 <style type="text/css">
    .ui-dialog-titlebar-close {
	  visibility: hidden;
	}
  
	body {
        padding-top: 20px;
        padding-bottom: 40px;
      }

      /* Custom container */
      .container-narrow {
        margin: 0 auto;
        max-width: 700px;
      }
      .container-narrow > hr {
        margin: 30px 0;
      }

      /* Main marketing message and sign up button */
      .jumbotron {
        margin: 60px 0;
        text-align: center;
      }
      .jumbotron h1 {
        font-size: 72px;
        line-height: 1;
      }
      .jumbotron .btn {
        font-size: 21px;
        padding: 14px 24px;
      }

      /* Supporting marketing content */
      .marketing {
        margin: 60px 0;
      }
      .marketing p + h4 {
        margin-top: 28px;
      }
    </style>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
  </head>
  <body>
    <div class="container-narrow">

      <div class="masthead">
        <ul class="nav nav-pills pull-right">
<?php     
 if (!isset($_SESSION['id'])) {
	?>
	<li class="active"><a href="http://gradclothes.yasyf.com">Login</a></li>	<?php
	}    
 else if (isset($_SESSION['id'])) {
	?>
	<li class="active"><a href="http://gradclothes.yasyf.com">Order</a></li>
	<li><a href="http://gradclothes.yasyf.com/manage.php">Manage</a></li>  
	<li><a href="http://gradclothes.yasyf.com/vote.php">Vote</a</li> 
	<li><a href="http://gradclothes.yasyf.com/index.php?action=logout">Logout</a></li>	<?php
	}
	?>
        </ul>
        <h3 class="muted">Grad Clothes Order Form</h3>
      </div>

      <hr>

      <div class="jumbotron">
		<?php
	 	if (isset($_SESSION['id'])) {
		if($_SESSION['id'] == "1"){
				$result = mysql_query("SELECT * FROM `users`") or die(mysql_error());
				$usersnumb = mysql_num_rows($result);
				$result = mysql_query("SELECT * FROM `orders`") or die(mysql_error());
				$ordersnumb = mysql_num_rows($result);
				$result = mysql_query("SELECT * FROM `votes`") or die(mysql_error());
				$votesnumb = mysql_num_rows($result) / 3
			?>
		<h1 id='bigmessage'>Order Admin</h1>
        <p class="lead" id='smallmessage'><?php printf("%s Users, %s Orders, %s Votes",$usersnumb,$ordersnumb,$votesnumb); ?></p>
        <a class="btn btn-large btn-success" href="links.php">Generate YouTube Links</a>
        <a class="btn btn-large btn-primary" href="results.php">Results</a>
        <a class="btn btn-large btn-warning" href="orders.php">All Orders</a>
        <a class="btn btn-large btn-danger" href="check.php?filter=true">Bad People</a>
			<?php
		}
		else {
			if ($_REQUEST['action'] == "success")
			{
		?>
		<h1 id='bigmessage'>Order Success!</h1>
        <p class="lead" id='smallmessage'>Your Confirmation Has Been Emailed To You</p>
        <a class="btn btn-large btn-success" id="gobtn" href="#">Order Again</a>
		<?php
			} 
		else {
			?>
		<h1 id='bigmessage'>Welcome</h1>
        <p class="lead" id='smallmessage'>Click This Button To Order</p>
        <a class="btn btn-large btn-success" id="gobtn" href="#">Order</a>
		<?php
		 }
		}
	}
		else if ($_GET['register'] == "true")
		{
		?>
		<h1>Register</h1>
		<?php
		if ($_GET['error'] == "true"){
		?>
		<p style="color:red">There was an error with your registration. 
		<?php
		if (!empty($_GET['issue'])){
		?>
		That <?php echo $_GET['issue']; ?> has already been registered or is not valid.</p>
		<?php
		}
		else {
			?>
			</p>
			<?php
		}
		}
		?>
		<form action="" method="POST" id="registerform">
		Username: <br /><input name="user" type="text" value="<?php echo $_POST['user']; ?>" /><br />
		Password: <br /><input name="pass" type="password" value="<?php echo $_POST['pass']; ?>" /><br />
		Brentwood Email: <br /><input name="email" id="registeremail" type="text" /><br />
		<?php if(isset($_POST['user']) && isset($_POST['pass'])){
			?>
		<script>
		jQuery(function ($) {
		$('#registeremail').focus();
		  });
		</script>	
			<?php
		}
		?>
		<input name="action" type="hidden" value="register" />
		<button type="submit" value="register" class="btn btn-large btn-success">Register</button>
		<?php
		}
		else {
			if($_GET['issue'] == "credentials")
			{
			?>
			<p style="color:red">Your username or password was incorrect.</p>
			<?php
			}
			else if($_GET['error'] == "true")
			{
			?>
			<p style="color:red">You need to be logged in to do that.</p>
			<?php
			}
			?>	
			<p>Credentials are case-sensitive.</p>		
			<form action="" id="loginform" method="POST">
			Username: <br /><input name="user" type="text" /><br />
			Password: <br /><input name="pass" type="password" /><br />
			<input name="action" id="loginaction" type="hidden" value="login" />
			</form>	
			<a class="btn btn-mini btn-success" onclick="$('#loginform').submit();">login</a> or <a class="btn btn-mini btn-primary" onclick="$('#loginform').attr('action', '?register=true');$('#loginaction').val('none');$('#loginform').submit();">register</a>
			<?php
		}
		?>
      </div>
<?php
	if(isset($_SESSION['id']))
	{
?>
      <hr>

      <div class="row-fluid marketing">
<center>    
<table border=1 style="padding:30px;text-align:center;">
  <tbody>
    <tr>
<th>Article</th>  
<th>Cost</th>    
<th>Sizes</th>
<th>Colours</th>
<th>Personalized?</th>
<th>Picture</th>
    </tr>

<?php
$articles = array();
$con = mysql_connect("localhost",$databaseuser,$databasepass);
		if (!$con)
		  {
		  die('Could not connect: ' . mysql_error());
		  }
		mysql_select_db("yasyf_gradphotos", $con);
		$result = mysql_query("SELECT * FROM `articles`") or die(mysql_error());
while($row = mysql_fetch_array($result)){
		if(!in_array($row["article"], $articles))
		$article = ucwords($row["article"]);
		array_push($articles,$article);
		$cost = "$".$articles[$article]["cost"] = $row["cost"];
		$sizes = $articles[$article]["sizes"] = strtoupper(str_replace(",",", ",$row["sizes"]));
		$colours = $articles[$article]["colours"] = ucwords(str_replace(",",", ",$row["colours"]));
		$text = $articles[$article]["text"] = ucwords($row["text"]);
		$img = "<img src='".$articles[$article]["img"] = $row["img"]."' />";
		 echo "<tr>
		 <td>$article</td>     
		 <td>$cost</td>
		  <td>$sizes</td>
		<td>$colours</td>
		<td>$text</td>
		<td>$img</td>
		    </tr>";
		}
?>
 </tbody>
	</table>
	<br />
	<p>Prices are approximate. Sweatpants will have "BRENTWOOD" down the left leg. All clothing is unisex.</p>
	</center>
      </div>
<?php
	}
?>

      <div class="footer">
        <p>&copy; Yasyf Mohamedali 2013</p>
      </div>

    </div> <!-- /container -->
<?php
	if(isset($_SESSION['id']))
	{
?>
		<!-- modal content -->
		<div id="modal-content" style="display:none;background-color:#060606;opacity:0.9;">
		<center>
			<form action="order.php" method="POST">
				<form id="apiform">
				  <fieldset>
				    <label for="article">Article</label>
				<select name="article" id="article" class="text ui-widget-content ui-corner-all">
					<?php foreach($articles as $article){
						if(!is_array($article))
						echo "<option value='$article'>$article</option>";
					}
					?>
				</select>
							
				    <label for="colour">Colour</label>
				    <select name="colour" id="colour" value="" class="text ui-widget-content ui-corner-all" disabled></select>
				    <label for="size">Size</label>
				    <select name="size" id="size" value="" class="text ui-widget-content ui-corner-all" disabled></select>
				    <label for="text">Personalized Text</label>
				    <input name="text" id="text" value="" class="text ui-widget-content ui-corner-all" disabled />
				<br /><br />
				<input type="hidden" name="userid" value="<?php echo $_SESSION['id']; ?>" />
				<input type="hidden" name="email" value="<?php echo $_SESSION['useremail']; ?>" />
				<button id="formsubmitbutton">Submit</button>
				<button onclick='$( "#modal-content" ).dialog("close");return false;'>Cancel</button>
				
				  </fieldset>
			</form>
			</center>
		</div>
		<!-- /modal content -->

	<!-- javascripts -->
	<script>
	
	jQuery(function ($) {
		
		$( "#modal-content" ).dialog({
		      autoOpen: false,
		      height: 320,
		      width: 350,
		      modal: true,
		    });
		// Load dialog on click
		$('#gobtn').click(function () {
			$( "#modal-content" ).dialog("open");
			return false;
		});
		function updateForm()
		{
		var choice = $('#article').val();
							console.log(choice);
							<?php 
							if(isset($_SESSION['id']))
								{
							$i = 0;
							foreach ($articles as $article) {
								if(!is_array($article)){
									$sizes = explode(", ",$articles[$article]["sizes"]);
									$colours = explode(", ",$articles[$article]["colours"]);
									$text = $articles[$article]["text"];
									if($i > 0){
										echo "else ";									}
									echo "if (choice == '$article'){\n";
									echo '$'."('#size').html('');\n";
									echo '$'."('#colour').html('');\n";
									echo "$('#size').prop('disabled', false);";
									echo "$('#colour').prop('disabled', false);";
									foreach($sizes as $size) {
										echo '$'."('#size').append(\"<option value='$size'>$size</option>\");\n";
									}
									foreach($colours as $colour) {
										echo '$'."('#colour').append(\"<option value='$colour'>$colour</option>\");\n";
									}
									if($text == "Yes"){
										echo "$('#text').prop('disabled', false);";
									}
									else {
										echo "$('#text').prop('disabled', true);";
									}
									echo "}\n";
									$i++;	
								}
							}
								}
							?>
		}
		$('#article').change(function () {
					updateForm();
		 		 });
		updateForm();
		 });
		</script>

	<!-- /javascripts -->
	<?php
	}
?>
  </body>
</html>
