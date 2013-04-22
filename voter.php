<?php
		function sanitize($input) {
				return mysql_real_escape_string(strtoupper($input));
			}
		function error_die() {
				header("Location: http://gradclothes.yasyf.com/vote.php?error=dupe");
				exit();
			}	
		include "credentials.php";
		$con = mysql_connect("localhost",$databaseuser,$databasepass);

		if (!$con)
		  {
		  die('Could not connect: ' . mysql_error());
		  }
		mysql_select_db("yasyf_gradclothes", $con);
		$tbl_name = "votes";

		$sql = "DELETE FROM $tbl_name WHERE `userid`='".sanitize($_REQUEST["userid"])."'";
		mysql_query($sql) or die(mysql_error());

		$data = array("userid","songid1");
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
		$valuestring = str_replace('songid1', 'itemid', $valuestring);
		$insertstring = substr($insertstring,0,-2);
		$sql = "INSERT INTO $tbl_name (id, type, $valuestring)VALUES(NULL, 'slow', $insertstring)";
		mysql_query($sql) or error_die();

		$data = array("userid","songid2");
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
		$valuestring = str_replace('songid2', 'itemid', $valuestring);
		$insertstring = substr($insertstring,0,-2);
		$sql = "INSERT INTO $tbl_name (id, type, $valuestring)VALUES(NULL, 'fast', $insertstring)";
		mysql_query($sql) or error_die();

		$data = array("userid","speakerid");
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
		$valuestring = str_replace('speakerid', 'itemid', $valuestring);
		$insertstring = substr($insertstring,0,-2);
		$sql = "INSERT INTO $tbl_name (id, type, $valuestring)VALUES(NULL, 'speaker', $insertstring)";
		mysql_query($sql) or error_die();

		mysql_close($con);
		header("Location: http://gradclothes.yasyf.com/vote.php?action=success");
?>
