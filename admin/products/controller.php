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
		
	 

			$errofile = $_FILES['image']['error'];
			$type = $_FILES['image']['type'];
			$temp = $_FILES['image']['tmp_name'];
			$myfile =$_FILES['image']['name'];
		 	$location="uploaded_photos/".$myfile;


		if ( $errofile > 0) {
				message("¡Imagen no seleccionada!", "error");
				redirect("index.php?view=add");
		}else{
	 
				@$file=$_FILES['image']['tmp_name'];
				@$image= addslashes(file_get_contents($_FILES['image']['tmp_name']));
				@$image_name= addslashes($_FILES['image']['name']); 
				@$image_size= getimagesize($_FILES['image']['tmp_name']);

			if ($image_size==FALSE || $type=='video/wmv') {
				message("¡Archivo cargado no es una imagen!", "error");
				redirect("index.php?view=add");
			}else{
					//uploading the file
					move_uploaded_file($temp,"uploaded_photos/" . $myfile);
		 	
					if ($_POST['PRODESC'] == "" OR $_POST['PROPRICE'] == "") {
					$messageStats = false;
					message("Todos los campos son requeridos!","error");
					redirect('index.php?view=add');
					}else{	

			 
						$autonumber = New Autonumber();
						$res = $autonumber->set_autonumber('PROID'); 
  				 	 	$product = New Product(); 
  				 	 	$product->PROID 		= $res->AUTO; 
						$product->OWNERNAME 		= $_POST['OWNERNAME']; 
						$product->OWNERPHONE 		= $_POST['OWNERPHONE'];
						$product->IMAGES 		= $location; 
						$product->PRODESC 		= $_POST['PRODESC'];
						$product->CATEGID	    = $_POST['CATEGORY'];
						$product->PROQTY		= $_POST['PROQTY'];
						$product->ORIGINALPRICE	= $_POST['ORIGINALPRICE']; 
						$product->PROPRICE		= $_POST['PROPRICE']; 
						$product->PROSTATS		= 'Available';
						$product->create();
						// }

 

						$promo = New Promo();  
						$promo->PROID		= $res->AUTO;  
						$promo->PRODISPRICE	= $_POST['PROPRICE'];     
						$promo->create(); 
						$autonumber = New Autonumber();
						$autonumber->auto_update('PROID'); 
						message("¡EL NUEVO PRODUCO HA SIDO REGISTRADO!", "success");
						redirect("index.php");
						}
							
					}
			}
			 
		  }


	  }
 
 
	function doEdit(){
		if (@$_GET['stats']=='NotAvailable'){
			$product = New Product();
			$product->PROSTATS	= 'Available';
			$product->update(@$_GET['id']);

		}elseif(@$_GET['stats']=='Available'){
			$product = New Product();
			$product->PROSTATS	= 'NotAvailable';
			$product->update(@$_GET['id']);
		}else{

		if (isset($_GET['front'])){
			$product = New Product();
			$product->FRONTPAGE	= True;
			$product->update(@$_GET['id']);

		}
	}



		if(isset($_POST['save'])){
 
						$product = New Product();
						// $product->PROMODEL 		= $_POST['PROMODEL']; 
						// $product->PRONAME 		= $_POST['PRONAME']; 
						$product->OWNERNAME 		= $_POST['OWNERNAME']; 
						$product->OWNERPHONE 		= $_POST['OWNERPHONE']; 
						$product->PRODESC 		= $_POST['PRODESC'];
						$product->CATEGID	    = $_POST['CATEGORY'];
						$product->PROQTY		= $_POST['PROQTY'];
						$product->ORIGINALPRICE	= $_POST['ORIGINALPRICE']; 
						$product->PROPRICE		= $_POST['PROPRICE'];  
						$product->update($_POST['PROID']);
  

			message("¡EL PRODUCTO HA SIDO ACTUALIZADO!", "success");
			redirect("index.php");
	  }
	redirect("index.php"); 
}

	function doDelete(){

 
 

		if (isset($_POST['selector'])==''){
			message("¡SELECCIONA LAS FILAS PRIMERO ANTES DE ELIMINAR!","error");
			redirect('index.php');
			}else{

			$id = $_POST['selector'];
			$key = count($id);

			for($i=0;$i<$key;$i++){ 

			$product = New Product();
			$product->delete($id[$i]);
 

			$stockin = New StockIn();
			$stockin->delete($id[$i]);

			$promo = New Promo();   
			$promo->delete($id[$i]);

			message("EL PRODUCTO HA SIDO ELIMINADO!","info");
			redirect('index.php');

			}
		}

	}
		 
	function doupdateimage(){
 
			$errofile = $_FILES['photo']['error'];
			$type = $_FILES['photo']['type'];
			$temp = $_FILES['photo']['tmp_name'];
			$myfile =$_FILES['photo']['name'];
		 	$location="uploaded_photos/".$myfile;


		if ( $errofile > 0) {
				message("¡IMAGEN NO SELECCIONADA!", "error");
				redirect("index.php?view=view&id=". $_POST['proid']);
		}else{
	 
				@$file=$_FILES['photo']['tmp_name'];
				@$image= addslashes(file_get_contents($_FILES['photo']['tmp_name']));
				@$image_name= addslashes($_FILES['photo']['name']); 
				@$image_size= getimagesize($_FILES['photo']['tmp_name']);

			if ($image_size==FALSE ) {
				message("¡EL ARCHIVO CARGADO NO ES UNA IMAGEN!", "error");
				redirect("index.php?view=view&id=". $_POST['proid']);
			}else{
					//uploading the file
					move_uploaded_file($temp,"uploaded_photos/" . $myfile);
		 	
					 

						$product = New Product();
						$product->IMAGES 			= $location;
						$product->update($_POST['proid']); 

						redirect("index.php");
						 
							
					}
			}
			 
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
	function removeDiscount(){
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