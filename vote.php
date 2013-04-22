<?php
session_start();
		if(!isset($_SESSION['id']) || empty($_SESSION['id'])){
			session_destroy();
			header("Location: http://gradclothes.yasyf.com/index.php?error=true&next=vote.php");
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
    <title>Grad Songs Vote Form</title>
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
	<li><a href="http://gradclothes.yasyf.com/manage.php">Manage</a></li> 
	<li class="active"><a href="http://gradclothes.yasyf.com/vote.php">Vote</a</li>  
	<li><a href="http://gradclothes.yasyf.com/index.php?action=logout">Logout</a></li>
	<?php
	}
	?>
        </ul>
        <h3 class="muted">Grad Songs Vote Form</h3>
      </div>

      <hr>

      <div class="jumbotron">
		<?php
	 	if (isset($_SESSION['id'])) {
		 if ($_REQUEST['error'] == "dupe")
			{
		?>
		<h1 id='bigmessage'>Vote Failed!</h1>
		<p class="lead" class='text-error' id='smallmessage'>You Have Already votes For This Song</p>
        <a class="btn btn-large btn-success" id="gobtn" href="#">Vote Again</a>
		<?php
			} 
		else if ($_REQUEST['action'] == "success")
			{
		?>
		<h1 id='bigmessage'>Vote Success!</h1>
		<p class="lead" id='smallmessage'>Click This Button To Change Your Vote</p>
        <a class="btn btn-large btn-success" id="gobtn" href="#">Change Vote</a>
		<?php
			} 
		else {
			?>
		<h1 id='bigmessage'>Welcome</h1>
        <p class="lead" id='smallmessage'>Click This Button To Vote</p>
        <a class="btn btn-large btn-success" id="gobtn" href="#">Vote</a>
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
$songs = array();
$speakers = array();
$votes = array();
$con = mysql_connect("localhost",$databaseuser,$databasepass);
$empty = false;

	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }
	mysql_select_db("yasyf_gradclothes", $con);
	$result = mysql_query("SELECT * FROM `songs`") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$songid = $row["id"];
		$songs[$songid]["id"] = $songid;
		$songs[$songid]["song"] = $row["song"];
		$songs[$songid]["artist"] = $row["artist"];
		$songs[$songid]["video"] = $row["video"];
		$songs[$songid]["type"] = $row["type"];
	}
	$result = mysql_query("SELECT * FROM `speakers`") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$speakerid = $row["id"];
		$speakers[$speakerid]["id"] = $speakerid;
		$speakers[$speakerid]["name"] = $row["name"];
	}

	$result = mysql_query("SELECT * FROM `votes` WHERE userid='$id'") or die(mysql_error());
	$totalvotes = mysql_num_rows($result);
	if ($totalvotes === 0) {
		$empty = true;
		echo "<p class='text-error'>You have not voted yet.</p>";
	}
	else{
		while($row = mysql_fetch_array($result)){
		$type = $row["type"];
		$votes[$type]["id"] = $row["itemid"];
		$votes[$type]["type"] = $row["type"];
	}
		?>
		<p class="lead">Your Votes</p>
		<table border=1 style="padding:30px;text-align:center;">
  <tbody>
    <tr>
<th>Name</th>
<th>Artist</th> 
<th>Video</th> 
<th>Type</th> 
    </tr>
    <?php
    	$votes  = array_reverse($votes);
		foreach ($votes as $vote) {	
		$itemid = $vote["id"];	 	
			if ($vote["type"] != "speaker") {
				$song = $songs[$itemid];
				echo "
				<tr>
				<td>".$song["song"]."</td>     
				<td>".$song["artist"]."</td>
				<td><a target='_blank' href='".$song["video"]."'>YouTube</a></td>
				<td>".ucfirst($song["type"])." Song</td>  
				</tr>";
				}
			else{
				$speaker = $speakers[$itemid];
				echo "
				<tr>
				<td>".$speaker["name"]."</td>     
				<td>N/A</td>
				<td>N/A</td>
				<td>".ucfirst($vote["type"])."</td>  
				</tr>";
			}
			}
			?>
			</tbody>
	</table>
	<?php
		}
