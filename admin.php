<?php
    ob_start();
	session_start();
	if ($_SESSION['timeout'] + 10*60 < time()) 
  	{	
		session_start();
		session_destroy();
		header("location:employee_login.php");
        return;
    } 
    else 
	{
		$_SESSION['timeout'] = time();	
	}
	
	function adminShowCreate()
	{	
		echo"<html xmlns='http://www.w3.org/1999/xhtml'>
				<head>
				<script type='text/javascript'>
					function validateAdminCreate()
					{
						var emp_Userid = document.adminCreate.emp_Userid.value;
						if(emp_Userid === null || emp_Userid === '')
						{
							document.getElementById('emp_Userid_error').style.display = '';
							document.adminCreate.emp_Userid.focus();
							return false;
						}
						else
						{
							document.getElementById('emp_Userid_error').style.display = 'none';
						}
						
						var emp_Password = document.adminCreate.emp_Password.value;
						if(emp_Password === null || emp_Password === '' )
						{
							document.getElementById('emp_Password_error').style.display = '';
							document.adminCreate.emp_Password.focus();
							return false;
						}
						else
						{
							document.getElementById('emp_Password_error').style.display = 'none';
						}
						
						var emp_FirstName = document.adminCreate.emp_FirstName.value;
						if(emp_FirstName === null || emp_FirstName === '' )
						{
							document.getElementById('emp_FirstName_error').style.display = '';
							document.adminCreate.emp_FirstName.focus();
							return false;
						}
						else
						{
							document.getElementById('emp_FirstName_error').style.display = 'none';
						}
						
						var emp_LastName = document.adminCreate.emp_LastName.value;
						if(emp_LastName === null || emp_LastName === '' )
						{
							document.getElementById('emp_LastName_error').style.display = '';
							document.adminCreate.emp_LastName.focus();
							return false;
						}
						else
						{
							document.getElementById('emp_LastName_error').style.display = 'none';
						}
						
						var emp_Type = document.getElementById('emp_Type').value;
						if(emp_Type === null || emp_Type === '' || emp_Type.selected === false )
                        {
							document.getElementById('emp_Type_error').style.display = '';
							document.adminCreate.emp_Type.focus();
							return false;
						}
						else
						{
							document.getElementById('emp_Type_error').style.display = 'none';
						}
						
						var emp_Age = document.adminCreate.emp_Age.value;
						if(emp_Age === null || emp_Age === '' || isNaN(emp_Age))
						{
							document.getElementById('emp_Age_error').style.display = '';
							document.adminCreate.emp_Age.focus();
							return false;
						}
						else
						{
							document.getElementById('emp_Age_error').style.display = 'none';
						}
						
						var emp_Income = document.adminCreate.emp_Income.value;
						if(emp_Income === null || emp_Income === '' || isNaN(emp_Income))
						{
							document.getElementById('emp_Income_error').style.display = '';
							document.adminCreate.emp_Income.focus();
							return false;
						}
						else
						{
							document.getElementById('emp_Income_error').style.display = 'none';
                        }
                        return true;
					}
				</script>
				<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
				<title>Create Form</title>
				<link href='admin.css' rel='stylesheet' type='text/css' />
				</head>

				<body id='Form-wrapper' >
			
				<div id='Create' >
					<form action=".$_SERVER['PHP_SELF']." method='post' id='Create_form' class='form2' name='adminCreate'
						style='float:left' onsubmit='return validateAdminCreate();' >
						
						<H2>Create Form</H2><br />
						
            			<div id='Box-name'>Login Id* :</div>
						<div id='Box-field' >
							<input id='emp_Userid' name='emp_Userid' class='Box-input' type='text' /> <br />
							<div id='emp_Userid_error' style='display:none;position:relative;color:darkred;'>
								Please fill login id field correctly. <br />
							</div>
						</div><br />
						
						<div id='Box-name'>Employee Password* :</div>
						<div id='Box-field' >
							<input id='emp_Password' name='emp_Password' class='Box-input' type='text' /><br />
							<div id='emp_Password_error' style='display:none;position:relative;color:darkred;'>
								Please fill employee password field correctly. <br />
							</div>
						</div><br />
						
						<div id='Box-name'>First Name* :</div>
						<div id='Box-field' >
							<input id='emp_FirstName' name='emp_FirstName' class='Box-input' type='text' /> <br />
							<div id='emp_FirstName_error' style='display:none;position:relative;color:darkred;'>
								Please fill first name field correctly. <br />
							</div>
						</div><br />
						
						<div id='Box-name'>Last Name* :</div>
						<div id='Box-field' >
							<input id='emp_LastName' name='emp_LastName' class='Box-input' type='text' /> <br />
							<div id='emp_LastName_error' style='display:none;position:relative;color:darkred;'>
								Please fill last name field correctly. <br />
							</div>
						</div><br />
						
                		<div id='Box-name'>Employee Type* :</div>
						<div id='Box-field' >
							<select id='emp_Type' name='emp_Type' class='Box-input' >
								<option value='Manager'>Manager</option>
								<option value='Sales-Manager'>Sales-Manager</option>
								<option value='Administrator'>Administrator</option>
							</select> 
						<div id='emp_Type_error' style='display:none;color:red;postion:relative;'>
							Please make sure you have selected employee type.
						</div>
						</div><br />
						
						<div id='Box-name'>Employee Age* :</div>
						<div id='Box-field' >
                			<input id='emp_Age' name='emp_Age' class='Box-input' type='text' /><br />
							<div id='emp_Age_error' style='display:none;position:relative;color:darkred;'>
								Please fill employee age field correctly. <br />
							</div>
						</div><br />
						
						<div id='Box-name'>Employee Income* :</div>
						<div id='Box-field' >
							<input id='emp_Income' name='emp_Income' class='Box-input' type='text' /><br />
							<div id='emp_Income_error' style='display:none;position:relative;color:darkred;'>
								Please fill employee income field correctly. <br />
							</div>
                		</div><br />
						<br />
						<br />
          				<input type='submit' name='submit' value='Submit Create' class='Submit' style='position:absolute;float:right;top:75%;'/><br />
                        </form><br />
                        <br />
                        <div style='position:absolute;float=left;top:75%;'>
                        <form style='float:left;'  action=".$_SERVER['PHP_SELF']."  method='post' >
                            <input type='submit' name='submit' value='Go Back' class='Submit' style='color:darkred;' />
                        </form>
                        </div>
				</div>
				</body>
			</html>";
	}
	
	function adminSubmitCreate()
	{
		/*** if the data is valid, we can insert it into database ***/
		$emp_Userid = $_POST['emp_Userid'];
		$emp_Password = $_POST['emp_Password'];
		$emp_LastName = $_POST['emp_LastName'];
		$emp_FirstName = $_POST['emp_FirstName'];
		$emp_Type = $_POST['emp_Type'];
		$emp_Age = $_POST['emp_Age'];
		$emp_Income = $_POST['emp_Income'];

        try
        {
            $link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
                or die('Could not connect: ' . mysql_error());

            mysql_select_db('flamboyance_db') or die('Could not select database');

            $query = "INSERT INTO                  					     					 
				employeeInfo(emp_Userid,emp_Password,emp_LastName,emp_FirstName,emp_Type,emp_Age,emp_Income) VALUES     	
					('$emp_Userid','$emp_Password','$emp_LastName','$emp_FirstName','$emp_Type', $emp_Age,$emp_Income)";

            mysql_query($query);// or die('Query failed: ' . mysql_error());
            
            $message = 'New user added';

            // Closing connection
            mysql_close($link);
        }
		catch(Exception $e)
		{
			/*** check if the username already exists ***/
			if( $e->getCode() == 23000)
			{
				$message = 'Username already exists';
                echo "<a href='admin_report.php'>goback</a>"; 
            }
			else
			{
				$message = 'Error is = '.$e->getMessage();
                echo "<a href='admin_report.php'>goback</a>"; 
			}
		}
}

	function adminShowUpdate($emp_Id)
	{
		try
        {
            $link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
                or die('Could not connect: ' . mysql_error());

            mysql_select_db('flamboyance_db') or die('Could not select database');

            $query = "SELECT emp_Userid,emp_Password,emp_LastName,emp_FirstName,emp_Type,emp_Age,emp_Income FROM employeeInfo 	 						WHERE emp_Id=$emp_Id";

            //var_dump(query);
            $result = mysql_query($query);// or die('Query failed: ' . mysql_error());

            $row= mysql_fetch_array($result, MYSQL_ASSOC); 

            if($row == false)
            {
                $message = 'User not found';
                echo "<a href='admin_report.php'>goback</a>"; 
            }
            else
            {
				echo"<html xmlns='http://www.w3.org/1999/xhtml'>
				<head>
				<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
				<title>Update Form</title>
				<link href='admin.css' rel='stylesheet' type='text/css' />
				<script type='text/javascript' >
				function validateAdminUpdate()
				{	
					var emp_Userid = document.adminUpdate.emp_Userid.value;
					if(emp_Userid === null || emp_Userid === '')
                    {
						document.getElementById('emp_Userid_update_error').style.display = '';
						document.adminUpdate.emp_Userid.focus();
						return false;
					}
					else
					{
						document.getElementById('emp_Userid_update_error').style.display = 'none';
					}
					
					var emp_Password = document.adminUpdate.emp_Password.value;
					if(emp_Password === null || emp_Password === '' )
					{
						document.getElementById('emp_Password_update_error').style.display = '';
						document.adminUpdate.emp_Password.focus();
						return false;
					}
					else
					{
						document.getElementById('emp_Password_update_error').style.display = 'none';
					}
					
					var emp_FirstName = document.adminUpdate.emp_FirstName.value;
					if(emp_FirstName === null || emp_FirstName === '' )
					{
						document.getElementById('emp_FirstName_update_error').style.display = '';
						document.adminUpdate.emp_FirstName.focus();
						return false;
					}
					else
					{
						document.getElementById('emp_FirstName_update_error').style.display = 'none';
					}
					
					var emp_LastName = document.adminUpdate.emp_LastName.value;
					if(emp_LastName === null || emp_LastName === '' )
					{
						document.getElementById('emp_LastName_update_error').style.display = '';
						document.adminUpdate.emp_LastName.focus();
						return false;
					}
					else
					{
						document.getElementById('emp_LastName_update_error').style.display = 'none';
					}
					
					var emp_Type = document.getElementById('emp_Type').value;
					if(emp_Type === null || emp_Type === '' || emp_Type.selected === false )
					{
						document.getElementById('emp_Type_update_error').style.display = '';
						document.adminUpdate.emp_Type.focus();
						return false;
					}
					else
					{
						document.getElementById('emp_Type_update_error').style.display = 'none';
					}

			
				    var emp_Age = document.adminUpdate.emp_Age.value;
					if(emp_Age === null || emp_Age === '' || isNaN(emp_Age))
                    {
                        document.getElementById('emp_Age_update_error').style.display = '';
                        document.adminUpdate.emp_Age.focus();
                        return false;
                    }
					else
                    {
                        document.getElementById('emp_Age_update_error').style.display = 'none';
                    }
						
					var emp_Income = document.adminUpdate.emp_Income.value;
				    if(emp_Income === null || emp_Income === '' || isNaN(emp_Income))
                    {
                        document.getElementById('emp_Income_update_error').style.display = '';
                        document.adminUpdate.emp_Income.focus();
                        return false;
                    }
					else
					{
					    document.getElementById('emp_Income_update_error').style.display = 'none';
                    }
			        return true;
				}

				</script>
				</head>

				<body id='Form-wrapper' >
			
				<div id='Create' >
                	<form action=".$_SERVER['PHP_SELF']." method='post' name='adminUpdate' class='form2' style='float:left' onsubmit='return validateAdminUpdate()' >
						<H2>Update Form</H2><br />
                		<input type='hidden' name='emp_Id' value=$emp_Id>
					
            			<div id='Box-name'>Login Id* :</div>
						<div id='Box-field' >
							<input id='emp_Userid' name='emp_Userid' class='Box-input' type='text' value=".$row['emp_Userid']." 	 								/> <br />
							<div id='emp_Userid_update_error' style='display:none;position:relative;color:darkred;' >
								Make sure login-id is filled correctly. <br />
							</div>
						</div><br />
						
                		<div id='Box-name'>Employee Password* :</div>
						<div id='Box-field' >
							<input id='emp_Password' name='emp_Password' class='Box-input' type='text' 
								value=".$row['emp_Password']." /><br />
							<div id='emp_Password_update_error' style='display:none;position:relative;color:darkred;' >
								Make sure password field is filled correctly. <br />
							</div>
						</div><br />
						
                    	<div id='Box-name'>First Name* :</div>
						<div id='Box-field' >
							<input id='emp_FirstName' name='emp_FirstName' class='Box-input' type='text' 
								value=".$row['emp_FirstName']." /> <br />
							<div id='emp_FirstName_update_error' style='display:none;position:relative;color:darkred;' >
								Make sure first name field is filled correctly. <br />
							</div>
						</div><br />
						
                    	<div id='Box-name'>Last Name* :</div>
						<div id='Box-field' >
							<input id='emp_LastName' name='emp_LastName' class='Box-input' type='text' 
								value=".$row['emp_LastName']." /> <br />
							<div id='emp_LastName_update_error' style='display:none;position:relative;color:darkred;' >
								Make sure last name field is filled correctly. <br />
							</div>
						</div><br />
   
						<div id='Box-name'>Employee Type* :</div>
							<div id='Box-field' >
								<select id='emp_Type' name='emp_Type' class='Box-input' >
									<option value='Manager' ";
									if (0 == strcmp('Manager',$row['emp_Type'] ))
									{
										echo "selected='selected' ";
									}
									echo ">Manager</option>
									
									<option value='Sales-Manager' ";
									if (0 == strcmp('Sales-Manager',$row['emp_Type'] ))
									{
										echo "selected='selected' ";
									}
									echo ">Sales-Manager</option>
									
									<option value='Administrator' ";
									if (0 == strcmp('Administrator',$row['emp_Type'] ))
									{
										echo "selected='selected' ";
									}
									echo ">Administrator</option>
								</select> 
                        		<div id='emp_Type_update_error' style='display:none;position:relative;color:darkred;'>
								Please fill employee age field correctly. <br />
							    </div>
		        
                            </div><br />
							
						<div id='Box-name'>Employee Age* :</div>
						<div id='Box-field' >
                			<input id='emp_Age' name='emp_Age' class='Box-input' type='text' value=".$row['emp_Age']." /><br />
							<div id='emp_Age_update_error' style='display:none;position:relative;color:darkred;'>
								Please fill employee age field correctly. <br />
							</div>
						</div><br />
						
						<div id='Box-name'>Employee Income* :</div>
						<div id='Box-field' >
							<input id='emp_Income' name='emp_Income' class='Box-input' type='text' value=".$row['emp_Income']." /><br />
							<div id='emp_Income_update_error' style='display:none;position:relative;color:darkred;'>
								Please fill employee income field correctly. <br />
							</div>
                		</div><br />
                        <br />
                        <br />
                        <input type='submit' name='submit' value='Submit Update' class='Submit' style='position:absolute;float:right;top:75%;' 
							onclick='validateAdminUpdate();' /><br />
                    </form><br/ >
                    <div style='float:left;position:absolute;top:75%;'>                        
                        <form style='float:left;' action=".$_SERVER['PHP_SELF']."  method='post' >
                            <input type='submit' name='submit' value='Go Back' class='Submit' style='color:darkred;' />
                        </form>
                    </div>
				</div>
				</body>
				</html>";

            }

            mysql_free_result($result);

            // Closing connection
            mysql_close($link);
        }
		catch(Exception $e)
		{
			$message = 'We are unable to process your request. Please try again later"';
            echo "<a href='admin_report.php'>go back</a>"; 
        }
	}
 	
	function adminSubmitUpdate($emp_Id)
	{
		/*** if the data is valid, we can insert it into database ***/
		$emp_Userid = $_POST['emp_Userid'];
		$emp_Password = $_POST['emp_Password'];
		$emp_LastName = $_POST['emp_LastName'];
		$emp_FirstName = $_POST['emp_FirstName'];
		$emp_Type = $_POST['emp_Type'];
		$emp_Age = $_POST['emp_Age'];
		$emp_Income = $_POST['emp_Income'];
	
		/*** now we can encrypt the password ***/
		//$emp_Password = sha1( $emp_Password );

        try
		{
            $link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
                or die('Could not connect: ' . mysql_error());

            mysql_select_db('flamboyance_db') or die('Could not select database');

		$query = "UPDATE employeeInfo SET emp_Userid='$emp_Userid',emp_Password ='$emp_Password',emp_LastName='$emp_LastName',emp_FirstName='$emp_FirstName',emp_Type='$emp_Type',emp_Age=$emp_Age,emp_Income=$emp_Income WHERE emp_Id=$emp_Id";
	
            mysql_query($query);// or die('Query failed: ' . mysql_error());
	
			/*** if all is done, say thanks ***/
			$message = 'user updated';

            mysql_query("COMMIT"); 

            // Closing connection
            mysql_close($link);

        }
		catch(Exception $e)
		{
			/*** check if the username failed ***/
			$message = 'user update failed';
            echo "<a href='admin_report.php'>go back</a>";		
		}
	}
	
	function adminDelete($emp_Id)
	{
		try
        {
            $link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
                or die('Could not connect: ' . mysql_error());

            mysql_select_db('flamboyance_db') or die('Could not select database');

            $query = "DELETE FROM employeeInfo WHERE emp_Id = $emp_Id";

            mysql_query($query);// or die('Query failed: ' . mysql_error());

            // Closing connection
            mysql_close($link);

        }
		catch(Exception $e)
		{
			$message = 'Delete failed';
            echo "<a href='admin_report.php'>go back</a>";		
        }
	}

	if(isset($_POST['submit']))
	{
		if(0 == strcmp('Delete',$_POST['submit']))
		{
			adminDelete($_POST['emp_id']);
		}
		
		if(0 == strcmp('Update', $_POST['submit']))		
		{	
			adminShowUpdate($_POST['emp_id']);
			return;
			
		}
		if(0 == strcmp('Submit Update', $_POST['submit']))		
		{	
			adminSubmitUpdate($_POST['emp_Id']);
		}
		if(0 == strcmp('Create', $_POST['submit']))		
		{	
			adminShowCreate();
			return;
		}
		if(0 == strcmp('Submit Create', $_POST['submit']))		
		{	
			adminSubmitCreate();
		}
		if(0 == strcmp('Log Out', $_POST['submit']))		
		{	
			session_start();
			session_destroy();
			header("location:employee_login.php");
		}
		
	}
	
	try
    {
        $link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
            or die('Could not connect: ' . mysql_error());
        mysql_select_db('flamboyance_db') or die('Could not select database');
        $query = "SELECT emp_Id,emp_Userid,emp_Password,emp_LastName,emp_FirstName,emp_Type,emp_Age,emp_Income FROM employeeInfo ";
        $result = mysql_query($query);// or die('Query failed: ' . mysql_error());

        /*** if we have no result then fail boat ***/
        if($result == false)
        {
            $message = 'Login Failed';
            echo "<a href='admin_report.php'>retry login</a>"; 
        }
        /*** if we do have a result, all is well ***/
        else
        {	
			echo"<html xmlns='http://www.w3.org/1999/xhtml'>
				<head>
				<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
				<title>Create Form</title>
				<link href='admin.css' rel='stylesheet' type='text/css' />
				</head>

				<body id='Table-wrapper' >
						<table border='1' style='top:20%;position:relative;font-size:16px;'>
						<tr>
						<th>Employee Id</th>
						<th>User Id</th>
						<th>Password</th>
						<th>Lastname</th>
						<th>Firstname</th>
						<th>Employee Type</th>
						<th>Employee Age</th>
						<th>Employee Income</th>
						<th>Click to Update</th>
						<th>Click to Delete</th>
						</tr>";
						
            			/*** set the session user_id variable ***/
						while($row = mysql_fetch_array($result,MYSQL_ASSOC))
						{	
							echo "<form action=".$_SERVER['PHP_SELF']. " method='post'>";
							echo "<input type='hidden' name='emp_id' value=".$row['emp_Id'].">";
							echo "<tr >";
							echo "<td>" . $row['emp_Id'] . "</td>";
							echo "<td>" . $row['emp_Userid'] . "</td>";
							echo "<td>" . $row['emp_Password'] . "</td>";
							echo "<td>" . $row['emp_LastName'] . "</td>";
							echo "<td>" . $row['emp_FirstName'] . "</td>";
							echo "<td>" . $row['emp_Type'] . "</td>";
							echo "<td>" . $row['emp_Age'] . "</td>";
							echo "<td>" . $row['emp_Income'] . "</td>";
							echo "<td><input type='submit' name='submit' class='Report-button' value='Update' /></td>";
							if ($_SESSION['emp_Id'] == $row['emp_Id'])
							{
								echo "<td></td>";
							}
							else
							{
								echo "<td><input type='submit' name='submit' class='Report-button' value='Delete' /></td>";
							}
	
							echo "</tr>";
							echo "</form>";
						}
						echo "</table>";
						echo "<form action=".$_SERVER['PHP_SELF']. " method='post'>
									<input type='submit' name='submit' class='Report-button' value='Create' 
										style='margin:20px 0 7px 0;'/>
									<input type='submit' name='submit' value='Log Out' class='Report-button' 
										style='float:left;margin:20px 0 7px 0;color:darkred;'/>
							 </form>
				
				</body>
 				</html>";
			mysql_free_result($result);

			// Closing connection
			mysql_close($link);
        }
    }
	catch(Exception $e)
	{
		$message = 'We are unable to process your request. Please try again later';
        echo "<a href='admin_report.php'>go back</a>";
    }

?> 

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Administrator Report</title>
<link href="admin.css" rel="stylesheet" type="text/css" />
</head>
<body>
</body>
</html>
