<?php
function sanitize($input) {
		return mysql_real_escape_string(strtoupper($input));
	}
function getName($input) {
			return ucwords(str_replace("."," ",substr($input,0,-16)));
			}			
function getURL($string){
	 $vq = $string;
    $vq = preg_replace('/[[:space:]]+/', ' ', trim($vq));
    $vq = urlencode($vq);
    $feedURL = 'http://gdata.youtube.com/feeds/api/videos?q='.$vq.'&safeSearch=none&orderby=relevance&restriction=CA&v=2'; // Added version two tag...
    $youTubeXML = simplexml_load_file($feedURL);
    $videoLink = $youTubeXML->entry->link[0]['href'];
    $videoLink = str_replace('&feature=youtube_gdata', '', $videoLink);
    return $videoLink;
}
require "credentials.php";

$con = mysql_connect("localhost",$databaseuser,$databasepass);

	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }
	mysql_select_db("yasyf_gradclothes", $con);
	$result = mysql_query("SELECT * FROM `songs`") or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$songid = $row["id"];
		$videourl = getURL($row["song"]." ".$row["artist"]);
		$tbl_name = "songs";
		$sql = "UPDATE`$tbl_name` SET video='$videourl' WHERE `id`='$songid'";
		echo $sql."<br />";
		mysql_query($sql) or die(mysql_error());
		}
	mysql_close($con);
?>