?>
<br />
<p class="lead">Slow Song Options</p>
 	<table border=1 style="padding:30px;text-align:center;">
 <tbody>
    <tr>
<th>Song Name</th>
<th>Artist</th> 
<th>Video</th> 
    </tr>
    <?php
    
		foreach ($songs as $song) {
			if($song["type"] == "slow"){
				echo "
				<tr>
				<td>".$song["song"]."</td>     
				<td>".$song["artist"]."</td>
				<td><a target='_blank' href='".$song["video"]."'>YouTube</a></td>
				</tr>";
				}
			}
			?>
			</tbody>
	</table>
	<br />
	<p class="lead">Fast Song Options</p>
 	<table border=1 style="padding:30px;text-align:center;">
 <tbody>
    <tr>
<th>Song Name</th>
<th>Artist</th> 
<th>Video</th> 
    </tr>
    <?php
    
		foreach ($songs as $song) {
			if($song["type"] == "fast"){
				echo "
				<tr>
				<td>".$song["song"]."</td>     
				<td>".$song["artist"]."</td>
				<td><a target='_blank' href='".$song["video"]."'>YouTube</a></td>
				</tr>";
				}
			}
			?>
			</tbody>
	</table>
	<br />
	<p class="lead">Grad Speaker Options</p>
 	<table border=1 style="padding:30px;text-align:center;">
 <tbody>
    <tr>
<th>Speaker Name</th>
    </tr>
    <?php
    
		foreach ($speakers as $speaker) {
			if($song["type"] == "fast"){
				echo "
				<tr>
				<td>".$speaker["name"]."</td>     
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
			<form action="voter.php" method="POST">
				<form id="apiform">
				  <fieldset>
				    <label for="songid1">Slow Song</label>
				<select name="songid1" id="songid1" class="text ui-widget-content ui-corner-all">
					<?php foreach($songs as $song){
						if($song["type"] == "slow"){
							if ($votes["slow"]["id"] == $song["id"]) {
								echo "<option value='".$song["id"]."' selected>".$song["song"]." by ".$song["artist"]."</option>";
							}
							else{
								echo "<option value='".$song["id"]."'>".$song["song"]." by ".$song["artist"]."</option>";
							}
						
						}
					}
					?>
				</select>
				<br />
				<label for="songid2">Fast Song</label>
				<select name="songid2" id="songid2" class="text ui-widget-content ui-corner-all">
					<?php foreach($songs as $song){
						if($song["type"] == "fast"){
							if ($votes["fast"]["id"] == $song["id"]) {
								echo "<option value='".$song["id"]."' selected>".$song["song"]." by ".$song["artist"]."</option>";
							}
							else{
								echo "<option value='".$song["id"]."'>".$song["song"]." by ".$song["artist"]."</option>";
							}
						}
					}
					?>
				</select>
				<br />
				<label for="speakerid">Grad Speaker</label>
				<select name="speakerid" id="speakerid" class="text ui-widget-content ui-corner-all">
					<?php foreach($speakers as $speaker){
						if ($votes["speaker"]["id"] == $speaker["id"]) {
								echo "<option value='".$speaker["id"]."' selected>".$speaker["name"]."</option>";
							}
							else{
								echo "<option value='".$speaker["id"]."'>".$speaker["name"]."</option>";
							}

					}
					?>
				</select>
				<br />
				<input type="hidden" name="userid" value="<?php echo $_SESSION['id']; ?>" />
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
		      height: 300,
		      width: 350,
		      modal: true,
		    });
		// Load dialog on click
		$('#gobtn').click(function () {
			<?php if ($totalvotes < 3) {
				?>
			$( "#modal-content" ).dialog("open");
				<?php
			}
			else{
				?>
				//$( "#modal-content" ).html("<center><p class='text-error'>You have already votes.</p><button onclick='$( \"#modal-content\" ).dialog(\"close\");return false;'>Close</button></center>");
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
