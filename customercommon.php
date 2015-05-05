<?php
/**************************************************************************	*/
/*						 Cart Funtions      							*/
/****************************************************************************/
function getCartArray()
{
	$content = array();
	
	if(isset($_SESSION['cart']))
	{
		$cart = $_SESSION['cart'];
	
		$product_pairs = explode(',',$cart);
		
		foreach($product_pairs as $product_pair)
		{
			$product_info_array = array();
			$product_info_array = explode('-',$product_pair);
			if (isset($product_info_array[0]) && isset($product_info_array[1]))
			{
				$content[$product_info_array[0]]= $product_info_array[1];
			}
		}
	}
	return $content;
}

function getCartFromArray($cart_array)
{
	$cart = "";
	$first = true;
	foreach ($cart_array as $id=>$quantity)
	{
		if ($first)
		{
			$cart .= $id. "-" . $quantity;
			$first = false;
		}
		else
		{
			$cart .= ',' . $id . "-" . $quantity;
		}
	}

	return $cart;
}

function deleteFromCart()
{
	if (isset($_POST['product_id']))
	{
		$product_id = $_POST['product_id'];
		if (isset($_SESSION['cart']))
		{
			$cart_array = getCartArray($_SESSION['cart']);
			unset($cart_array[$product_id]);
			$_SESSION['cart'] = getCartFromArray($cart_array);

		}	
	}
	
	if (isset($_POST['ss_prod_id']))
	{
		$ss_prod_id = $_POST['ss_prod_id'];
		if (isset($_SESSION['cart']))
		{
			$cart_array = getCartArray($_SESSION['cart']);
			unset($cart_array[$ss_prod_id]);
			$_SESSION['cart'] = getCartFromArray($cart_array);

		}	
	}
	
	try
	{
		$link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
			or die('Could not connect: ' . mysql_error());
		
		mysql_select_db('flamboyance_db') or die('Could not select database');
		
		$query = "SELECT session_id,session_cart FROM sessionInfo WHERE session_id='".session_id()."'" ;
		
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		
		while($row = mysql_fetch_array($result,MYSQL_ASSOC))
		{	
			$query = "UPDATE sessionInfo SET session_cart='".$_SESSION['cart']."' 
			WHERE session_id='".$row['session_id']."'";
			
			mysql_query($query) or die('Query failed: ' . mysql_error());
			
			mysql_query("COMMIT"); 
		}
		// Closing connection
		mysql_close($link);

	}
	catch(Exception $e)
	{
		echo "Exception while processing request";
	}
	echo "Cart contains {$_SESSION['cart']}";
}

function updateCart()
{
	if (isset($_POST['product_id']) && isset($_POST['product_quantity']))
	{
		$product_id = $_POST['product_id'];
		$product_quantity = $_POST['product_quantity'];
		
		if (isset($_SESSION['cart']))
		{
			$cart_array = getCartArray($_SESSION['cart']);
			$cart_array[$product_id] = $product_quantity;
			$_SESSION['cart'] = getCartFromArray($cart_array);
		}
		else
		{
			$cart_array = array();
			$cart_array[$product_id] = $product_quantity;
			$_SESSION['cart'] = getCartFromArray($cart_array);
		}
	}
	
	if (isset($_POST['ss_prod_id']) && isset($_POST['ss_quantity']))
	{
		$ss_prod_id = $_POST['ss_prod_id'];
		$ss_quantity = $_POST['ss_quantity'];
		
		if (isset($_SESSION['cart']))
		{
			$cart_array = getCartArray($_SESSION['cart']);
			$cart_array[$ss_prod_id] = $ss_quantity;
			$_SESSION['cart'] = getCartFromArray($cart_array);
		}
		else
		{
			$cart_array = array();
			$cart_array[$ss_prod_id] = $ss_quantity;
			$_SESSION['cart'] = getCartFromArray($cart_array);
		}
	}
	
	try
	{
		$link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
			or die('Could not connect: ' . mysql_error());
		
		mysql_select_db('flamboyance_db') or die('Could not select database');
		
		$query = "SELECT session_id,session_cart FROM sessionInfo WHERE session_id='".session_id()."'" ;
		
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		
		while($row = mysql_fetch_array($result,MYSQL_ASSOC))
		{	
			$query = "UPDATE sessionInfo SET session_cart='".$_SESSION['cart']."' 
			WHERE session_id='".$row['session_id']."'";
			
			mysql_query($query) or die('Query failed: ' . mysql_error());
			
			mysql_query("COMMIT"); 
		}
		// Closing connection
		mysql_close($link);

	}
	catch(Exception $e)
	{
		echo "Exception while processing request";
	}

	echo "Cart contains {$_SESSION['cart']}";
}



