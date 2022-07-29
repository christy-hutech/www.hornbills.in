<?php
################################################################################# 
//Excel class to export database results to excel sheet. 
// Author Name : Pranipat Khatua
// Date: 26-sep-2012
#################################################################################
error_reporting(E_ALL ^ E_NOTICE);
//require_once("../db_config.php");

class Excel{
	require_once('../library/db_connect.php');

/*	private $host = "localhost";
	private $username = "wildnet_fbauth" ;
	private $password = "[MlgpFn.IdlI";
	public $db = 'wildnet_cate_manager';
	public $conn;
*/	
	public $query;
	public $result;
	public $num_fields;
	public $num_rows;
	
	public $error;
	private $excel_header ; // we need custom header , so we will set this by passing an array to set_excel_header function.
	public $sql;
	
	/*public function __construct($db_name=""){
		$this->conn = mysql_connect(HOST,$this->username,$this->password) or die('Error Connecting'); 
		if($this->conn){
			if(!empty($db_name)){
				$this->db = $db_name;
			}
			mysql_select_db($this->db,$this->conn) or die(mysql_error()); 
			//echo $db_name;	
		}
	}*/
	
	public function query($sql) {
		if(!empty($this->sql)){
			$this->result = mysqli_query($con,$this->sql);
			if(!empty($this->result)){				
				$this->get_num_fields();
			}else{
				$this->error = mysqli_error();
			}
		}	
	}
	
	public function get_num_fields(){
		if(!empty($this->result)){
			return $this->num_fields = mysqli_num_fields($this->result);
		}	
	}
	
	public function get_num_rows(){
		if(!empty($this->result)){
			return $this->num_rows = mysqli_num_rows($this->result);
		}	
	}
	
	public function set_excel_header($xls_header){
		if(is_array($xls_header) && !empty($xls_header)){
			$this->excel_header = implode("\t",$xls_header);
			//return ; 
		}
	}
	
	public function get_excel_header(){
		return $this->excel_header ;
	}
	
	public function getexcel($filename){
	
		if(!empty($this->sql)){
			$this->query($this->sql);
		}
		//$data = "";
		if(empty($this->error) && isset($this->result)){
			//$row = mysqli_fetch_array($this->result);
			if(!empty($this->excel_header)){
				$header = $this->excel_header;
			}else{
				for($i = 0; $i < $this->num_fields; $i++){
					$header .= mysqli_field_name($this->result,$i)."\t";
				}
			}
			
			while($row = mysqli_fetch_row($this->result)){
				$line = '';
				foreach($row as $value){                                           
					if((!isset($value)) || ($value == "")){
						$value = "\t";
					}
					else{
						$value = str_replace( '"' , '""' , $value );
						$value = '"'.$value.'"'."\t";
					}
					$line .= $value;
				}
				$data .= trim($line)."\n";
			}
			
			$data = str_replace("\\r" , "" , $data);
			
			if ($data == ""){
				$this->error = "\n No Record Found!\n";                       
			}else{
				header('Content-type: application/vnd.ms-excel');
				//header("Content-type: application/octet-stream");
				header("Content-Disposition: attachment; filename=$filename");
				header("Pragma: no-cache");
				header("Expires: 0");
				echo $header."\n";
				print "$data";
				exit;			
			}
		}
	
	}


} // end of class 
?>