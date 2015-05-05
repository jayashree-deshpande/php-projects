<?php
    ob_start();
    session_start();

    if(isset( $_SESSION['emp_Id'] ))
	{
		$message = 'Users is already logged in';
	}
    
  	/*** first check that both the username, password and form token have been sent ***/
	if(!isset($_POST['emp_Userid'], $_POST['emp_Password']))
	{
		$message = 'Please enter a valid username and password';
        echo "<a href='login.php'>retry login</a>"; 
	}
	
	/*** check the form token is valid ***/
	else if( $_POST['form_token'] != $_SESSION['form_token'])
	{
		$message = 'Invalid form submission';
        echo "<a href='login.php'>retry login</a>"; 
	}
	
	/*** check the username is the correct length ***/
	else if (strlen( $_POST['emp_Userid']) > 20 || strlen($_POST['emp_Userid']) < 4)
	{
		$message = 'Incorrect Length for Username';
        echo "<a href='login.php'>retry login</a>"; 
	}
	
	/*** check the password is the correct length ***/
	elseif (strlen( $_POST['emp_Password']) > 20 || strlen($_POST['emp_Password']) < 4)
	{
		$message = 'Incorrect Length for Password';
        echo "<a href='login.php'>retry login</a>"; 
	}
	
	/*** check the username has only alpha numeric characters ***/
	elseif (ctype_alnum($_POST['emp_Userid']) != true)
	{
		/*** if there is no match ***/
		$message = "Username must be alpha numeric";
        echo "<a href='login.php'>retry login</a>"; 
	}
	
	/*** check the password has only alpha numeric characters ***/
	elseif (ctype_alnum($_POST['emp_Password']) != true)
	{
			/*** if there is no match ***/
		$message = "Password must be alpha numeric";
        echo "<a href='login.php'>retry login</a>"; 
	}
	else
	{
		/*** if the data is valid, we can insert it into database ***/
		$emp_Userid = $_POST['emp_Userid'];
		$emp_Password = $_POST['emp_Password'];
		
		/*** now we can encrypt the password ***/
		//$emp_Password = sha1( $emp_Password );
    	try
        {
            
            $link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
                    or die('Could not connect: ' . mysql_error());

            mysql_select_db('flamboyance_db') or die('Could not select database');

            $query = "SELECT emp_Id,emp_Type FROM employeeInfo WHERE emp_Userid = 
                '$emp_Userid' AND emp_Password = '$emp_Password'";

            $result = mysql_query($query) or die('Query failed: ' . mysql_error());

            $row = mysql_fetch_array($result, MYSQL_ASSOC);

            if ($row == false)
            {
				$message = 'Login Failed';
                echo "<a href='login.php'>retry login</a>"; 
            }
            else
            {
               
                $_SESSION['emp_Id'] = $row['emp_Id'];
				$_SESSION['emp_Type'] = $row['emp_Type'];
				
				$message = 'You are now logged in';
            
                if(0 == strcmp($_SESSION['emp_Type'],'Administrator'))
				{
					header("location:admin_report.php");
				}
				else if(0 == strcmp($_SESSION['emp_Type'],'Manager'))
				{
					header("location:manager.php");
				}
				else if(0 == strcmp($_SESSION['emp_Type'],'Sales-Manager'))
				{
					header("location:sales_manager.php");
				}
            }
               
            mysql_free_result($result);

            // Closing connection
            mysql_close($link);

    	}
    	catch(Exception $e)
        {
			$message = "Login failed";
            echo "<a href='login.php'>retry login</a>"; 
        }
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>submit_login_info</title>
</head>

<body>
<p><?php echo $message; ?></p>
</body>
</html>
		