/*********************************************************************************/
/* 					Profile Update	                         */
/*********************************************************************************/

function profileUpdateFormDisplay()
{
	print('inside select form display ');
	$cust_user_id = $_SESSION['cust_user_id'];
	
	try
	{
		$link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
			or die('Could not connect: ' . mysql_error());

		mysql_select_db('flamboyance_db') or die('Could not select database');

		/*** prepare the insert ***/
		$query ="SELECT cust_first_name,cust_last_name,cust_user_id,cust_password FROM customerInfo
					WHERE cust_user_id='$cust_user_id' ";
	
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());

		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		
		echo"<html xmlns='http://www.w3.org/1999/xhtml'>
			<head>
			<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
			<title>Special-Sale-Update Form</title>
			<link href='cust.css' rel='stylesheet' type='text/css' />
			</head>

			<body >
		
			<div id='Create' >
				<form id='profileUpdateDisplayForm' name='profileUpdateDisplayForm' 
					action=".$_SERVER['PHP_SELF']." method='post' class='form3' style='float:left' >
		
				<H2>Profile Update Form</H2><br />
					
					<div id='Box-name'>First Name :</div>
					<div id='Box-field' >
						<input id='cust_first_name' value=".$row['cust_first_name']." name='cust_first_name' 	
							class='Box-input' type='text' 		
							/> <br />
						<div id='cust_first_name_update_error' style='display:none;color:darkred;position:relative;'>
							Please ensure cust first name is field correctly.
						</div>
					</div><br />
					
					<div id='Box-name'>Last Name :</div>
					<div id='Box-field' >
						<input id='cust_last_name' value=".$row['cust_last_name']." name='cust_last_name' class='Box-input' 
							type='text' /> <br />
						<div id='cust_last_name_update_error' style='display:none;color:darkred;position:relative;'>
							Please ensure last name is field correctly.
						</div>	
					</div><br />

					<div id='Box-name'>User Id :</div>
					<div id='Box-field' >
						<input id='cust_user_id' value=".$row['cust_user_id']." name='cust_user_id' class='Box-input' 
							type='text' /> <br />
						<div id='cust_user_id_update_error' style='display:none;color:darkred;position:relative;'>
							Please ensure user id is filled correctly.
						</div>
					</div><br />
		   
					<div id='Box-name'>Password :</div>
					<div id='Box-field' >
						<input id='cust_password' value=".$row['cust_password']." name='cust_password' class='Box-input' 
							type='text' /> <br />
						<div id='cust_password_update_error' style='display:none;color:darkred;position:relative;'>
							Please ensure password is in the format YYYY-MM-DD.
						</div>
					</div><br />
		  
					<input type='button' name='submit' value='Submit Update' class='Submit'
						onclick='profileUpdateFormSubmitClick(cust_user_id)' />
					<input type='submit' name='submit' value='Delete Profile' style='float:left;color:darkred;' 	
						class='Submit' />		
				</form>
			</div>
			</body>
			</html>";
		
		// Closing connection
		mysql_close($link);
	}
	catch(Exception $e)
	{
		/*** check if the username already exists ***/
		if( $e->getCode() == 23000)
		{
			echo 'Product already exists';
		}
		else
		{
			echo 'Error is = '.$e->getMessage();
		}
	}
}

function profileUpdateFormSubmit()
{
	print('inside Update form display');
	
	$cust_first_name = $_POST['cust_first_name'];
	$cust_last_name = $_POST['cust_last_name'];
	$cust_user_id = $_POST['cust_user_id'];
	$cust_password = $_POST['cust_password'];
	
	try
	{
		$link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
			or die('Could not connect: ' . mysql_error());

		mysql_select_db('flamboyance_db') or die('Could not select database');

		$query = "UPDATE customerInfo SET cust_first_name='$cust_first_name',cust_last_name='$cust_last_name', 
				cust_user_id='$cust_user_id',cust_password='$cust_password' WHERE cust_user_id='$cust_user_id' ";

		mysql_query($query) or die('Query failed: '. mysql_error());

		mysql_query("COMMIT"); 
		print('update done');
		// Closing connection
		mysql_close($link);
	}
	catch(Exception $e)
	{
		echo 'Error is = '.$e->getMessage();
		return false;
	}
	return true;
}

