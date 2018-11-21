<?php
session_start();
require_once("./application/classes/functions.php");
require_once("./application/classes/csrf.php");
$m4 = new FUNCTIONS();
$csrf = new CSRF();

if($m4->is_loggedin()==""){
	$m4->redirect('./');
}
$user_id = $_SESSION['user_session'];
$stmt = $m4->runQuery("SELECT * FROM users WHERE user_id=:user_id");
$stmt->execute(array(":user_id"=>$user_id));
$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['logout'])){
	$m4->doLogout();
	$m4->redirect('./');
}
$token_id = $csrf->get_token_id();
$token_value = $csrf->get_token($token_id);
 
$form_names = $csrf->form_names(array('npassword', 'cpassword'), false);
 
 
if(isset($_POST[$form_names['npassword']], $_POST[$form_names['cpassword']])) {
    if($csrf->check_valid('post')) {
    		$uid = strip_tags(trim($user_id));
            $npassword = strip_tags(trim($_POST[$form_names['npassword']]));
            $cpassword = strip_tags(trim($_POST[$form_names['cpassword']]));

            if($npassword===""){
            	$error = "<script>swal('Oh Snap!','Password field is empty','error');</script>";
            }elseif($cpassword===""){
            	$error = "<script>swal('Oh Snap!','Please confirm your password','error');</script>";
            }elseif($npassword!==$cpassword){
            	$error = "<script>swal('Oh Snap!','Please reconfirm your password','info');</script>";
            }else{
				if($m4->changePass($npassword,$uid)){
					$m4->redirect('home.php');
				}else{
					$error = "<script>swal('Oh Snap!','Something went wrong','error');</script>";
				}	
            }

    }
    $form_names = $csrf->form_names(array('npassword', 'cpassword'), true);
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
	        <h5>Fast and Reliable Connection</h5>
	        <hr/>
	        <form id="login-form" method="post" class="form">
	        	<input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
	            <div class="form-group">
	                <label>New Password</label>
	                <input type="password" class="form-control" name="<?= $form_names['npassword']; ?>" placeholder="New Password">
	            </div>
	            <div class="form-group">
	                <label>Confirm Password</label>
	                <input type="password" class="form-control" name="<?= $form_names['cpassword']; ?>" placeholder="Confirm Password">
	            </div>
	            <div class="form-group">
	                <input type="submit" name="login-btn" class="btn btn-primary btn-block" value="Change Password" />
	            </div>
	        </form>

	    </div>
	    <form method="post">
	    	<button type="submit" name="logout" id="signup-btn" class="btn btn-lg btn-block">Logout</a>
	    </button>
	</div>

	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>