<?php
session_start();
require_once("./application/classes/functions.php");
$m4 = new FUNCTIONS();

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
	        <div align="left">
			<table class="table table-borderless">
			<tbody>
				<tr>
					<td style="width:30%;"><strong>Username:</strong></td>
					<td style="width:70%;"><?=$userRow['user_name']?></td>
				</tr>
				<tr>
					<td style="width:30%;"><strong>Password:</strong></td>
					<td style="width:70%;"><a href="changepassword.php" style="text-decoration:none;">Change Password</a></td>
				</tr>
				<tr>
					<td style="width:30%;"><strong>Duration:</strong></td>
					<td style="width:70%;"><?=$m4->hour_min($userRow['user_duration'])?></td>
				</tr>
			</tbody>
			</table>
			</div>
	    </div>
	    <form method="post">
	    	<button type="submit" name="logout" id="signup-btn" class="btn btn-lg btn-block">Logout</a>
	    </button>
	</div>

	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>