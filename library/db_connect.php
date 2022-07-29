

<?php

error_reporting(E_ALL ^ E_DEPRECATED);
//$connection = mysqli_connect('localhost', 'username', 'password', 'database');

$con = mysqli_connect('localhost','root','', 'www_hornbills_in');   
if (!$con || !mysqli_select_db($con,'www_hornbills_in')) {
    die('Unable to connect or select database!'); 
}
?>
