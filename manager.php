<?php
    ob_start();
    session_start();
    if ($_SESSION['timeout'] + 10*60 < time()) 
  	{	
		session_start();
		session_destroy();
		header("location:employee_login.php");
		
    } 
    else 
	{
		$_SESSION['timeout'] = time();	
	}

    // Product    
    function productSearchDisplay()
    {
        $is_provided_price_start = false;
        $price_start = "";
        $is_provided_price_end = false;
        $price_end = "";
        $product_list = array();
        $product_category_list = array();
		
		echo"<html xmlns='http://www.w3.org/1999/xhtml'>
				<head>
				<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
				<title>Special-Sale-Create Form</title>
				<link href='manager.css' rel='stylesheet' type='text/css' />
				</head>

				<body >
			
				<div id='Create' >
        			<form id='productSearchDisplayForm' name='productSearchDisplayForm' action=".$_SERVER['PHP_SELF']."  
						method='post' class='form4' style='float:left' >

						<H2>Product Search Form</H2><br />
						
						<div id='Box-name'>Product Start Price :</div>
						<div id='Box-field' >
							<input id='price_start' name='price_start' class='Box-input' type='text' ";
							
							if (isset($_POST['todo']) && 'true' === $_POST['is_provided_price_start'])
							{
								$is_provided_price_start = true;
								$price_start = $_POST['price_start'];
								echo " value='".$_POST['price_start']."' ";
                            }
                        echo "/><br />";    
      					echo"<div id='price_start_error' style='position:relative;color:darkred;display:none;'>
								Please ensure product start price field is filled.
							</div>
						</div><br />
		
            			<div id='Box-name'>Product End Price :</div>
						<div id='Box-field' >
							<input id='price_end' name='price_end' class='Box-input' type='text' ";

							if (isset($_POST['todo']) && 'true' === $_POST['is_provided_price_end'])
							{
								$is_provided_price_end = true;
								$price_end = $_POST['price_end'];
								echo " value='".$_POST['price_end']."' ";
                            }
                        echo "/> <br />";    
						echo "<div id='price_end_error' style='position:relative;color:darkred;display:none;'>
								Please ensure product end price field is filled.
							</div>
						</div><br />";
       
						try
						{
							$link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
								or die('Could not connect: ' . mysql_error());
				
							mysql_select_db('flamboyance_db') or die('Could not select database');
				
							$query = "SELECT DISTINCT(product_name) FROM productInfo ";
				
							$result = mysql_query($query) or die('Query failed: ' . mysql_error());
				
							if (isset($_POST['todo']) &&  'true' === $_POST['is_provided_product_selection'])
							{
								$product_list = explode(',',$_POST['product_selection']);
							}

							echo"<div id='Box-name'>Product Selection :</div><br />
							<select multiple name='product_selection' >";
            
							while($row = mysql_fetch_array($result,MYSQL_ASSOC))
							{
								echo "<option value='".$row['product_name']."'"; 
								if (in_array($row['product_name'],$product_list))
								{
									echo " selected ";
								}   
								echo " >".$row['product_name'];
								echo " </option>";
							}
            				echo "</select><br />";

           					 mysql_close($link);
						}
						catch(Exception $e)
						{
							echo "Exception while processing request";
						}
						try
						{
							$link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
								or die('Could not connect: ' . mysql_error());
				
							mysql_select_db('flamboyance_db') or die('Could not select database');
				
							$query = "SELECT DISTINCT(prod_cat_name) FROM productCategoryInfo";
				
							$result = mysql_query($query) or die('Query failed: ' . mysql_error());
				
							if (isset($_POST['todo']) &&  'true' === $_POST['is_provided_product_category_selection'])
							{
								$product_category_list = explode(',',$_POST['product_category_selection']);
							}

							echo "<div id='Box-name'>Product Selection :</div><br />";
            
							echo "<select multiple name='product_category_selection' >";
				
							while($row = mysql_fetch_array($result,MYSQL_ASSOC))
							{
								echo "<option value='".$row['prod_cat_name']."'";
								if (in_array($row['prod_cat_name'],$product_category_list))
								{
									echo " selected ";
								}   
								
								echo " >".$row['prod_cat_name']."</option>";
							}
							echo "</select><br />";
						
							mysql_close($link);
						}
						catch(Exception $e)
						{
							echo "Exception while processing request";
						}
           
          			echo"<input type='button' name='submit' class='Submit' value='Submit'
						onclick='productSearchSubmitClick();' /><br />";
        
					try
					{
						$link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
							or die('Could not connect: ' . mysql_error());
			
						mysql_select_db('flamboyance_db') or die('Could not select database');
			
						$query = "SELECT productInfo.product_id,productInfo.product_name,productCategoryInfo.prod_cat_name,
							productInfo.product_price,productInfo.product_quantity FROM productInfo,productCategoryInfo where 
								productInfo.product_cat_id=productCategoryInfo.prod_cat_id";
			
						if ($is_provided_price_start)
						{
							$query = $query . " AND productInfo.product_price >=".$price_start;
						}
            
						if ($is_provided_price_end)
						{
							$query = $query . " AND productInfo.product_price <=".$price_end;
						}
            
						if (count($product_list) > 0)
						{
							$index =0;
							$query = $query . " AND productInfo.product_name IN (";
							$query = $query . "'" . $product_list[$index] . "'";
			
							while (++$index < count($product_list))
							{
								$query = $query . ",'" . $product_list[$index] . "'";
							}
							$query = $query . ")";
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
						} 

						$result = mysql_query($query) or die('Query failed: ' . mysql_error());
						echo "<table border='1'>
							<tr>
							<th>Product Id</th>
							<th>Product Name</th>
							<th>Product Category Name</th>
							<th>Product Price</th>
							<th>Product Quantity</th>
							</tr>";

							while($row = mysql_fetch_array($result,MYSQL_ASSOC))
							{
								echo "<tr>";
								echo "<td>" . $row['product_id'] . "</td>";
								echo "<td>" . $row['product_name'] . "</td>";
								echo "<td>" . $row['prod_cat_name'] . "</td>";
								echo "<td>" . $row['product_price'] . "</td>";
								echo "<td>" . $row['product_quantity'] . "</td>";
								echo "</tr>";
								echo "</form>";
							}
							echo "</table><br />";
							echo "</form><br / >";
					}
					catch(Exception $e)
					{
						echo "Exception while processing request";
					}
    			}
			echo "</form>
			</div>
			</body>
		</html>";

    function employeeSearchDisplay()
    {
        $is_provided_income_start = false;
        $income_start = '';
        $is_provided_income_end = false;
        $income_end = '';
        $emp_type_list = array();

		echo"<html xmlns='http://www.w3.org/1999/xhtml'>
				<head>
				<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
				<title>Special-Sale-Create Form</title>
				<link href='manager.css' rel='stylesheet' type='text/css' />
				</head>

				<body >
			
				<div id='Create' >

        			<form id='employeeSearchDisplayForm' name='employeeSearchDisplayForm' action=".$_SERVER['PHP_SELF']." 
						method='post' class='form4' style='float:left' >
						
						<H2>Employee Search Form</H2><br />
						
						<div id='Box-name'>Employee Income Start :</div>
						<div id='Box-field' >
							<input id='income_start' name='income_start' class='Box-input' type='text' "; 
						if (isset($_POST['todo']) && 'true' === $_POST['is_provided_income_start'])
						{
							$is_provided_income_start = true;
							$income_start = $_POST['income_start'];
							echo " value='".$_POST['income_start']."' ";
						}
                        echo "/> <br />
							<div id='income_start_error' style='position:relative;color:darkred;display:none;'>
								Please ensure income start field is filled correctly.
							</div>
						</div><br />";

	   					echo"<div id='Box-name'>Employee End Income :</div>
						<div id='Box-field' >
                        <input id='income_end' name='income_end' class='Box-input' type='text' ";
                                    
						if (isset($_POST['todo']) && 'true' === $_POST['is_provided_income_end'])
						{
							$is_provided_income_end = true;
							$income_end = $_POST['income_end'];
							echo " value='".$_POST['income_end']."' ";
						}
						echo "/> <br />
							<div id='income_start_error' style='position:relative;color:darkred;display:none;'>
								Please ensure income start field is filled correctly.
							</div>
						</div><br />";

						try
						{
							$link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
								or die('Could not connect: ' . mysql_error());
				
							mysql_select_db('flamboyance_db') or die('Could not select database');
				
							$query = "SELECT DISTINCT(emp_Type) FROM employeeInfo ";
				
							$result = mysql_query($query) or die('Query failed: ' . mysql_error());
				
							if (isset($_POST['todo']) &&  'true' === $_POST['is_provided_emp_type_selection'])
							{
								$emp_type_list = explode(',',$_POST['emp_type_selection']);
							}
				
							echo"<div id='Box-name'>Employee Type Selection :</div><br />";
							echo"<br />";
							echo"<br />";
							echo "<select multiple name='emp_type_selection' >";
							
							while($row = mysql_fetch_array($result,MYSQL_ASSOC))
							{
								echo "<option value='".$row['emp_Type']."'"; 
								if (in_array($row['emp_Type'],$emp_type_list))
								{
									echo " selected ";
								}   
								echo " >".$row['emp_Type'];
								echo " </option>";
							}
							echo "</select><br />";
				
							mysql_close($link);
						}
						catch(Exception $e)
						{
							echo "Exception while processing request";
						}
          
          				echo"<input type='button' name='submit' value='Submit' class='Submit' 
							onclick='employeeSearchSubmitClick();' /><br />";
        
						try
						{
							$link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
								or die('Could not connect: ' . mysql_error());
				
							mysql_select_db('flamboyance_db') or die('Could not select database');
				
							$query = "SELECT employeeInfo.emp_Id,employeeInfo.emp_Userid,employeeInfo.emp_LastName,
								employeeInfo.emp_FirstName,employeeInfo.emp_Type,employeeInfo.emp_Age, 
									employeeInfo.emp_Income FROM employeeInfo WHERE 1=1";
				
							if ($is_provided_income_start)
							{
								$query = $query . " AND employeeInfo.emp_Income >=".$income_start;
							}
							
							if ($is_provided_income_end)
							{
								$query = $query . " AND employeeInfo.emp_Income <=".$income_end;
							}
							
							if (count($emp_type_list) > 0)
							{
								$index =0;
								$query = $query . " AND emp_Type IN (";
								$query = $query . "'" . $emp_type_list[$index] . "'";
				
								while (++$index < count($emp_type_list))
								{
									$query = $query . ",'" . $emp_type_list[$index] . "'";
								}
								$query = $query . ")";
							} 
				
							$result = mysql_query($query) or die('Query failed: ' . mysql_error());
							echo "<table border='1'>
								<tr>
								<th>Employee Id</th>
								<th>Employee Userid</th>
								<th>Employee Last Name</th>
								<th>Employee First Name</th>
								<th>Employee Type</th>
								<th>Employee Age</th>
								<th>Employee Income</th>
								</tr>";
				
							while($row = mysql_fetch_array($result,MYSQL_ASSOC))
							{
								echo "<tr>";
								echo "<td>" . $row['emp_Id'] . "</td>";
								echo "<td>" . $row['emp_Userid'] . "</td>";
								echo "<td>" . $row['emp_LastName'] . "</td>";
								echo "<td>" . $row['emp_FirstName'] . "</td>";
								echo "<td>" . $row['emp_Type'] . "</td>";
								echo "<td>" . $row['emp_Age'] . "</td>";
								echo "<td>" . $row['emp_Income'] . "</td>";
								echo "</tr>";
								echo "</form>";
							}
							echo "</table><br />";
							echo "</form><br / >";
						}
						catch(Exception $e)
						{
							echo "Exception while processing request";
						}
					}
				echo"</form>
				</div>
			</body>
		</html>";
    function specialSaleSearchDisplay()
    {
        $is_provided_price_start = false;
        $price_start = "";
        $is_provided_price_end = false;
        $price_end = "";
        $product_list = array();
        $product_category_list = array();
        $is_provided_sale_start = false;
        $sale_start = "";
        $is_provided_sale_end = false;
        $sale_end = "";

		echo"<html xmlns='http://www.w3.org/1999/xhtml'>
				<head>
				<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
				<title>Special-Sale-Create Form</title>
				<link href='manager.css' rel='stylesheet' type='text/css' />
				</head>

				<body >
			
				<div id='Create' >

        			<form id='specialSaleSearchDisplayForm' name='specialSaleSearchDisplayForm' 
						action=".$_SERVER['PHP_SELF']." method='post' class='form4' style='float:left' >
						
						<H2>Special Sale Search Form</H2><br />
						
						<div id='Box-name'>Product Start Price :</div>
						<div id='Box-field' >
                        <input id='price_start' name='price_start' class='Box-input' type='text' ";
        				
						if (isset($_POST['todo']) && 'true' === $_POST['is_provided_price_start'])
						{
							$is_provided_price_start = true;
							$price_start = $_POST['price_start'];
							echo " value='".$_POST['price_start']."' ";
						}
                        echo "/> <br />
							<div id='income_start_error' style='position:relative;color:darkred;display:none;'>
								Please ensure income start field is filled correctly.
							</div>
						</div><br />";

						echo"<div id='Box-name'>Product End Price :</div>
						<div id='Box-field' >
                        <input id='price_end' name='price_end' class='Box-input' type='text' ";
                                   
						if (isset($_POST['todo']) && 'true' === $_POST['is_provided_price_end'])
						{
							$is_provided_price_end = true;
							$price_end = $_POST['price_end'];
							echo " value='".$_POST['price_end']."' ";
						}
                        
                        echo "/> <br />
							<div id='price_end_error' style='position:relative;color:darkred;display:none;'>
								Please ensure income start field is filled correctly.
							</div>
						</div><br />";
 
						echo"<div id='Box-name'>Sale Start Date(YYYY-MM-DD) :</div>
						<div id='Box-field' >
                        <input id='sale_start' name='sale_start' class='Box-input' type='text' ";
                        // Sale dates
						if (isset($_POST['todo']) && 'true' === $_POST['is_provided_sale_start'])
						{
							$is_provided_sale_start = true;
							$sale_start = $_POST['sale_start'];
							echo " value='".$_POST['sale_start']."' ";
						}
                        
                        echo "/> <br />
						</div><br />";

						echo"<div id='Box-name'>Sale End Date(YYYY-MM-DD):</div>
						<div id='Box-field' >
                        <input id='sale_end' name='sale_end' class='Box-input' type='text' ";
                        						
						 if (isset($_POST['todo']) && 'true' === $_POST['is_provided_sale_end'])
						{
							$is_provided_sale_end = true;
							$sale_end = $_POST['sale_end'];
							echo " value='".$_POST['sale_end']."' ";
                        }
                        echo "/> <br />
						</div><br />
						<br />";

						
						try
						{
							$link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
								or die('Could not connect: ' . mysql_error());
				
							mysql_select_db('flamboyance_db') or die('Could not select database');
				
							$query = "SELECT DISTINCT(product_name) FROM productInfo ";
				
							$result = mysql_query($query) or die('Query failed: ' . mysql_error());
				
							if (isset($_POST['todo']) &&  'true' === $_POST['is_provided_product_selection'])
							{
								$product_list = explode(',',$_POST['product_selection']);
							}
							echo"<br />";
							echo"<br />";
							echo"<div id='Box-name'>Product Selection :</div><br />";
                            echo "<br />";
                            echo "<select multiple name='product_selection' >";
							while($row = mysql_fetch_array($result,MYSQL_ASSOC))
							{
								echo "<option value='".$row['product_name']."'"; 
								if (in_array($row['product_name'],$product_list))
								{
									echo " selected ";
								}   
								echo " >".$row['product_name'];
								echo " </option>";
							}
							echo "</select><br />";
				
							mysql_close($link);
						}
						catch(Exception $e)
						{
							echo "Exception while processing request";
						}
						try
						{
							$link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
								or die('Could not connect: ' . mysql_error());
				
							mysql_select_db('flamboyance_db') or die('Could not select database');
				
							$query = "SELECT DISTINCT(prod_cat_name) FROM productCategoryInfo";
				
							$result = mysql_query($query) or die('Query failed: ' . mysql_error());
				
							if (isset($_POST['todo']) &&  'true' === $_POST['is_provided_product_category_selection'])
							{
								$product_category_list = explode(',',$_POST['product_category_selection']);
							}
							
							echo"<br />";
							echo"<div id='Box-name'>Product Category Selection :<br /></div>";
							echo "<select multiple name='product_category_selection' >";
				
							while($row = mysql_fetch_array($result,MYSQL_ASSOC))
							{
								echo "<option value='".$row['prod_cat_name']."'";
								if (in_array($row['prod_cat_name'],$product_category_list))
								{
									echo " selected ";
								}   
								
								echo " >".$row['prod_cat_name']."</option>";
							}
							echo "</select><br />";
						
							mysql_close($link);
						}
						catch(Exception $e)
						{
							echo "Exception while processing request";
						}
						   
						  echo"<input type='button' name='submit' value='Submit' class='Submit'
						  	onclick='specialSaleSearchSubmitClick();' /><br />";
        
						try
						{
							$link = mysql_connect('cs-server.usc.edu:6092', 'root', 'mysql_jdd')
								or die('Could not connect: ' . mysql_error());
				
							mysql_select_db('flamboyance_db') or die('Could not select database');
				
							$query = "SELECT productInfo.product_id,productInfo.product_name,productCategoryInfo.prod_cat_name,
								productInfo.product_price,productInfo.product_quantity,specialSalesInfo.ss_name,
									specialSalesInfo.ss_start_date,specialSalesInfo.ss_end_date FROM productInfo,
										productCategoryInfo,specialSalesInfo where 	
										productInfo.product_id=specialSalesInfo.ss_prod_id AND 
											specialSalesInfo.ss_cat_id = productCategoryInfo.prod_cat_id";

							if ($is_provided_price_start)
							{
								$query = $query . " AND productInfo.product_price >=".$price_start;
							}
							
							if ($is_provided_price_end)
							{
								$query = $query . " AND productInfo.product_price <=".$price_end;
							}
						
							if ($is_provided_sale_start)
							{
								$query = $query . " AND specialSalesInfo.ss_start_date >='".$sale_start . "'";
							}
							
							if ($is_provided_sale_end)
							{
								$query = $query . " AND specialSalesInfo.ss_end_date <='".$sale_end . "'";
							}
							
							if (count($product_list) > 0)
							{
								$index =0;
								$query = $query . " AND productInfo.product_name IN (";
								$query = $query . "'" . $product_list[$index] . "'";
				
								while (++$index < count($product_list))
								{
									$query = $query . ",'" . $product_list[$index] . "'";
								}
								$query = $query . ")";
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
							} 
			
							$result = mysql_query($query) or die('Query failed: ' . mysql_error());
							echo "<table border='1'>
								<tr>
								<th>Product Id</th>
								<th>Product Name</th>
								<th>Product Category Name</th>
								<th>Product Price</th>
								<th>Product Quantity</th>
								<th>Sale Name</th>
								<th>Sale Start Date</th>
								<th>Sale Start End</th>
								</tr>";

								while($row = mysql_fetch_array($result,MYSQL_ASSOC))
								{
									echo "<tr>";
									echo "<td>" . $row['product_id'] . "</td>";
									echo "<td>" . $row['product_name'] . "</td>";
									echo "<td>" . $row['prod_cat_name'] . "</td>";
									echo "<td>" . $row['product_price'] . "</td>";
									echo "<td>" . $row['product_quantity'] . "</td>";
									echo "<td>" . $row['ss_name'] . "</td>";
									echo "<td>" . $row['ss_start_date'] . "</td>";
									echo "<td>" . $row['ss_end_date'] . "</td>";
									echo "</tr>";
									echo "</form>";
								}
								echo "</table><br />";
								echo "</form><br / >";
							}
							catch(Exception $e)
							{
								echo "Exception while processing request";
							}
						}

						if (isset($_GET['search']))
						{
							if ($_GET['search'] == 1)
							{
								productSearchDisplay();
								return;
							}
							if ($_GET['search'] == 2)
							{
								employeeSearchDisplay();
								return;
							}
							if ($_GET['search'] == 3)
							{
								specialSaleSearchDisplay();
								return;
							}
						}
                        if(isset($_POST['submit']))
                        {
                            if(0 == strcmp('Log Out', $_POST['submit']))		
                            {	
                                session_start();
                                session_destroy();
                                header("location:employee_login.php");
                            }
                        }
						if (isset($_POST['todo']))
						{
							if (0 == strcmp($_POST['todo'],'productSearch'))
							{
								productSearchDisplay();
								return;
							}
					
							if (0 == strcmp($_POST['todo'],'employeeSearch'))
							{
								employeeSearchDisplay();
								return;
							}
						 
							if (0 == strcmp($_POST['todo'],'specialSaleSearch'))
							{
								specialSaleSearchDisplay();
								return;
							}
						}
					echo"</form>";
				echo"</body>";
			echo"</html>";
				
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href='manager.css' rel='stylesheet' type='text/css' />
<title>Manager Dashboard</title>
<script language="JavaScript" type="text/javascript"> 
function callAjaxGet(url, pageElement, callMessage, errorMessage) 
{
    document.getElementById(pageElement).innerHTML = callMessage;
    try {
        req = new XMLHttpRequest(); /* e.g. Firefox */
    } catch(e) {
        try {
            req = new ActiveXObject("Msxml2.XMLHTTP");  /* some versions IE */
        } catch (e) {
            try {
                req = new ActiveXObject("Microsoft.XMLHTTP");  /* some versions IE */
            } catch (E) {
                req = false;
            }
        }
    }

    req.onreadystatechange = function()      {responseAjaxGet(pageElement, errorMessage);};
    req.open("GET",url,true);
    req.send(null);
}

