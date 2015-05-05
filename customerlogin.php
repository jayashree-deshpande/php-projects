<?php
/*** begin our session ***/
ob_start();
session_start();

/*** set a form token ***/
$form_token = md5( uniqid('auth', true) );

/*** set the session form token ***/
$_SESSION['form_token'] = $form_token;
$_SESSION['timeout'] = time();

?>

<html>
<head>
	<meta content="text/html; charset=iso-8859-1" />
	<title>Customer Login</title>
	<link href="login.css" rel="stylesheet" type="text/css" />
</head>

<body id= "login-wrapper" >
	<div id="login-box" >
	<form id="Login" method="post" class="form1" style="float:left" action="submit_customer_loginInfo.php">
        <H2>Login</H2><br />
        <div id="login-box-name" style="margin-top:20px;">User ID:</div>
        <div id="login-box-field" style="margin-top:20px;">
        	<input id="cust_user_id" name="cust_user_id" class="Textinput" type="text"  formmethod="post" /> 
       	</div><br />
        <div id="login-box-name">Password:</div>
        <div id="login-box-field">
        	<input id="cust_password" name= "cust_password" class="Textinput" type="text"  formmethod="post" /> 
		</div>
        
        <div id="link"><a href="create_new_account.php">create new account</a></div>
    	<br />
        <input type="hidden" name="form_token" value="<?php echo $form_token; ?>" />
        <input id="Submit" type="submit" value="login" />
      
	</form>
    </div>
</body>
</html>
