<?php
session_start();
		if(!isset($_SESSION['id']) || empty($_SESSION['id'])){
			session_destroy();
			header("Location: http://gradclothes.yasyf.com/index.php?error=true&next=manage.php");
			exit();
		}
		else {
			$id = $_SESSION['id'];
			require "credentials.php";
		}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Grad Clothes Order Management Form</title>
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
	<li><a href="http://gradclothes.yasyf.com">Order</a></li>
	<li class="active"><a href="http://gradclothes.yasyf.com/manage.php">Manage</a></li>  
	<li><a href="http://gradclothes.yasyf.com/vote.php">Vote</a</li> 
	<li><a href="http://gradclothes.yasyf.com/index.php?action=logout">Logout</a></li>	<?php
	}
	?>
        </ul>
        <h3 class="muted">Grad Clothes Order Management Form</h3>
      </div>

      <hr>

      <div class="jumbotron">
		<?php
	 	if (isset($_SESSION['id'])) {
		 if ($_REQUEST['action'] == "success")
			{
		?>
		<h1 id='bigmessage'>Deletion Success!</h1>
		<p class="lead" id='smallmessage'>Click This Button To Delete Another Order</p>
        <a class="btn btn-large btn-success" id="gobtn" href="#">Delete Again</a>
		<?php
			} 
		else {
			?>
		<h1 id='bigmessage'>Welcome</h1>
        <p class="lead" id='smallmessage'>Click This Button To Delete An Order</p>
        <a class="btn btn-large btn-success" id="gobtn" href="#">Delete</a>
		<?php
		 }
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
<?php
$orders = array();
$con = mysql_connect("localhost",$databaseuser,$databasepass);
$empty = false;

	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }
	mysql_select_db("yasyf_gradclothes", $con);
	$result = mysql_query("SELECT * FROM `orders` WHERE userid='$id'") or die(mysql_error());
	if (mysql_num_rows($result) === 0) {
		$empty = true;
		echo "<p class='text-error'>There are no active orders under this account.</p>";
	}
	else{
		?>
		<table border=1 style="padding:30px;text-align:center;">
  <tbody>
    <tr>
<th style="color:red;">Order ID</th>
<th>Article</th>  
<th>Size</th>
<th>Colour</th>
<th>Personalized Text</th>
    </tr>
    <?php
		while($row = mysql_fetch_array($result)){
			$orderid = ucwords($row["id"]);
			$orders[$orderid]["id"] = $orderid;
			$orders[$orderid]["article"] = $article = ucwords($row["article"]);
			$orders[$orderid]["size"] = $size = strtoupper($row["size"]);
			$orders[$orderid]["colour"] = $colour = ucwords($row["colour"]);
			$orders[$orderid]["text"] = $text = strtoupper($row["text"]);
			if(empty($text)){
				$text = "N/A";
			}
			 echo "<tr>
			<td style='color:red;'>$orderid</td> 
			<td>$article</td>     
			  <td>$size</td>
			 <td>$colour</td>
			<td>$text</td>
			    </tr>";
			}
		}
?>
 </tbody>
	</table>
	</center>
      </div>
<?php
	}
?>

      <div class="footer">
        <p>&copy; Yasyf Mohamedali 2013</p>
      </div>

    </div> <!-- /container -->

		<!-- modal content -->
		<div id="modal-content" style="display:none;background-color:#060606;opacity:0.9;">
		<center>
			<form action="delete.php" method="POST">
				<form id="apiform">
				  <fieldset>
				    <label for="order">Order</label>
				<select name="order" id="order" class="text ui-widget-content ui-corner-all">
					<?php foreach($orders as $order){
						echo "<option value='".$order["id"]."'>".$order["id"]."/".$order["colour"]."/".$order["size"]."/".$order["article"]."/\"".$order["text"]."\"</option>";
					}
					?>
				</select>
				<br />
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
		      height: 150,
		      width: 350,
		      modal: true,
		    });
		// Load dialog on click
		$('#gobtn').click(function () {
			<?php if (!$empty) {
				?>
			$( "#modal-content" ).dialog("open");
				<?php
			}
			else{
				?>
				$( "#modal-content" ).html("<center><p class='text-error'>There are no active orders under this account.</p><button onclick='$( \"#modal-content\" ).dialog(\"close\");return false;'>Close</button></center>");
				$( "#modal-content" ).dialog("open");
				<?php
			}
			?>
			return false;
		});
		 		 });
		</script>

	<!-- /javascripts -->
  </body>
</html>
