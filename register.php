<?php
session_start();
require_once("./application/classes/functions.php");
require_once("./application/classes/csrf.php");
$m4 = new FUNCTIONS();
$csrf = new CSRF();

if($m4->is_loggedin()!=""){
	$m4->redirect('home.php');
}

$token_id = $csrf->get_token_id();
$token_value = $csrf->get_token($token_id);
 
$form_names = $csrf->form_names(array('user', 'password'), false);
 
 
if(isset($_POST[$form_names['user']], $_POST[$form_names['password']])) {
    if($csrf->check_valid('post')) {
            $user = $_POST[$form_names['user']];
            $password = $_POST[$form_names['password']];

            if($user===""){
            	$error = "<script>swal('Oh Snap!','Username field is empty','error');</script>";
            }elseif($password===""){
            	$error = "<script>swal('Oh Snap!','Password field is empty','error');</script>";
            }else{
				try{
					$stmt = $m4->runQuery("SELECT user_name FROM users WHERE user_name=:uname");
					$stmt->execute(array(':uname'=>$user));
					$row=$stmt->fetch(PDO::FETCH_ASSOC);
						
					if($row['user_name']==$user) {
						$error = "<script>swal('Oh Snap!','Username already taken','error');</script>";
					}else{
						if($m4->register($user,$password)){
							$m4->redirect('./');
						}else{
							$error = "<script>swal('Oh Snap!','Something went wrong','error');</script>";
						}	
					}
				}
				catch(PDOException $e){
					echo $e->getMessage();
				}
            }

    }
    $form_names = $csrf->form_names(array('user', 'password'), true);
}

?>
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
    <title>M4VPN</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css"/>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" type="text/css"/>
    <link rel="stylesheet" href="assets/css/main.css" type="text/css"/>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>

<body>
	<?php
	if(isset($error)){
		echo $error;
	}
	?>
	<div id="login-container">
	    <div id="login">
	    	<hr/>
	        <h3><a href="./" style="text-decoration:none;color:black;">M4VPN</a></h3>
	        <h5>Fill up the following details to register.</h5>
	        <hr/>
	        <form id="login-form" method="post" class="form">
	        	<input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
	            <div class="form-group">
	                <label>Username</label>
	                <input type="text" class="form-control" name="<?= $form_names['user']; ?>" placeholder="Username">
	            </div>
	            <div class="form-group">
	                <label>Password</label>
	                <input type="password" class="form-control" name="<?= $form_names['password']; ?>" placeholder="Password">
	            </div>
	            <div class="form-group">
	                <input type="submit" name="login-btn" class="btn btn-primary btn-block" value="Register" />
	            </div>
	        </form>
	    </div>
	    <a href="./" id="signup-btn" class="btn btn-lg btn-block">Already have an Account?</a>
	</div>

	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>