function profileDelete()
{
	$cust_user_id = $_SESSION['cust_user_id'];
	
	try
	{
		$link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
			or die('Could not connect: ' . mysql_error());

		mysql_select_db('flamboyance_db') or die('Could not select database');

		$query = "DELETE FROM custInfo WHERE cust_user_id = '$cust_user_id' ";

		mysql_query($query) or die('Query failed: ' . mysql_error());

		// Closing connection
		mysql_close($link);
		
		session_start();
		session_destroy();
		header("location:customer_login.php");
	}
	catch(Exception $e)
	{
		echo "Delete failed";
		return false;
	}
	return true;
}
	
/**************************************************************************	*/
/*						 Product Category       							*/
/****************************************************************************/
  
function productCategoryDisplay()
{
	$product_category_list = array();
	$cart_array = getCartArray();
	print('inside prodcat func');
	echo"<html xmlns='http://www.w3.org/1999/xhtml'>
		<head>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
		<title>Product Form</title>
		<link href='cust.css' rel='stylesheet' type='text/css' />
		</head>
		<body >
	
		<div id='Create' >

			<form id='productDisplayForm' name='productDisplayForm' 
				action=".$_SERVER['PHP_SELF']." method='post' class='form4' style='float:left' >
				
				<H2>Product Display Form</H2><br />";
	
				try
				{
					$link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
						or die('Could not connect: ' . mysql_error());
		
					mysql_select_db('flamboyance_db') or die('Could not select database');
			
					/*** prepare the insert ***/
					$query ="SELECT prod_cat_id,prod_cat_name FROM productCategoryInfo";
				
					$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		
					echo "<div id='Box-name'>Product Selection :</div>";
		
					echo "<select multiple name='product_category_selection' >";
		
					while($row = mysql_fetch_array($result,MYSQL_ASSOC))
					{
						echo "<option value='".$row['prod_cat_name']."'";  
						
						echo " >".$row['prod_cat_name']."</option>";
					}
					echo "</select><br />";
				
					mysql_close($link);
				}
				catch(Exception $e)
				{
					echo "Exception while processing request";
				}
				echo"<input type='button' name='submit' class='Submit' value='Show'
								onclick='productSearchSubmitClick();' /><br />";
					
					
				try
				{
					$link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
						or die('Could not connect: ' . mysql_error());
		
					mysql_select_db('flamboyance_db') or die('Could not select database');
		
					$query = "SELECT productInfo.product_id,productInfo.product_name,productCategoryInfo.prod_cat_name,
						productInfo.product_price,productInfo.product_quantity
							 FROM productInfo,productCategoryInfo 
								where productInfo.product_cat_id = productCategoryInfo.prod_cat_id";
									

					if (isset($_POST['todo']) &&  'true' === $_POST['is_provided_product_category_selection'])
					{
						$product_category_list = explode(',',$_POST['product_category_selection']);
					}
						
					if (count($product_category_list) > 0)
					{
						$index =0;
						$query = $query . " AND productCategoryInfo.prod_cat_name IN (";
						$query = $query . "'" . $product_category_list[$index] . "'";
		
						while (++$index<count($product_category_list))
						{
							$query = $query . ",'" . $product_category_list[$index] . "'";
						}
						$query = $query . ")";
					
				
						$result = mysql_query($query) or die('Query failed: ' . mysql_error());
					
						echo "<table border='1'>
							<tr>
							<th>Product Id</th>
							<th>Product Name</th>
							<th>Product Category Name</th>
							<th>Product Price</th>
							<th>Product Quantity</th>
							<th>Add to Cart</th>
							<th>Select Quantity</th>
							</tr>";

						while($row = mysql_fetch_array($result,MYSQL_ASSOC))
						{
							echo "<tr>";
							echo "<td>" . $row['product_id'] . "</td>";
							echo "<td>" . $row['product_name'] . "</td>";
							echo "<td>" . $row['prod_cat_name'] . "</td>";
							echo "<td>" . $row['product_price'] . "</td>";
							echo "<td>" . $row['product_quantity'] . "</td>";
							echo "<td><input type='checkbox' ";
							if (isset($cart_array[$row['product_id']]))
							{
								echo " checked ";
							}
							echo " id='product_checkbox_".$row['product_id']."' ";
							echo " onclick='productCheckBoxOnChange(".$row['product_id'].");' ";
							echo " name='product_checkbox_".$row['product_id']."'  ></td>";
							
							$product_quantity = -1;
							if (isset($cart_array[$row['product_id']]))
							{
								$product_quantity = $cart_array[$row['product_id']];
							}
							echo "<td><select ";
							echo " id='product_quantity_".$row['product_id']."' ";
							echo " onchange='productQuantityOnChange(".$row['product_id'].");' ";
							echo " name='product_quantity_".$row['product_id']."' >";
							for ($index=1;$index<=10;++$index)
							{
								echo "<option ";
								
								if ($index == $product_quantity)
								{	
									echo " selected ";
								}
								
								echo " value='" . $index . "'>" . $index . "</option>";
							}
							echo "</td>";
							echo "</tr>";
							echo "</form>";
						}
						echo "</table><br />";
					}
					echo "</form><br / >";
					mysql_close($link);
				}
				catch(Exception $e)
				{
					echo "Exception while processing request";
				}
			echo"</form>";
		echo"</body>";
	echo"</html>";
}
			

