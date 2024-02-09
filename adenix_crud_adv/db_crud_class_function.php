<?php
//error_reporting(0);

class dbConnecttion{
	private  $db;
	
	public function __construct(){
		//new mysqli("localhost", "root", "","bitcoindb") or die("no connection"); //
    $this->db =new mysqli("localhost", "root", "","bitcoindb") or die("no connection");
	}
	
		
	public function getConnection(){
		if(!$this->db):
		new dbConnecttion();
		endif;
		return $this->db;
		}
	}


class db_application_class extends form_new_account_validation {
	
	private  $db_key;
	private $Id;
	
	private $checkerprocess;
	
	public function  __construct(){
		$con = new dbConnecttion();
		$this->db_key = $con->getConnection(); 
		} 
		
	public function db_application_insert($tablename,$question_count,$field){
		
		$q = implode(',',array_fill(0,$question_count,'?'));
		$dbtype = str_repeat("s",$question_count);
		$stmt =$this->db_key->prepare("insert into $tablename values(null,$q)");
		$stmt->bind_param($dbtype,...$field);
		$stmt->execute();
		$this->Id=$this->db_key->insert_id;
		if($stmt->affected_rows>0){
			return true;
			
			}
			else{
				//echo $this->db_key->error;
				return false;
				}
		$this->db_key->close();
		}
		
public function db_application_update_table($num,$update,$sql){
	//$q = implode(',',array_fill(0,$question_count,'?'));
		$dbtype = str_repeat("s",$num);
		$stmt = $this->db_key->prepare($sql);
		$stmt->bind_param($dbtype,...$update);
		$stmt->execute();
		
		if($stmt->affected_rows>0){
		return true;
		//$this->db_key->error();
			}
			else{
				return false;
				}
		//$this->db_key->close();
	}
	
	public function fetch_record_per_rows($stmt,$para,$num){
	$elo =$this->db_key->prepare($stmt);
	$dbtype = str_repeat("s",$num);
	$elo->bind_param($dbtype,...$para);  
$elo->execute();
$r = $elo->get_result();
$call = $r->fetch_object();

$elo->free_result(); 
$elo->close();

//fetching out and checking the form if the applicant is have completed his profile 
return $call;
	}
	
public function fetch_record_per_rows_query($sql){
	//bind with connection
	$elo = $this->db_key->query($sql);
	$rst = $elo->fetch_object();
	//
	
	return $rst;
	$this->db_key->close();
	
	}
	
	
public function getIdUser(){
	return $this->Id;
	}
	

public function getSelectOption($tablename,$id,$val){
		
	if($id!=0 || $val !=""){
		//$row = null;
		$stmt = "SELECT $id,$val from $tablename order by $id";
		$process = $this->db_key->query($stmt);
		
		while(@$row =$process->fetch_assoc()){
			
			echo "<option value='".@$row[$id]."'>".@$row[$val]."</option>";
		}
		//while(@$row =$process->fetch_assoc());
	}
	
	}
	
	public function getSelectOptionbyID($tablename,$id,$val,$stateid,$getValue){
		
	if($id!=0 || $val !=""){
		//$row = null;
		$stmt = "SELECT $id,$val from $tablename where $stateid=$getValue order by $id";
		$process = $this->db_key->query($stmt);
		
		while(@$row =$process->fetch_assoc()){
			
			echo "<option value='".@$row[$id]."'>".@$row[$val]."</option>";
		}
		//while(@$row =$process->fetch_assoc());
	}
	
	}
	
	
public function processcheckervalidate($stmt){

		if(!empty($stmt)):
		$processQuery = $this->db_key->query($stmt);
		$rst = $processQuery->fetch_array();
		return $rst[0];
	else:
	return false;
	endif;
	


	}
	
public function getCheckProcessQuery(){
	return $this->checkerprocess;
		}
	
	
	
