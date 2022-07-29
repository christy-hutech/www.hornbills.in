<?php  
$to = "frogmouth@gmail.com, aparajita@ncf-india.org";
$subject = "Admin Credentials for Hornbill Watch";
$txt = "The username for admin login is 'admin' and password is 'Hornbill@2'";
//$headers = "From : Hornbill" . "\r\n" ;

// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	// Additional headers
	$headers .= 'To: Ramki <frogmouth@gmail.com>, Aparajita <aparajita@ncf-india.org>' . "\r\n";
	$headers .= 'From: Horbill Watch <admin@hornbills.in>' . "\r\n";


mail($to,$subject,$txt,$headers);
header('Location: /admin/login.php?m=42');
exit;
?> 