/*********************************************************************************/
/* 								Check Out										 */
/*********************************************************************************/	

function checkOutDisplay()
{

	echo"<html xmlns='http://www.w3.org/1999/xhtml'>
		<head>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
		<title>Special-Sale-Update Form</title>
		<link href='cust.css' rel='stylesheet' type='text/css' />
		</head>

		<body >
		
		<div id='Create' >
			<form id='checkOutDisplayForm' name='checkOutDisplayForm' 
				action=".$_SERVER['PHP_SELF']." method='post' class='form3' style='float:left' >
		
			<H2>Check Out Form</H2><br />
						
				<div id='Box-name'>Billing Address Line1</div>
				<div id='Box-field' >
					<input id='address_line1' value='' name='address_line1' 	
						class='Box-input' type='text' /> <br />
					<div id='address_line1_error' style='display:none;color:darkred;position:relative;'>
						Please ensure cust Address field is filled correctly.
					</div>
				</div><br />
            			
				<div id='Box-name'>Billing Address Line2</div>
				<div id='Box-field' >
					<input id='address_line2' value='' name='address_line2' class='Box-input' 
						type='text' /> <br />
				</div><br />

				<div id='Box-name'>Credit Card Number</div>
				<div id='Box-field' >
					<input id='credit_card_number' value='' name='credit_card_number' class='Box-input' 
						type='text' /> <br />
					<div id='credit_card_number_error' style='display:none;color:darkred;position:relative;'>
						Please ensure credit card number field is filled correctly.
					</div>
				</div><br />
				
				<div id='Box-name'>Credit Card Pin</div>
				<div id='Box-field' >
					<input id='credit_card_pin' value='' name='credit_card_pin' class='Box-input' 
						type='text' /> <br />
					<div id='credit_card_pin_error' style='display:none;color:darkred;position:relative;'>
						Please ensure credit card pin field is filled correctly.
					</div>
				</div><br />
				
				<div id='Box-name'>Expiration Month</div>
				<div id='Box-field' >
					<input id='expiration_month' value='' name='expiration_month' class='Box-input' 
						type='text' /> <br />
					<div id='expiration_month_error' style='display:none;color:darkred;position:relative;'>
						Please ensure expiration month field is filled correctly.
					</div>
				</div><br />
				<div id='Box-name'>Expiration Year</div>
				<div id='Box-field' >
					<input id='expiration_year' value='' name='expiration_year' class='Box-input' 
						type='text' /> <br />
					<div id='expiration_year_error' style='display:none;color:darkred;position:relative;'>
						Please ensure expiration year field is filled correctly.
					</div>
				</div><br />
               
				<input type='submit' name='submit' value='Submit Order' class='Submit' />	
			</form>
		</div>
		</body>
		</html>";
}
/********************************************************************************************************/
/*										Check Out Form Submit Function									*/
/*********************************************************************************************************/
function checkOutDisplayFormSubmit()
{
	print_r('inside checkOutDisplay display customer common');
	
	$address_line1 = $_POST['address_line1'];
	$address_line2 = $_POST['address_line2'];
	$credit_card_number = $_POST['credit_card_number'];
	$credit_card_pin = $_POST['credit_card_pin'];
	$expiration_month = $_POST['expiration_month'];
	$expiration_year = $_POST['expiration_year'];
	$cust_user_id = $_POST['cust_user_id'];
	$cust_id  = $_SESSION['cust_id '];
	print_r('inside checkout function'.$cust_id);
	
	/*if($address_line1 == ''|| $address_line1 == null )
	{
		echo 'Error Please enter address line 1 field';
		return;
	}
	else if($credit_card_number=='' || $credit_card_number==null || isNaN($credit_card_number))
	{
		echo 'Error Please enter credit card number field';
		return;
	}
	else if($credit_card_pin=='' || $credit_card_pin==null || isNaN($credit_card_pin))
	{
		echo 'Error Please enter credit card pin field';
		return;
	}
	else if($expiration_month=='' || $expiration_month==null || isNaN($expiration_month) || 
		$expiration_month<1 || $expiration_month>12)
		
	{
		echo 'Error Please enter expiration month field';
		return;
	}
	else if($expiration_year=='' || $expiration_year==null || isNaN($expiration_year))
	{
		echo 'Error Please enter expiration year field';
		return;
	}
	else
	{*/
		try
		{
			$link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
				or die('Could not connect: ' . mysql_error());
	
			mysql_select_db('flamboyance_db') or die('Could not select database');
			
			//*** Start Transaction ***//  
			mysql_query("START TRANSACTION");  
	
			//***  Query 1 ***//  
			$query = "INSERT INTO orderInfo(cust_id,address_line1,address_line2,credit_card_number,
			credit_card_pin,expiration_month,expiration_year) 
				VALUES ('$cust_id','$address_line1','$address_line2','$credit_card_number','$credit_card_pin',
					'$expiration_month','$expiration_year' )";
			$result = mysql_query($query) or die('Query failed: ' . mysql_error());
			//$row1 = mysql_fetch_array($result,MYSQL_ASSOC);
			//($row1['cust_id']);
			echo "done query1";
			
			//***  Query 2 ***//  
			$query2 = "SELECT MAX(order_id) FROM orderInfo WHERE cust_id='$cust_id' ";
			
			$result2 = mysql_query($query2) or die('Query failed: ' . mysql_error());
			echo $result2;
			print_r($result2);
			//$row2 = mysql_fetch_array($result2,MYSQL_ASSOC);
		//	print($row2['orderInfo']);
			echo "done query2";
  
			if(($result) and ($result2))  
			{  
				//*** Commit Transaction ***//  
				mysql_query("COMMIT");  
				echo "Save Done.";  
			}  
			else  
			{  
				//*** RollBack Transaction ***//  
				mysql_query("ROLLBACK");  
				echo "Error while Save";  
			}  
			
			// Closing connection
			mysql_close($link);
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
	//}
}