function responseAjaxGet(pageElement, errorMessage) 
{
    var output = '';
    if(req.readyState == 4) {
        if(req.status == 200) {
            output = req.responseText;
            document.getElementById(pageElement).innerHTML = output;
        } else {
            document.getElementById(pageElement).innerHTML = errorMessage+"\n"+output;
        }
    }
}

function makeactive(tab)
{
    document.getElementById("tab1").className = "";
    document.getElementById("tab2").className = "";
    document.getElementById("tab3").className = "";
    document.getElementById("tab"+tab).className = "active";
    callAjaxGet('manager.php?search= '+tab, 'search', 'getting content for search. Please Wait...', 'Error');
}

function callAjaxPost(url, parameters, pageElement, callMessage, errorMessage)
{
    document.getElementById(pageElement).innerHTML = callMessage;
    try {
        req = new XMLHttpRequest(); /* e.g. Firefox */
    } catch(e) {
        try {
            req = new ActiveXObject("Msxml2.XMLHTTP");  /* some versions IE */
        } catch (e) {
            try {
                req = new ActiveXObject("Microsoft.XMLHTTP");  /* some versions IE */
            } catch (E) {
                req = false;
            }
        }
    }

    req.onreadystatechange = function()      {responseAjaxPost(pageElement, errorMessage);};
    req.open("POST",url,true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
    req.send(parameters);

}

function responseAjaxPost(pageElement, errorMessage) 
{
    var output = '';
    if(req.readyState == 4) {
        if(req.status == 200) {
            output = req.responseText;
            document.getElementById(pageElement).innerHTML = output;
        } else {
            document.getElementById(pageElement).innerHTML = errorMessage+"\n"+output;
        }
    }
}

function productSearchSubmitClick()
{
    var params = "todo=productSearch";

    if (document.productSearchDisplayForm.price_start.value !== "" && document.productSearchDisplayForm.price_start.value !== null)
    {
        params = params + "&is_provided_price_start=true";
        params = params + "&price_start="+ document.productSearchDisplayForm.price_start.value;
    }
    else
    {
        params = params + "&is_provided_price_start=false";
    }

    if (document.productSearchDisplayForm.price_end.value !== "" &&
        document.productSearchDisplayForm.price_end.value !== null)
    {
        params = params + "&is_provided_price_end=true";
        params = params + "&price_end=" + document.productSearchDisplayForm.price_end.value;
    }
    else
    {
        params = params + "&is_provided_price_end=false";
    }

    var selectedList = []; 
    var product_select = document.productSearchDisplayForm.product_selection;
    var product_options = product_select.options;
    for (var i = 0; i < product_options.length; i++) {
        if (product_options[i].selected) 
        {
            selectedList.push(product_options[i].value);
        }
    }

    if (selectedList.length !== 0)
    {
        params = params + "&is_provided_product_selection=true";
        params = params + "&product_selection=" + Array.join(selectedList,",");
    }
    else
    {
        params = params + "&is_provided_product_selection=false";
    }

    var prodCatSelectedList = []; 
    var product_cat_select = document.productSearchDisplayForm.product_category_selection;
    var product_category_options = product_cat_select.options;
    for (var i = 0; i < product_category_options.length; i++) {
        if (product_category_options[i].selected) 
        {
            prodCatSelectedList.push(product_category_options[i].value);
        }
    }

    if (prodCatSelectedList.length !== 0)
    {
        params = params + "&is_provided_product_category_selection=true";
        params = params + "&product_category_selection="+Array.join(prodCatSelectedList,",");
    }
    else
    {
        params = params + "&is_provided_product_category_selection=false";
    }

    callAjaxPost('manager.php', params, 'search',
        'Searching. Please Wait ...', 'Error');
}

function specialSaleSearchSubmitClick()
{
    var params = "todo=specialSaleSearch";

    if (document.specialSaleSearchDisplayForm.price_start.value !== "" && document.specialSaleSearchDisplayForm.price_start.value !== null)
    {
        params = params + "&is_provided_price_start=true";
        params = params + "&price_start="+ document.specialSaleSearchDisplayForm.price_start.value;
    }
    else
    {
        params = params + "&is_provided_price_start=false";
    }

    if (document.specialSaleSearchDisplayForm.price_end.value !== "" &&
        document.specialSaleSearchDisplayForm.price_end.value !== null)
    {
        params = params + "&is_provided_price_end=true";
        params = params + "&price_end=" + document.specialSaleSearchDisplayForm.price_end.value;
    }
    else
    {
        params = params + "&is_provided_price_end=false";
    }

    if (document.specialSaleSearchDisplayForm.sale_start.value !== "" && document.specialSaleSearchDisplayForm.sale_start.value !== null)
    {
        params = params + "&is_provided_sale_start=true";
        params = params + "&sale_start="+ document.specialSaleSearchDisplayForm.sale_start.value;
    }
    else
    {
        params = params + "&is_provided_sale_start=false";
    }

    if (document.specialSaleSearchDisplayForm.sale_end.value !== "" &&
        document.specialSaleSearchDisplayForm.sale_end.value !== null)
    {
        params = params + "&is_provided_sale_end=true";
        params = params + "&sale_end=" + document.specialSaleSearchDisplayForm.sale_end.value;
    }
    else
    {
        params = params + "&is_provided_sale_end=false";
    }

    var selectedList = []; 
    var product_select = document.specialSaleSearchDisplayForm.product_selection;
    var product_options = product_select.options;
    for (var i = 0; i < product_options.length; i++) {
        if (product_options[i].selected) 
        {
            selectedList.push(product_options[i].value);
        }
    }

    if (selectedList.length !== 0)
    {
        params = params + "&is_provided_product_selection=true";
        params = params + "&product_selection=" + Array.join(selectedList,",");
    }
    else
    {
        params = params + "&is_provided_product_selection=false";
    }

    var prodCatSelectedList = []; 
    var product_cat_select = document.specialSaleSearchDisplayForm.product_category_selection;
    var product_category_options = product_cat_select.options;
    for (var i = 0; i < product_category_options.length; i++) {
        if (product_category_options[i].selected) 
        {
            prodCatSelectedList.push(product_category_options[i].value);
        }
    }

    if (prodCatSelectedList.length !== 0)
    {
        params = params + "&is_provided_product_category_selection=true";
        params = params + "&product_category_selection="+Array.join(prodCatSelectedList,",");
    }
    else
    {
        params = params + "&is_provided_product_category_selection=false";
    }

    callAjaxPost('manager.php', params, 'search',
        'Searching. Please Wait ...', 'Error');
}

function employeeSearchSubmitClick()
{
    var params = "todo=employeeSearch";

    if (document.employeeSearchDisplayForm.income_start.value !== "" && document.employeeSearchDisplayForm.income_start.value !== null)
    {
        params = params + "&is_provided_income_start=true";
        params = params + "&income_start="+ document.employeeSearchDisplayForm.income_start.value;
    }
    else
    {
        params = params + "&is_provided_income_start=false";
    }

    if (document.employeeSearchDisplayForm.income_end.value !== "" &&
        document.employeeSearchDisplayForm.income_end.value !== null)
    {
        params = params + "&is_provided_income_end=true";
        params = params + "&income_end=" + document.employeeSearchDisplayForm.income_end.value;
    }
    else
    {
        params = params + "&is_provided_income_end=false";
    }

    var selectedList = []; 
    var emp_type_select = document.employeeSearchDisplayForm.emp_type_selection;
    var emp_type_options = emp_type_select.options;
    for (var i = 0; i < emp_type_options.length; i++) 
    {
        if (emp_type_options[i].selected) 
        {
            selectedList.push(emp_type_options[i].value);
        }
    }
    if (selectedList.length !== 0)
    {
        params = params + "&is_provided_emp_type_selection=true";
        params = params + "&emp_type_selection=" + Array.join(selectedList,",");
    }
    else
    {
        params = params + "&is_provided_emp_type_selection=false";
    }

    callAjaxPost('manager.php', params, 'search',
        'Searching. Please Wait ...', 'Error');
}

</script>
</head>
<body id="Form-wrapper" >
<div class="Wrapper" >
	<div class="Header" >
    	<form action="" method="post"  style="float:left">
			<div id="menuid">
                 <ul style="list-style:none;float:left;margin-top:5px;padding-left:20px;" id="tabmenu" > 
					<li>
						<input type= "button" class="Menubutton" id="tab1" name= "tab1" value= "Products" 
                    		onclick= "makeactive(1);" style="font-size:14px;font-weight:bold;color:black;height:35px;
								width:200px;" />
					</li>
				</ul>
				<ul style="list-style:none;float:left;margin-top:5px;padding-left:20px;" id="tabmenu" >  
                    <li> 
                      <input type= "button" class="Menubutton" id="tab2" name= "tab2" value= "Employees" 
                    	onclick= "makeactive(2);" style="font-size:14px;font-weight:bold;color:black;list-style:none;
                        	height:35px;width:200px;" />
					</li>
				</ul>
            	<ul style="list-style:none;float:left;margin-top:5px;padding-left:20px;" id="tabmenu" > 
                	<li>
                    	<input type= "button" class="Menubutton" id="tab3" name= "tab3" value= "Special Sales" 
                    		onclick= "makeactive(3);" style="font-size:14px;font-weight:bold;color:black;list-style:none;
                        	height:35px;width:200px;" /> 
  					</li>
				</ul>
                <ul style="list-style:none;float:left;margin-top:5px;padding-left:20px;" id="tabmenu" > 
                <li> 
                    <input type= "submit" class="Menubutton" id="tab4" name= "submit" value= "Log Out" 
                    	style="font-size:14px;font-weight:bold;color:black;list-style:none;height:35px;width:200px;" />
                </li>
            </ul><br />
			</div>
			<div id="search" ></div>
		</form>
	</div>
</div>
</body>
</html>
