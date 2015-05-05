<?php    
    ob_start();
    session_start();
	if ($_SESSION['timeout'] + 10*60 < time()) 
  	{	
		session_start();
		session_destroy();
		header("location:login.php");
		
    } 
    else 
	{
		$_SESSION['timeout'] = time();	
	}
	
	function insertNewCutomer()
	{
	/*** if the data is valid, we can insert it into database ***/
		$cust_first_name = $_POST['cust_first_name'];
		$cust_last_name = $_POST['cust_last_name'];
		$cust_date_b = $_POST['cust_date_b'];
		$cust_user_id = $_POST['cust_user_id'];
		$cust_password = $_POST['cust_password'];
	
		if($cust_first_name == null || $cust_first_name == '')
		{
			$message = 'Please enter first name' ;
		}
	
		if($cust_last_name == null || $cust_last_name == '')
		{
			$message = 'Please enter last name' ;
		}

		/*$reg = /^(\d{4})\-(\d{1,2})\-(\d{1,2})$/
		if(($cust_date_b == null)|| (!$cust_date_b.match($reg)))
		{
			$message = "";
		}*/
	
		if($cust_user_id == null)
		{
			$message = 'Please enter valid user id';
		}
		
		if($cust_password == null)
		{
			$message = 'Please enter valid password ';
		}
	
		try
		{
			$link = mysql_connect('localhost', 'root', 'mysql_jdd')
				or die('Could not connect: '  .  mysql_error());
	
			mysql_select_db('flamboyance_db') or die('Could not select database');
	
			/*** prepare the insert ***/
			$query ="INSERT INTO custInfo(cust_first_name,cust_last_name,cust_date_b,cust_user_id,cust_password ) 
				VALUES('$cust_first_name','$cust_last_name','$cust_date_b','$cust_user_id','$cust_password')";
		
			$result = mysql_query($query) or die('Query failed:'.mysql_error());
	
			/*** if all is done, say thanks ***/
			$message = 'New user added';
			mysql_close($link);
			$_SESSION['cust_user_id'] = $cust_user_id;
			header("location:customer_page.php");
			
		}
		catch(Exception $e)
		{
			/*** check if the username already exists ***/
			if( $e->getCode() == 23000)
			{
				$message = 'Username already exists';
			}
			else
			{
				$message = 'Error is = '.$e->getMessage();
				/*** if we are here, something has gone wrong with the database ***/
				//$message = 'We are unable to process your request. Please try again later"';
			}
		}
	}
	
	if(isset($_POST['submit']))
	{
		print('outside if');
		print($_POST['submit']);
		if(0 == strcmp('Submit',$_POST['submit']))
		{
			print('inside if');
			insertNewCutomer();
		}
	}
	
	
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>New User Form</title>
<link href="new_account.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" >

function validateNewAccInfo()
{
	var cust_first_name = document.new_account_form.cust_first_name.value;
	if(cust_first_name === null || cust_first_name === '')
	{
		document.getElementById('cust_first_name_error').style.display = '';
		document.new_account_form.cust_first_name.focus();
		return false;
	}
	else
	{
		document.getElementById('cust_first_name_error').style.display = "none";
	}
	
	var cust_last_name = document.new_account_form.cust_last_name.value;
	if(cust_last_name === null || cust_last_name === '')
	{
		document.getElementById('cust_last_name_error').style.display = '';
		document.new_account_form.cust_last_name.focus();
		return false;
	}
	else
	{
		document.getElementById('cust_last_name_error').style.display = "none";
	}
	
	var cust_date_b = document.new_account_form.cust_date_b.value;
	reg = /^(\d{4})\-(\d{1,2})\-(\d{1,2})$/
	if(!cust_date_b.match(reg) || cust_date_b === null)
	{
		document.getElementById('cust_date_b_error').style.display = '';
		document.new_account_form.cust_date_b.focus();
		return false;
	}
	else
	{
		document.getElementById('cust_date_b_error').style.display = "none";
	}
	
	var cust_user_id = document.new_account_form.cust_user_id.value;
	if(cust_user_id === null || cust_user_id === '')
	{
		document.getElementById('cust_user_id_error').style.display = '';
		document.new_account_form.cust_user_id.focus();
		return false;
	}
	else
	{
		document.getElementById('cust_user_id_error').style.display = "none";
	}
	
	var cust_password = document.new_account_form.cust_password.value;
	if(cust_password === null || cust_password === '')
	{
		document.getElementById('cust_password_error').style.display = '';
		document.new_account_form.cust_password.focus();
		return false;
	}
	else
	{
		document.getElementById('cust_password_error').style.display = "none";
	}
	
	return true;
}
</script>
</head>

<body id= "login-wrapper" >
	<div id="login-box" >
	<form id="Newaccount" method="post" class="form1" style="float:left" action="create_new_account.php" 
    	name="new_account_form" onsubmit="return validateNewAccInfo();">
        <H2>User Information Form</H2><br />
        
        <div id="login-box-name" style="margin-top:20px;">First Name:</div>
        <div id="login-box-field" style="margin-top:20px;">
        	<input id="cust_first_name" name="cust_first_name" class="Textinput" type="text" /> 
            <div id="cust_first_name_error" style="color:darkred;display:none;" >
            	Please ensure first name field is filled correctly
            </div>
       	</div><br />
        
        <div id="login-box-name" >Last Name:</div>
        <div id="login-box-field">
        	<input id="cust_last_name" name= "cust_last_name" class="Textinput" type="text"  formmethod="post" /> 
            <div id="cust_last_name_error" style="color:darkred;display:none;" >
            	Please ensure last name field is filled correctly
            </div>
		</div><br />
        
        <div id="login-box-name" >Date of Birth</div>
        <div id="login-box-field">
        	<input id="cust_date_b" name= "cust_date_b" class="Textinput" type="text"  formmethod="post" /> 
            <div id="cust_date_b_error" style="color:darkred;display:none;" >
            	Please ensure date of birth field is filled correctly
            </div>
		</div><br />
        <br />
        
		<div id="login-box-name" >User Id</div>
        <div id="login-box-field" >
        	<input id="cust_user_id" name="cust_user_id" class="Textinput" type="text" /> 
            <div id="cust_user_id_error" style="color:darkred;display:none;" >
            	Please ensure user id field is filled correctly
			</div>
       	</div><br />
        
        <div id="login-box-name" >Password</div>
        <div id="login-box-field" >
        	<input id="cust_password" name="cust_password" class="Textinput" type="text" /> 
            <div id="cust_password_error" style="color:darkred;display:none;" >
            	Please ensure password field is filled correctly
			</div>
       	</div><br />
        
        <input id="Submit" type="submit" name="submit" value="Submit" />
      
	</form>
    </div>
</body>
</html>