/*********************************************************************************************************/
/*						                       Special Sails								              */
/*********************************************************************************************************/
function specialSaleDisplay()
{
	try
	{	
		$cart_array = getCartArray();
		
		print_r($_SESSION['cart']);
		$link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
			or die('Could not connect: ' . mysql_error());

		mysql_select_db('flamboyance_db') or die('Could not select database');

		$query = "SELECT ss_id,ss_cat_id,ss_prod_id,ss_name,ss_start_date,ss_end_date FROM specialSalesInfo
			WHERE CURDATE() >= ss_start_date AND CURDATE() <= ss_end_date ";

		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		
		echo"<html xmlns='http://www.w3.org/1999/xhtml'>
			<head>
			<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
			<title></title>
			<link href='cust.css' rel='stylesheet' type='text/css' />
			</head>

			<body >
				<table border='1'>
				<tr>
				<th>Sales Id</th>
				<th>Product Category Id</th>
				<th>Product Id</th>
				<th>Sale Name</th>
				<th>Sale Start Date</th>
				<th>Sale End Date</th>
				<th>Add to Cart</th>
				<th>Select Quantity</th>
				</tr>";

				while($row = mysql_fetch_array($result,MYSQL_ASSOC))
				{
					echo "<form id='specialSaleDisplayForm1' action=".$_SERVER['PHP_SELF']. " method='post'>";
					echo "<input type='hidden' name='ss_id' value=".$row['ss_id'].">";
					echo "<tr>";
					echo "<td>" . $row['ss_id'] . "</td>";
					echo "<td>" . $row['ss_cat_id'] . "</td>";
					echo "<td>" . $row['ss_prod_id'] . "</td>";
					echo "<td>" . $row['ss_name'] . "</td>";
					echo "<td>" . $row['ss_start_date'] . "</td>";
					echo "<td>" . $row['ss_end_date'] . "</td>";
					
					
					echo "<td><input type='checkbox' ";
					if (isset($cart_array[$row['ss_prod_id']]))
					{
						echo " checked ";
					}
					echo " id='ss_checkbox_".$row['ss_prod_id']."' ";
					echo " onclick='specialSalesCheckBoxOnChange(".$row['ss_prod_id'].");' ";
					echo " name='ss_checkbox_".$row['ss_prod_id']."'  ></td>";
							
					$product_quantity = -1;
					if (isset($cart_array[$row['ss_prod_id']]))
					{
						$product_quantity = $cart_array[$row['ss_prod_id']];
					}
					echo "<td><select ";
					echo " id='ss_quantity_".$row['ss_prod_id']."' ";
					echo " onchange='specialSalesQuantityOnChange(".$row['ss_prod_id'].");' ";
					echo " name='ss_quantity_".$row['ss_prod_id']."' >";
					for ($index=1;$index<=10;++$index)
					{
						echo "<option ";
						
						if ($index == $product_quantity)
						{	
							echo " selected ";
						}
						
						echo " value='" . $index . "'>" . $index . "</option>";
					}
					echo "</td>";
					echo "</tr>";
					echo "</form>";
				}
				echo "</table>
			</body>
			</html>";
		mysql_close($link);
	}
	catch(Exception $e)
	{
		echo "Exception while processing request";
	}
}

