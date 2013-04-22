<?php
		function sanitize($input) {
				return mysql_real_escape_string(strtoupper($input));
			}
		function getName($input) {
					return ucwords(str_replace("."," ",substr($input,0,-16)));
					}			
		require "credentials.php";
		$con = mysql_connect("localhost",$databaseuser,$databasepass);

		if (!$con)
		  {
		  die('Could not connect: ' . mysql_error());
		  }
		mysql_select_db("yasyf_gradclothes", $con);
		$tbl_name = "orders";
		$data = array("article","size","colour","userid","text");
		$valuestring = "";
		$insertstring = "";
		foreach ($data as $value) {
			if(!empty($_REQUEST[$value])){
				$$value = sanitize($_REQUEST[$value]);
				$valuestring .= "$value, ";
				$insertstring .= "'".sanitize($_REQUEST[$value])."', ";
			}
		}
		$valuestring = substr($valuestring,0,-2);
		$insertstring = substr($insertstring,0,-2);
		$sql = "INSERT INTO $tbl_name (id, $valuestring)VALUES(NULL, $insertstring)";
		mysql_query($sql) or die(mysql_error());
		mysql_close($con);
		$email = $_REQUEST["email"];
		$url = "http://gradclothes.yasyf.com/manage.php";
		$textt = "Hey ".getName($email).", \r\n Thanks for ordering grad clothing! \r\n You have ordered a ".$size." ".strtolower($colour)." ".strtolower($article).". \r\n"; 
		if(!empty($text)){
			$textt .= "Your personalized text is \"".$text."\".\r\n";
		}
		$textt .= "To view your current orders, or to make a correction, please visit <".$url.">.\r\n";
		$textt .= "If you did not place this order, please email Yasyf (yasyf@yasyf.com) for assistance.\r\n";
		
		$html ="Hey ".getName($email).", <br />Thanks for ordering grad clothing! <br /> You have ordered a ".$size." ".strtolower($colour)." ".strtolower($article).". <br />"; 
		if(!empty($text)){
			$html .= "Your personalized text is <span style='border-style:solid;border-width:2px;'>".$text."</span>.<br />";
		}
		$html .= "To view your current orders, or to make a correction, please visit <a href='".$url."'>".$url."</a>.<br />";
		$html .= "If you did not place this order, please <a href='mailto:yasyf@yasyf.com'>email Yasyf</a> for assistance.";
		require 'sendgrid/SendGrid_loader.php';
		$sendgrid = new SendGrid($mailuser, $mailpass);
		$mail = new SendGrid\Mail();
		$mail->addTo($email)->
		       setFrom('gradclothes@yasyf.com')->
		       setSubject('Grad Clothes Order Confirmation')->
		       setText($textt)->
			       setHtml($html);
		$sendgrid->smtp->send($mail);
		//echo $email;
		header("Location: http://gradclothes.yasyf.com/index.php?action=success");
?>
