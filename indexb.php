<?php
//no  cache headers 
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if(!$_SESSION["userid"] != "" || !isset($_SESSION["userid"]) || empty($_SESSION["userid"])){
	header("Location: http://www.yasyf.com/snowball/pictures/login.php");
}
include "credentials.php";
function CapitalizeName($name) {
    $name = strtolower($name);
    $name = join("'", array_map('ucwords', explode("'", $name)));
    $name = join("-", array_map('ucwords', explode("-", $name)));
    $name = join("Mac", array_map('ucwords', explode("Mac", $name)));
    $name = join("Mc", array_map('ucwords', explode("Mc", $name)));
    return $name;
}
if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['article']) && isset($_POST['colour']) && isset($_POST['size']) && !$_REQUEST['admin'])
{
	$con = mysql_connect("localhost",$databaseuser,$databasepass);

	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }
	mysql_select_db("yasyf_gradclothes", $con);
	$name = CapitalizeName(trim($_POST['name']));
	$email = trim($_POST['email']);
	$photo = $_POST['photo'];
	$size = $_POST['size'];
	mysql_query("INSERT INTO pictures (`name`,`email`,`photo`,`size`) VALUES ('$name', '$email', '$photo', '$size');");
	mysql_close($con);
	$url = "http://www.yasyf.com/snowball/pictures/correction.php?name=".urlencode($name)."&key=".md5($name);
	$text = "Hey ".$name.", \r\n Thanks for confirming your Snowball picture order. The pictures will be available for pickup at the Valentines Dance. \r\n You have ordered picture #".$photo.". \r\n To make a correction, please visit <".$url.">.";
	$html = "Hey ".$name.", <br /> Thanks for confirming your Snowball picture order. The pictures will be available for pickup at the Valentines Dance. <br /> You have ordered picture #".$photo.".  <br /> To make a correction, please visit <a href='".$url."'>".$url."</a>.";
	include '../sendgrid/SendGrid_loader.php';
	$sendgrid = new SendGrid($mailuser, $mailpass);
	$mail = new SendGrid\Mail();
	$mail->addTo($email)->
	       setFrom('snowball@yasyf.com')->
	       setSubject('Snowball Picture Order Confirmation')->
	       setText($text)->
		       setHtml($html);
	$sendgrid->smtp->send($mail);
	header("Location: http://www.yasyf.com/snowball/pictures/?confirm=true");
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Grad Clothing Order From</title>
<?php if(!$_REQUEST['print']){ ?>
<link href="http://cache.yasyf.com/style.css" rel="stylesheet" type="text/css" media="screen" />
<?php } ?>
</head>
<body>
<center>
<?php if(!$_REQUEST['print']){ ?>
<h2>Grad Clothing Order Form</h2>
<h3 style="color:red">Please Use A Separate Entry For Each Article You Wish To Order</h3>
<h3 style="color:red">Need To Change Your Order? Check Your Email</h3>
<table border=1>
  <tbody>
    <tr>
<th>Name</th>      
<th>Photo #s</th>
<th>Sizes</th>
<th>Cost</th>
    </tr>

<br /><br />
<?php
if($_REQUEST['order']){
	?>
<form action="" method="POST" style="border-style:solid;border-width:2px;">
Name: <input type="text" name="name" value="<?php if(isset($_REQUEST['name'])) echo $_REQUEST['name'];?>"/> <br />
Email: <input type="text" name="email" value="<?php if(isset($_REQUEST['email'])) echo $_REQUEST['email'];?>"/> <br />
Photo #:
<select name="photo">
<?php
for ($i = 1;$i<=817;$i++) {
	?>  
	<option value="<?php echo $i; ?>" <?php if($i == $_REQUEST['photo']) echo "selected='selected'";?>><?php echo $i; ?></option>
<?php
}
?>
</select><br />
Size:
<select name="size"> 
	<option value="4x6" <?php if("4x6" == $_REQUEST['size']) echo "selected='selected'";?>>4x6</option>
	<option value="5x7" <?php if("5x7" == $_REQUEST['size']) echo "selected='selected'";?>>5x7</option>
	<option value="8x10" <?php if("8x10" == $_REQUEST['size']) echo "selected='selected'";?>>8x10</option>
</select> <br />
<input type="submit" />
<?php 
if(isset($_GET['confirm'])){
	?>
	<br />A confirmtion has been emailed to you.
	<?php
}
?>
</form>
<br /><br />
<?php 
}
}
?>
<table border=1>
  <tbody>
    <tr>
<th>Article</th>  
<th>Cost</th>    
<th>Sizes</th>
<th>Colours</th>
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
		$article = $row["article"];
		array_push($articles,$article);
		$cost = $articles[$article]["cost"] = $row["cost"];
		$sizes = $articles[$article]["sizes"] = $row["sizes"];
		$colours = $articles[$article]["colours"] = $row["colours"];
		$img = $articles[$article]["img"] = $row["img"];
					
		 echo "<tr>
		 <td>$article</td>     
		 <td>$cost</td>
		  <td>$sizes</td>
		<td>$colours</td>
		    </tr>";
		}
?>
 </tbody>
	</table>
<?php if($_REQUEST['admin']){
$orders = array();
		if (!$con)
		  {
		  die('Could not connect: ' . mysql_error());
		  }
		mysql_select_db("yasyf_gradphotos", $con);
		$result = mysql_query("SELECT * FROM `orders`") or die(mysql_error());
while($row = mysql_fetch_array($result)){
		if(!in_array($row["name"], $names))
		array_push($names,$row["name"]);
}
		foreach ($names as $name) {
			$costs[$name] = 0;
			$orders = mysql_query("SELECT * FROM `pictures` WHERE `name`='$name'") or die(mysql_error());
					while($row2 = mysql_fetch_array($orders)){
					if(empty($photos[$name]))
					{
						$photos[$name] = $row2["photo"];
						$sizes[$name] = $row2["size"];
					}
					else {
						$photos[$name] .= ", ".$row2["photo"];
						$sizes[$name] .= ", ".$row2["size"];
					}
					switch ($row2["size"]) {
						case "4x6": $costs[$name] += 2; break;
						case "5x7": $costs[$name] += 3; break;
						case "8x10": $costs[$name] += 5; break;
					}
					}
					
		 echo "<tr>
		 <td>$name</td>     
		 <td>$photos[$name]</td>
		  <td>$sizes[$name]</td>
		<td>$$costs[$name]</td>
		    </tr>";
		}
?>
	  </tbody>
	</table>
	
	<?php 
		$total = 0;
		foreach ($costs as $cost) {
			$total += $cost;
		}
		?>
		<br />
		<h3>Total:</h3>
		<span style="color:green"><b>$<?php echo $total; ?></b></span>
		<?php
	}
	?>
	<?php if(!$_REQUEST['print']){ ?>
	<p><b>Need help? <a href="mailto:yasyf@yasyf.com?subject=Snowball Photos Help" style="color:green;text-decoration:none;">Email Yasyf</a>.</b></p>
	<?php } ?>
	</center>	
</body>
</html>