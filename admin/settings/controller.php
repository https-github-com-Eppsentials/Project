<?php
require_once ("../../include/initialize.php");
	 

$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';

switch ($action) {
	case 'add' :
	doInsert();
	break;
	
	case 'edit' :
	doEdit();
	break; 
	case 'editStatus' :
	editStatus();
	break;
	
	case 'delete' :
	doDelete();
	break;

	case 'photos' :
	doupdateimage();
	break;

	case 'banner' :
	setBanner();
	break;

 case 'discount' :
	setDiscount();
	break;
	}

   
function doInsert(){
	if(isset($_POST['save'])){ 
  				 	 	$product = New Setting();  
						$product->PLACE 		= $_POST['PLACE']; 
						$product->BRGY 			= $_POST['BRGY']; 
						$product->DELPRICE 		= $_POST['DELPRICE']; 
						$product->create(); 
   
						message("¡Nueva ubicación creada con éxito!", "success");
						redirect("index.php"); 
		  }  
	  }
 
 
	function doEdit(){ 
		if(isset($_POST['save'])){
 
					
  				 	 	$product = New Setting();  
						$product->PLACE 		= $_POST['PLACE']; 
						$product->BRGY 			= $_POST['BRGY'];  
						$product->DELPRICE 		= $_POST['DELPRICE']; 
						$product->update($_POST['SETTINGID']);
  

			message("¡La ubicación ha sido actualizada!", "success");
			redirect("index.php");
	  }
	redirect("index.php"); 
}

 function editStatus(){
 	
	if (@$_GET['stats']=='Oculto'){
		$product = New Product();
		$product->PROSTATS	= 'Disponible';
		$product->update(@$_GET['id']);

	}elseif(@$_GET['stats']=='Disponible'){
		$product = New Product();
		$product->PROSTATS	= 'Oculto';
		$product->update(@$_GET['id']);
	}else{

		if (isset($_GET['front'])){
			$product = New Product();
			$product->FRONTPAGE	= True;
			$product->update(@$_GET['id']);

		}
	}

	redirect("index.php");

 }
		 
	 
	function setBanner(){
		$promo = New Promo();
		$promo->PROBANNER  =1;  
		$promo->update($_POST['PROID']);

	}

 	function setDiscount(){
 		if (isset($_POST['submit'])){

		$promo = New Promo();
		$promo->PRODISCOUNT  = $_POST['PRODISCOUNT']; 
		$promo->PRODISPRICE  = $_POST['PRODISPRICE']; 
		$promo->PROBANNER  =1;    
		$promo->update($_POST['PROID']);

		msgBox("El descuento se ha establecido.");

		redirect("index.php"); 
 		}
	
	}
	 
?>