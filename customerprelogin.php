<?php 

ob_start();
session_start();

/*if ($_SESSION['timeout'] + 10*60 < time()) 
{	
	session_start();
	session_destroy();
	header("location:login.php");
} 
else 
{
	$_SESSION['timeout'] = time();	
}*/

/*********************************************************************************/
/* 								GET      										 */
/*********************************************************************************/		
require 'customer_common.php';

if(isset($_GET['content']))
{
	if ($_GET['content'] == 1)
	{
		productCategoryDisplay();
		return;
	}
	if ($_GET['content'] == 2)
	{
		specialSaleDisplay();
		return;
	}
	if ($_GET['content'] == 3)
	{
		shoppingCartDisplay();
		return;
	}
	if ($_GET['content'] == 4)
	{
		if(isset($_SESSION['login_flag']) && $_SESSION['login_flag']== true)
		{
			checkOutDisplay();
		}
		else
		{
			echo"Please login to proceed with check-out ";
		}
		return;
	}
	if ($_GET['content'] == 5)
	{	
		print('inside get content == 1');
		profileUpdateFormDisplay();
		return;
	}
}

/*********************************************************************************/
/* 								POST      										 */
/*********************************************************************************/	

if(isset($_POST['todo']))
{
	if (0 == strcmp($_POST['todo'],'profileUpdateFormDisplay'))
	{
		profileUpdateFormDisplay();
		return;
	}
	if (0 == strcmp($_POST['todo'],'profileUpdateFormSubmit'))
	{
		profileUpdateFormSubmit();
		return;
	}
	if (0 == strcmp($_POST['todo'],'profileDelete'))
	{
		profileDelete();
		return;
	}
	if (0 == strcmp($_POST['todo'],'productCategoryDisplay'))
	{
		print('inside todo product cat');
		productCategoryDisplay();
		return;
	}
	if (0 == strcmp($_POST['todo'],'updateCart'))
	{
		updateCart();
		return;
	}
	if (0 == strcmp($_POST['todo'],'deleteFromCart'))
	{
		deleteFromCart();
		return;
	}
}
		
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta Content-Type="text/html; charset=utf-8" />
<link href="cust.css" rel="stylesheet" type="text/css" />
<title>Customer Dashboard</title>
<script language="JavaScript" type="text/javascript" src="customer_jsutil.js"> </script>
<script language="JavaScript" type="text/javascript"> 

function makeactive(tab)
{
	alert('inside makeactive');
	document.getElementById("tab1").className = "";
	document.getElementById("tab2").className = "";
	document.getElementById("tab3").className = "";
	document.getElementById("tab4").className = "";
	document.getElementById("tab"+tab).className = "active";
	callAjaxGet('customer_prelogin.php?content= '+tab, 'content', 'getting content. Please Wait...', 'Error');
}

function productQuantityOnChange(product_id)
{
	var ischecked = document.getElementById("product_checkbox_" + product_id).checked;
	var e = document.getElementById("product_quantity_" + product_id);
	var ischecked = document.getElementById("product_checkbox_" + product_id).checked;
	if (ischecked)
	{
		var quantity = e.options[e.selectedIndex].value;
		var params = "todo=updateCart&product_id=" + product_id + "&product_quantity="+quantity;
		callAjaxPost('customer_prelogin.php', params, 'error','getting content. Please Wait...', 'Error');
	}
}

function productCheckBoxOnChange(product_id)
{
	var e = document.getElementById("product_quantity_" + product_id);
	var ischecked = document.getElementById("product_checkbox_" + product_id).checked;
	if (ischecked)
	{
		var quantity = e.options[e.selectedIndex].value;
		var params = "todo=updateCart&product_id=" + product_id + "&product_quantity="+quantity;	
	}
	else
	{
		var params = "todo=deleteFromCart&product_id=" + product_id;
	}
	callAjaxPost('customer_prelogin.php', params, 'error','getting content. Please Wait...', 'Error');
}
	
function specialSalesQuantityOnChange(ss_prod_id)
{
	var ischecked = document.getElementById("ss_checkbox_" + ss_prod_id).checked;
	var e = document.getElementById("ss_quantity_" + ss_prod_id);
	var ischecked = document.getElementById("ss_checkbox_" + ss_prod_id).checked;
	if (ischecked)
	{
		var quantity = e.options[e.selectedIndex].value;
		var params = "todo=updateCart&ss_prod_id=" + ss_prod_id + "&ss_quantity="+quantity;
		callAjaxPost('customer_prelogin.php', params, 'error','getting content. Please Wait...', 'Error');
	}
}


function specialSalesCheckBoxOnChange(ss_prod_id)
{
	var e = document.getElementById("ss_quantity_" + ss_prod_id);
	var ischecked = document.getElementById("ss_checkbox_" + ss_prod_id).checked;
	if (ischecked)
	{
		var quantity = e.options[e.selectedIndex].value;
		var params = "todo=updateCart&ss_prod_id=" + ss_prod_id + "&ss_quantity="+quantity;	
	}
	else
	{
		var params = "todo=deleteFromCart&ss_prod_id=" + ss_prod_id;
	}
	callAjaxPost('customer_prelogin.php', params, 'error','getting content. Please Wait...', 'Error');
}


</script>
</head>

<body id="Form-wrapper" onload= "makeactive(2);" >
<div class="Wrapper" >
	<div class="Header" >
    	<form action="" method="post"  style="float:left">
		<div id="menuid" >
            
            <ul style="list-style:none;float:left;margin-top:5px;padding-left:10px;" id="tabmenu" > 
                <li> 
                    <input type= "button" class="Menubutton" id="tab1" name= "tab1" value= "Product Categories" 
                    	onclick= "makeactive(1);" style="font-size:14px;font-weight:bold;color:black;list-style:none;
                        	height:35px;width:160px;" />
                </li>
            </ul>
            <ul style="list-style:none;float:left;margin-top:5px;padding-left:10px;" id="tabmenu" > 
                <li> 
                    <input type= "button" class="Menubutton" id="tab2" name= "tab2" value= "Special Sales" 
                    	onclick= "makeactive(2);" style="font-size:14px;font-weight:bold;color:black;list-style:none;
                        	height:35px;width:150px;" />
                </li>
            </ul>
            <ul style="list-style:none;float:left;margin-top:5px;padding-left:10px;" id="tabmenu" > 
                <li> 
                    <input type= "button" class="Menubutton" id="tab3" name= "tab3" value= "Shopping Cart" 
                    	onclick= "makeactive(3);" style="font-size:14px;font-weight:bold;color:black;list-style:none;
                        	height:35px;width:150px;" />
                </li>
            </ul>
            
            <ul style="list-style:none;float:left;margin-top:5px;padding-left:10px;" id="tabmenu" > 
                <li> 
                    <input type= "button" class="Menubutton" id="tab4" name= "tab4" value= "Check Out" 
                    	onclick= "makeactive(4);" style="font-size:14px;font-weight:bold;color:black;list-style:none;
                        	height:35px;width:150px;" />
                </li>
            </ul>
            <ul style="list-style:none;float:left;margin-top:5px;padding-left:10px;" id="tabmenu" > 
                <li> 
                    <a href="customer_login.php" style="font-size:14px;font-weight:bold;color:black;list-style:none;
                        	height:35px;width:150px;"> Log In </a>
                    
                </li>
            </ul>
            
		</div>
        
		<div id="content" style="top:auto;" ></div>
        <div id="error" ></div>
        </form>
	</div>
</div>
</body>
</html>
