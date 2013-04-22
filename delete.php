<?php
		function sanitize($input) {
				return mysql_real_escape_string(strtoupper($input));
			}
		include "credentials.php";
		$con = mysql_connect("localhost",$databaseuser,$databasepass);

		if (!$con)
		  {
		  die('Could not connect: ' . mysql_error());
		  }
		mysql_select_db("yasyf_gradclothes", $con);
		$tbl_name = "orders";
		$data = array("order","userid");
		foreach ($data as $value) {
			if(!empty($_REQUEST[$value])){
				$$value = sanitize($_REQUEST[$value]);
			}
		}
		$sql = "DELETE FROM $tbl_name WHERE `id`='$order' AND `userid`='$userid'";
		mysql_query($sql) or die(mysql_error());
		mysql_close($con);
		header("Location: http://gradclothes.yasyf.com/manage.php?action=success")
?>