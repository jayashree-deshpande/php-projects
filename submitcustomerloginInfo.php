<?php
    ob_start();
    session_start();

	if(isset( $_SESSION['cust_user_id'] ))
	{
		$message = 'User is already logged in';
	}
    
  	/*** first check that both the username, password and form token have been sent ***/
	if(!isset($_POST['cust_user_id'], $_POST['cust_password']))
	{
		$message = 'Please enter a valid username and password';
        echo "<a href='customer_login.php'>retry login</a>"; 
	}
	
	/*** check the form token is valid ***/
	else if( $_POST['form_token'] != $_SESSION['form_token'])
	{
		$message = 'Invalid form submission';
        echo "<a href='customer_login.php'>retry login</a>"; 
	}
	
	/*** check the username is the correct length ***/
	else if (strlen( $_POST['cust_user_id']) > 20 || strlen($_POST['cust_user_id']) <= 0)
	{
		$message = 'Incorrect Length for Username';
        echo "<a href='customer_login.php'>retry login</a>"; 
	}
	
	/*** check the password is the correct length ***/
	elseif (strlen( $_POST['cust_password']) > 20 || strlen($_POST['cust_password']) <= 0)
	{
		$message = 'Incorrect Length for Password';
        echo "<a href='customer_login.php'>retry login</a>"; 
	}
	
	/*** check the username has only alpha numeric characters ***/
	elseif (ctype_alnum($_POST['cust_user_id']) != true)
	{
		/*** if there is no match ***/
		$message = "Username must be alpha numeric";
        echo "<a href='customer_login.php'>retry login</a>"; 
	}
	
	/*** check the password has only alpha numeric characters ***/
	elseif (ctype_alnum($_POST['cust_password']) != true)
	{
			/*** if there is no match ***/
		$message = "Password must be alpha numeric";
        echo "<a href='customer_login.php'>retry login</a>"; 
	}
	else
	{
		/*** if the data is valid, we can insert it into database ***/
		$cust_user_id = $_POST['cust_user_id'];
		$cust_password = $_POST['cust_password'];
		
		/*** now we can encrypt the password ***/
		//$emp_Password = sha1( $emp_Password );
    	try
        {
            
            $link = mysql_connect('localhost', 'root', 'mysql_jdd')
                    or die('Could not connect: ' . mysql_error());

            mysql_select_db('flamboyance_db') or die('Could not select database');

			$query = "SELECT cust_id,cust_first_name,cust_last_name,cust_user_id,cust_password,last_session_id FROM 		
				customerInfo where cust_user_id='$cust_user_id' AND cust_password = '$cust_password' ";

            $result = mysql_query($query) or die('Query failed: ' . mysql_error());

            $row = mysql_fetch_array($result, MYSQL_ASSOC);

            if ($row == false)
            {
				$message = 'Login Failed';
                echo "<a href='customer_login.php'>retry login</a>"; 
            }
            else
            {
				print_r('inside flamboyance'.$row['cust_id']);
				if('' == $row['last_session_id'] && !isset($_SESSION['cart']))
				{
					$_SESSION['cart'] = '' ;
	
					try
					{
						$query = "INSERT INTO sessionInfo(session_id,session_cart) VALUES (session_id(),'') ";
		
						mysql_query($query) or die('Query failed: ' . mysql_error());
					}
					catch(Exception $e)
					{
						/*** check if the username already exists ***/
						if( $e->getCode() == 23000)
						{
							echo 'Cart already exists';
						}
						else
						{
							echo 'Error is = '.$e->getMessage();
						}
					}
					try
					{
						$query = "UPDATE customerInfo SET last_session_id=session_id() 
							WHERE where cust_user_id='$cust_user_id' AND cust_password = '$cust_password' ";
			   
						mysql_query($query) or die('Query failed: ' . mysql_error());
				
						mysql_query("COMMIT"); 
						
					}
					catch(Exception $e)
					{
						echo 'Error is = '.$e->getMessage();
					}
				}
				else if('' != $row['last_session_id'] && isset($_SESSION['cart']))
				{
					if($_SESSION['cart'] = '')
					{
						try
						{
							$query = "UPDATE sessionInfo SET session_id=session_id(),session_cart= ".$_SESSION['cart']."
								WHERE session_id= ".$row['last_session_id']."  ";
	   
							mysql_query($query) or die('Query failed: ' . mysql_error());
		
							mysql_query("COMMIT"); 
				
						}
						catch(Exception $e)
						{
							echo 'Error is = '.$e->getMessage();
						}
						try
						{
							$query = "UPDATE customerInfo SET last_session_id=session_id() 
								WHERE where cust_user_id='$cust_user_id' AND cust_password = '$cust_password' ";
				   
							mysql_query($query) or die('Query failed: ' . mysql_error());
					
							mysql_query("COMMIT"); 
							
						}
						catch(Exception $e)
						{
							echo 'Error is = '.$e->getMessage();
						}
					}
					else
					{
						try
						{
							$cart = $_SESSION['cart'];
							$query = "UPDATE sessionInfo SET session_id=session_id(),session_cart= ".$_SESSION['cart']. "
								WHERE session_id=".$row['last_session_id']." ";
	   
							mysql_query($query) or die('Query failed: ' . mysql_error());
		
							mysql_query("COMMIT"); 
				
						}
						catch(Exception $e)
						{
							echo 'Error is = '.$e->getMessage();
						}
						try
						{
							$query = "UPDATE customerInfo SET last_session_id=session_id() 
								WHERE where cust_user_id='$cust_user_id' AND cust_password = '$cust_password' ";
				   
							mysql_query($query) or die('Query failed: ' . mysql_error());
					
							mysql_query("COMMIT"); 
							
						}
						catch(Exception $e)
						{
							echo 'Error is = '.$e->getMessage();
						}
					}
					
				}
				$_SESSION['cust_user_id'] = $row['cust_user_id'];
				$_SESSION['cust_password'] = $row['cust_password'];
				$_SESSION['cust_id'] = $row['cust_id'];
				echo"after storing into session ".$_SESSION['cust_id'];
				
				$message = 'You are now logged in';
				$_SESSION['login_flag']=true;
				
				header("location:customer_page.php");
				
			}
               
            mysql_free_result($result);

            // Closing connection
            mysql_close($link);

    	}
    	catch(Exception $e)
        {
			$message = "Login failed";
            echo "<a href='customer_login.php'>retry login</a>"; 
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
		
