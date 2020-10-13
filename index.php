<?php
require_once ("include/initialize.php");
// if(isset($_SESSION['IDNO'])){
// 	redirect(web_root.'index.php');

// }
date_default_timezone_set('America/Lima');
$content='home.php';
$view = (isset($_GET['q']) && $_GET['q'] != '') ? $_GET['q'] : '';

//Se va a decidir que tipo de vista se inicializa 
 

switch ($view) {
 

	case 'product' :
        $title="Productos";	//Products
		$content='menu.php';		
		break;
 	case 'cart' :
        $title="Lista de carro";	//Cart List
		$content='cart.php';		
		break;
 	case 'profile' :
        $title="Perfil";//Profile
		$content='customer/profile.php';		
		break;

	case 'trackorder' :
        $title="Orden de pista"; //Track Order
		$content='customer/trackorder.php';		
		break;

	case 'orderdetails' :  

         If(!isset($_SESSION['orderdetails'])){
         $_SESSION['orderdetails'] = "Detalle Pedido";
		} 
		$content='customer/orderdetails.php';	


	if( isset($_SESSION['orderdetails'])){
      if (@count($_SESSION['orderdetails'])>0){
        	$title = 'Lista de Carro' . '| <a href="">Detalle Pedido</a>';
		      }
		    } 
		break;

	case 'billing' : 	
	 If(!isset($_SESSION['billingdetails'])){
         $_SESSION['billingdetails'] = "Detalle Pedido";
		} 
		$content='customer/customerbilling.php';	
		if( isset($_SESSION['billingdetails'])){
      if (@count($_SESSION['billingdetails'])>0){
        	$title = 'Lista de carro' . '| <a href="">Detalle Facturación</a>';
		      }
		    } 	
		break;

	case 'contact' :
        $title="Contáctenos";	
		$content='contact.php';		
		break;
 	case 'single-item' :
        $title="Productos";	
		$content='single-item.php';		
		break;

	case 'recoverpassword' :
        $title="Recuperar password";	
		$content='passwordrecover.php';		
		break;
	default :
	    $title="Inicio";	
		$content ='home.php';		

}
 
require_once("theme/templates.php"); 
?>

