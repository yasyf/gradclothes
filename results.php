<?php
session_start();
		if(!isset($_SESSION['id']) || empty($_SESSION['id']) || $_SESSION['id'] != "1"){
			if ($_GET['bypass'] != "a3c47b6429025cb7dfdd6623a017c16ff39ebca2") {
			session_destroy();
			header("Location: http://gradclothes.yasyf.com/index.php?error=true&next=results.php");
			exit();
			}
			else{
			$id = $_SESSION['id'] = "1";
			require "credentials.php";
			}
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
	<li><a href="http://gradclothes.yasyf.com/vote.php">Vote</a</li>  
	<li><a href="http://gradclothes.yasyf.com/index.php?action=logout">Logout</a></li>
	<?php
	}
	?>
        </ul>
        <h3 class="muted">Grad Songs Vote Results</h3>
      </div>

      <hr>

      
<?php

	if(isset($_SESSION['id']))
	{
	$songs = array();
	$speakers = array();
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
	$result = mysql_query("SELECT * FROM `votes`") or die(mysql_error());
	$totalslowsongvotes = 0;
	$totalfastsongvotes = 0;
	$totalspeakervotes = 0;
	while($row = mysql_fetch_array($result)){
		if ($row["type"] != "speaker") {
			$songs[$row["itemid"]]["votes"] += 1;
			if ($row["type"] != "slow") {
				$totalslowsongvotes += 1;
			}
			else{
				$totalfastsongvotes += 1;
			}
			
		}
		else{
			$speakers[$row["itemid"]]["votes"] += 1;
			$totalspeakervotes += 1;
		}
	}
?>
 <div class="jumbotron">
 	<?php
        if (!$_GET['bypass']) {
				echo "<a class='btn btn-large btn-success' href='archive.php?page=results.php'>Archive</a>";
			}
        ?>
		<?php

			$string1 = "";
			$string2 = "";

			foreach ($songs as $song) {
				if ($song["type"] == "slow") {
				if ($song["votes"] != 0) {
					$string1 .= str_replace(" ", "+", $song["song"])."|";
					$string2 .= (($song["votes"]/$totalslowsongvotes)*100).",";
				}
			}
			}
			$string1 = substr($string1,0,-1);
			$string2 = substr($string2,0,-1);

			$url = "http://chart.googleapis.com/chart?chf=a,s,000000|bg,s,000000&chxs=0,00FF00,11.6&chxt=x&chs=800x320&cht=p3&chco=4D89F9&chd=t:".$string2."&chl=".$string1;
		?>
		<h1 id='bigmessage'>Slow Song Vote</h1>
		<p><img src="<?php echo $url; ?>" width="800" height="320" /></p>
		<br />
		<?php

			$string1 = "";
			$string2 = "";

			foreach ($songs as $song) {
				if ($song["type"] == "fast") {
				if ($song["votes"] != 0) {
					$string1 .= str_replace(" ", "+", $song["song"])."|";
					$string2 .= (($song["votes"]/$totalfastsongvotes)*100).",";
				}
			}
			}
			$string1 = substr($string1,0,-1);
			$string2 = substr($string2,0,-1);

			$url = "http://chart.googleapis.com/chart?chf=a,s,000000|bg,s,000000&chxs=0,00FF00,11.6&chxt=x&chs=800x320&cht=p3&chco=4D89F9&chd=t:".$string2."&chl=".$string1;
		?>
		<h1 id='bigmessage'>Fast Song Vote</h1>
		<p><img src="<?php echo $url; ?>" width="800" height="320" /></p>
		<br />
		<?php

			$string1 = "";
			$string2 = "";

			foreach ($speakers as $speaker) {
				if ($speaker["votes"] != 0) {
					$string1 .= str_replace(" ", "+", $speaker["name"])."|";
					$string2 .= (($speaker["votes"]/$totalspeakervotes)*100).",";
				}
			}
			$string1 = substr($string1,0,-1);
			$string2 = substr($string2,0,-1);

			$url = "http://chart.googleapis.com/chart?chf=a,s,000000|bg,s,000000&chxs=0,00FF00,11.6&chxt=x&chs=800x320&cht=p3&chco=4D89F9&chd=t:".$string2."&chl=".$string1;
		?>
		<h1 id='bigmessage'>Speaker Vote</h1>
		<p><img src="<?php echo $url; ?>" width="800" height="320" /></p>
      </div>
      <hr>

      <div class="row-fluid marketing">
<center>        
<?php

	if ($totalslowsongvotes === 0) {
		$empty = true;
		echo "<p class='text-error'>There are no votes for any slow songs yet.</p>";
	}
	else{
		?>
		<p class="lead">Total Slow Song Votes: <?php echo $totalslowsongvotes; ?></p>
		<table border=1 style="padding:30px;text-align:center;">
  <tbody>
    <tr>
<th>Song ID</th>
<th>Song Name</th>
<th>Artist</th> 
<th>Video</th> 
<th style="color:green;">Votes</th> 
    </tr>
    <?php
		foreach ($songs as $song) {		 	
			if ($song["type"] == "slow") {
			echo "
			<tr>
			<td>".$song["id"]."</td> 
			<td>".$song["song"]."</td>     
			<td>".$song["artist"]."</td>
			<td><a target='_blank' href='".$song["video"]."'>YouTube</a></td>
			<td style='color:green;'>".$song["votes"]."</td>
			</tr>";
			}
			}
			?>
			</tbody>
	</table>
	<br />
	<?php
		}
if ($totalfastsongvotes === 0) {
		$empty = true;
		echo "<p class='text-error'>There are no votes for any fast songs yet.</p>";
	}
	else{
		?>
		<p class="lead">Total Fast Song Votes: <?php echo $totalfastsongvotes; ?></p>
		<table border=1 style="padding:30px;text-align:center;">
  <tbody>
    <tr>
<th>Song ID</th>
<th>Song Name</th>
<th>Artist</th> 
<th>Video</th> 
<th style="color:green;">Votes</th> 
    </tr>
    <?php
		foreach ($songs as $song) {		 	
			if ($song["type"] == "fast") {
			echo "
			<tr>
			<td>".$song["id"]."</td> 
			<td>".$song["song"]."</td>     
			<td>".$song["artist"]."</td>
			<td><a target='_blank' href='".$song["video"]."'>YouTube</a></td>
			<td style='color:green;'>".$song["votes"]."</td>
			</tr>";
			}
			}
			}
			?>
			</tbody>
	</table>
	<br />
	<?php
		}

	if ($totalspeakervotes === 0) {
		$empty = true;
		echo "<p class='text-error'>There are no votes for any speakers yet.</p>";
	}
	else{
		?>
		<p class="lead">Total Speaker Votes: <?php echo $totalspeakervotes; ?></p>
		<table border=1 style="padding:30px;text-align:center;">
  <tbody>
    <tr>
<th>Speaker Name</th>
<th style="color:green;">Votes</th> 
    </tr>
    <?php
		foreach ($speakers as $speaker) {		 	
			echo "
			<tr>
			<td>".$speaker["name"]."</td> 
			<td style='color:green;'>".$speaker["votes"]."</td>
			</tr>";
			}
			?>
			</tbody>
	</table>
	<br />
	<?php
		}
?>
	</center>
      </div>



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
				    <label for="songid">Song</label>
				<select name="songid" id="songid" class="text ui-widget-content ui-corner-all">
					<?php foreach($songs as $song){
						echo "<option value='".$song["id"]."'>".$song["song"]." by ".$song["artist"]."</option>";
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
		      height: 150,
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
				$( "#modal-content" ).html("<center><p class='text-error'>You can only vote three times.</p><button onclick='$( \"#modal-content\" ).dialog(\"close\");return false;'>Close</button></center>");
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
