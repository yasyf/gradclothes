<?php
$page = $_REQUEST['page'];
$contents = file_get_contents("http://gradclothes.yasyf.com/".$page."?bypass=a3c47b6429025cb7dfdd6623a017c16ff39ebca2");
$archivename = substr($page,0,-4).date("_i_g_m_d_y").".html";
file_put_contents($archivename, $contents);
header("Location: http://gradclothes.yasyf.com/$archivename");
exit();
?>