	public function email_send_request($to){
	$c = new Country();
	$ip = $c->getCountry();
	$from ="";
	$subject = "LOGIN NOTIFICATION";
	//$firstname = $name;
	$message ='<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Login Notification</title></head><body>
	

<div style="margin:auto 0px; width:100%;">
 <div style="padding:.3rem 1rem;margin-bottom:0;background-color:rgba(0,0,0,.03);border-bottom:1px solid rgba(0,0,0,.125);background-color:rgba(0,0,0)!important;width:100%;background-clip:border-box;border:1px solid rgba(0,0,0,.125);border-radius:.25rem;"><img src="http://demo.cryptoerratic.com/images/logo.png" alt="cryptoerratic.com" /></div>
<div style="position:relative;display:flex;flex-direction:column;min-width:0;word-wrap:break-word;background-color:#fff;background-clip:border-box;border:0px solid rgba(0,0,0,.125);border-radius:.25rem;color:rgba(33,37,41)!important;margin-bottom:1rem!important;flex:0 0 auto;width:100%;">
 
  <div style="padding:1rem 1rem;">
    <h2>Hey!</h2>
    
   
    <p><i>Username: </i>'.$to.'</p>
    <p><i>Date: </i>'.date('Y-m-d:h:i:sa"').'</p>
	<p><i>Country: </i>'.$ip.'</p>
    	
 
	
  </div>
 


</div>
 <div style="padding:.5rem 1rem;background-color:rgba(0,0,0,.03);border-top:1px solid rgba(0,0,0,.125); width:100%;">
<i>If there’s anything we can help you with, please contact us at support@cryptoerratic.com.
You received this email to let you know about important changes to your Cryptoerratic Account and services. </i> 
  </div>
</div>
	
	
	
	
	
	
	</body></html>';
	
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers.= 'From: <'.$from.'>';
	if(@mail($to, $subject, $message, $headers)){
		return true;
		}
		else{
			return false;
			}
		
		}
		
	}
	
class Country{
	
	private $ipdat;
	public function getVisIpAddr() { 
	
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) { 
		return $_SERVER['HTTP_CLIENT_IP']; 
	} 
	else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { 
		return $_SERVER['HTTP_X_FORWARDED_FOR']; 
	} 
	else { 
		return $_SERVER['REMOTE_ADDR']; 
	} 
} 
	
	public function getCountry(){
		
		$ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$this->getVisIpAddr())); 
   
	return  $ipdat->geoplugin_countryName;
		}
	
	}

//
class php_key_function{
	
	private $message;
	private $password;
	public function validation_stripslashes($post){
		return trim(stripslashes($post));
		}
		
	public function password_validation($pass1,$pass2){
		
		if($pass1!=$pass2){
			$this->throw_message('0');
			}
			else if(strlen($pass1)<=6 || strlen($pass2)<=6){
				$this->throw_message('1');
				//<p class="alert alert-danger">The Password must at least 8 Character</p>
				}
			else if(empty($pass1) || empty($pass2)){
				$this->throw_message('2');
				}
				else{
				 $this->password = $pass1 ?? $pass2;
				 return password_hash($this->validation_stripslashes($this->password),PASSWORD_DEFAULT);	
				}
		}
	public function getPassword(){
		return password_hash($this->validation_stripslashes($this->password),PASSWORD_DEFAULT) ;
		
		}
		
	public function throw_message($m){
		$this->message = $m;
		}
	public function get_throw_message(){
		return $this->message;
		}
		 	
	}
	
class form_new_account_validation extends php_key_function{
	
	private $php_key_function; 
	private $fullname;
	private $email;
	private $username;
	private $country;
	private $active;
	private $submit_date;
	private $update_date;
	
	
	public function __construct(){
	
	//$this->php_key_function = new php_key_function();
	//$this->country = new Country();
		
		}
	
	public function getFullname($fullname){
		
		return $this->fullname = $this->validation_stripslashes($fullname);
		
		}
		
	public function getEmail($email){
		
			return $this->email=$this->validation_stripslashes($email);
			
			} 
	
		public function getUsername($username){
			
				return $this->username = $this->validation_stripslashes($username);
				
				}
		public function getCountry(){
			return $this->country->getCountry();
			}
		
		public function getDateSubmit(){
			
			return $this->submit_date = date("Y-m-d");
			
			}
		public function getUpdateDate(){
			return $this->update_date = date("Y-m-d");
			}
		public function getActive($active = '0'){
		return $this->active = $active;
			}
		
	}
	
	

?>