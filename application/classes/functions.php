<?php

require_once('./application/config/database.php');

class FUNCTIONS{	

	private $conn;
	
	public function __construct(){
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function runQuery($sql){
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	
	public function register($user,$password){
		try{
			$new_password = password_hash($password, PASSWORD_DEFAULT);
			$stmt = $this->conn->prepare("INSERT INTO users(user_name,user_pass) 
		                                               VALUES(:uname, :upass)");								  
			$stmt->bindparam(":uname", $user);
			$stmt->bindparam(":upass", $new_password);										  
			$stmt->execute();	
			return $stmt;	
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}				
	}
	
	public function doLogin($user,$password){
		try{
			$stmt = $this->conn->prepare("SELECT user_id, user_name, user_pass FROM users WHERE user_name=:uname");
			$stmt->execute(array(':uname'=>$user));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() == 1){
				if(password_verify($password, $userRow['user_pass'])){
					$_SESSION['user_session'] = $userRow['user_id'];
					return true;
				}else{
					return false;
				}
			}
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	public function changePass($npassword,$uid){
		try{
			$password = password_hash($npassword, PASSWORD_DEFAULT);
			$stmt = $this->conn->prepare("UPDATE users SET user_pass=:upass WHERE user_id=:uid");								  
			$stmt->bindparam(":uid", $uid);
			$stmt->bindparam(":upass", $password);										  
			$stmt->execute();	
			return $stmt;	
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}				
	}
	
	public function is_loggedin(){
		if(isset($_SESSION['user_session'])){
			return true;
		}
	}
	
	public function redirect($url){
		header("Location: $url");
	}
	
	public function doLogout(){
		session_destroy();
		unset($_SESSION['user_session']);
		return true;
	}
	public function hour_min($minutes){
	   if($minutes <= 0) return '00 Hours 00 Minutes';
	else    
	   return sprintf("%02d",floor($minutes / 60)).' Hours '.sprintf("%02d",str_pad(($minutes % 60), 2, "0", STR_PAD_LEFT)). " Minutes";
	}
}
?>