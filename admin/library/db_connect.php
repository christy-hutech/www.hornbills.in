<?php
    
  // // $con=mysql_connect('localhost','www_hornbills_in','88254nejn54Z33MT'); 
  // $con=mysqli_connect('localhost','root','');   
  // if (!$con || !mysqli_select_db('www_hornbills_in', $con)) {
  // die('Unable to connect or select database!'); }

//$connection = mysqli_connect('localhost', 'username', 'password', 'database');

$con = mysqli_connect('localhost','root','', 'www_hornbills_in');   
if (!$con || !mysqli_select_db($con,'www_hornbills_in')) {
    die('Unable to connect or select database!'); 
}
?>