/************************************************************************************************/
/*							Shopping Cart Display												*/
/************************************************************************************************/
function shoppingCartDisplay()
{
	try
	{
		$cart_array = getCartArray($_SESSION['cart']);
		print_r($cart_array);
		
		$link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
			or die('Could not connect: ' . mysql_error());

		mysql_select_db('flamboyance_db') or die('Could not select database');
		
		echo"<html xmlns='http://www.w3.org/1999/xhtml'>
			<head>
			<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
			<title></title>
			<link href='cust.css' rel='stylesheet' type='text/css' />
			</head>

			<body >
				<form>
				<table border='1'>
				<tr>
				<th>Product Id</th>
				<th>Product Name</th>
				<th>Product Category Name</th>
				<th>Product Price</th>
				<th>Discounted Price</th>
				<th>Delete</th>
				</tr>";
						
				foreach ($cart_array as $id=>$quantity)
				{
					echo $id;
		 
					$query = "SELECT productInfo.product_id,productInfo.product_name,productCategoryInfo.prod_cat_name,
						productInfo.product_price FROM productInfo,productCategoryInfo,specialSalesInfo 
							where productInfo.product_id=".$id." AND 
								productInfo.product_cat_id = productCategoryInfo.prod_cat_id";
								
					$result = mysql_query($query) or die('Query failed: ' . mysql_error());
					$row = mysql_fetch_array($result,MYSQL_ASSOC);
					
					echo "<tr>";
					echo "<td>" . $row['product_id'] . "</td>";
					echo "<td>" . $row['product_name'] . "</td>";
					echo "<td>" . $row['prod_cat_name'] . "</td>";
					echo "<td>" . $row['product_price'] . "</td>";
					{
						
						//$link1 = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
							//or die('Could not connect: ' . mysql_error());

						//mysql_select_db('flamboyance_db') or die('Could not select database');
						
						$query1 =  "SELECT ss_name FROM specialSalesInfo WHERE 
						CURDATE() >= ss_start_date AND CURDATE() <= ss_end_date AND
							specialSalesInfo.ss_prod_id= ".$id ;
						$result1 = mysql_query($query1) or die('Query failed: ' . mysql_error());
						$row1 = mysql_fetch_array($result1,MYSQL_ASSOC);
						$discounted_price =doubleval($row['product_price']);
						if($row1)
						{
							$discounted_price = $discounted_price *(0.8);
						}
						echo "<td>" .$discounted_price. "</td>";
						//mysql_close($link1);		
					}
					
					echo "<td><input type='button' name='submit' value='Delete' class='Submit'/></td>";
					
					echo "</tr>";
					
					//next($cart_array);
				}
				echo "</table><br />";
				echo "</form><br / >
			</body>
			</html>";
		
		mysql_close($link);
		
	}
	catch(Exception $e)
	{
		echo "Exception while processing request";
	}
}

?>


