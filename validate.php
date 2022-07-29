<?php
 
if(!empty($_POST)){
	if(empty($_POST['name']) || ctype_alpha($_POST['name'])){
		$response = array('message'=>'Enter a valid name');
	}else{
		$response = array('name'=>$_POST['name']);
	}
	print_r($response); exit;
} 
?>
