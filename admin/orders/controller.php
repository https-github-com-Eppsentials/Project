

<?php
require_once ("../../include/initialize.php");
	 if (!isset($_SESSION['USERID'])){
      redirect(web_root."admin/index.php");
     }

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

	// case 'photos' :
	// doupdateimage();
	// break;

	case 'cartadd' :
	cartInsert();
	break;

	case 'cartedit' :
	cartEdit();
	break;

	case 'cartdelete' :
	cartDelete();
	break;


	case 'processorder' :
	processorder();
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
				message("Imagen no seleccionada", "error");
				redirect("index.php?view=add");
		}else{
	 
				@$file=$_FILES['image']['tmp_name'];
				@$image= addslashes(file_get_contents($_FILES['image']['tmp_name']));
				@$image_name= addslashes($_FILES['image']['name']); 
				@$image_size= getimagesize($_FILES['image']['tmp_name']);

			if ($image_size==FALSE || $type=='video/wmv') {
				message("El archivo cargado no es una imagen", "error");
				redirect("index.php?view=add");
			}else{
					//uploading the file
					move_uploaded_file($temp,"uploaded_photos/" . $myfile);
		 	
					if ($_POST['PRODUCTNAME'] == "" OR $_POST['QTY'] == "" OR $_POST['PRICE'] == "") {
					$messageStats = false;
					message("Todos los campos son requeridos","error");
					redirect('index.php?view=add');
					}else{	

						$product = new Product();
				       	$product_name 	= $_POST['PRODUCTNAME'];

						$res = $product->find_all_products($product_name);
						
						
						if ($res >=1) {
							message("El nombre del producto ya existe", "error");
							redirect("index.php?view=add");
						}else{

						$product = New Product();
						$product->PRODUCTNAME 		= $_POST['PRODUCTNAME'];
						$product->IMAGE 			= $location;
						$product->PRODUCTTYPE		= $_POST['PRODUCTTYPE'];			
						$product->ORIGINID			= $_POST['ORIGIN'];
						$product->CATEGORYID		= $_POST['CATEGORY'];
						$product->QTY				= $_POST['QTY'];
						$product->PRICE				= $_POST['PRICE'];
						$product->DESCRIPTION		= $_POST['DESCRIPTION'];
						$product->create();

						message("Nuevo [". $_POST['PRODUCTNAME'] ."] creado satisfactoriamente!", "success");
						redirect("index.php");
						}
							
					}
			}
			 
		}


	  }
}


	function doEdit(){
		global $mydb; 
		 $delivered = "";
		if ($_GET['actions']=='confirm') {
							# code...
				$status	= 'Confirmado';	
				$remarks ='Su orden ha sido confirmada.';
				$delivered = Date('Y-m-d');

		}elseif ($_GET['actions']=='deliver') {
							# code...
				$status	= 'Entregado';	
				$remarks ='Su orden ha sido entregada.';
				$delivered = Date('Y-m-d');
				 
		}elseif ($_GET['actions']=='cancel'){
			// $order = New Order();
				$status	= 'Cancelado';
				$remarks ='Su pedido ha sido cancelado por falta de comunicación e información incompleta.';
		}
			
			$order = New Order();
			$order->STATS       = $status;
			$order->pupdate($_GET['id']);

			$summary = New Summary();
			$summary->ORDEREDSTATS       = $status;
			$summary->ORDEREDREMARKS     = $remarks;
			$summary->CLAIMEDADTE 		 = $delivered;
			$summary->HVIEW 			 = 0;
			$summary->update($_GET['id']);


			// $customer = New customer;
  	// 		$res = $customer->single_customer($_GET['customerid']); 

  			$query = "SELECT * FROM `tblsummary` s ,`tblcustomer` c 
				WHERE   s.`CUSTOMERID`=c.`CUSTOMERID` and ORDEREDNUM=".$_GET['id'];
			$mydb->setQuery($query);
			$cur = $mydb->loadSingleResult();
 

	        $sql = "INSERT INTO `messageout` (`Id`, `MessageTo`, `MessageFrom`, `MessageText`) VALUES (Null, '".$cur->PHONE."','Janno','FROM Bachelor of Science and Entrepreneurs : Your order has been '".$status. "'. The amount is '".$cur->PAYMENT. "')";
	        $mydb->setQuery($sql);
	        $mydb->executeQuery();



			$query = "SELECT * 
				FROM  `tblproduct` p,`tblorder` o,  `tblsummary` s
				WHERE  p.`PROID` = o.`PROID` 
				AND o.`ORDEREDNUM` = s.`ORDEREDNUM`  
				AND o.`ORDEREDNUM`=".$_GET['id'];
	  		$mydb->setQuery($query);
	  		$cur = $mydb->loadResultList(); 
			foreach ($cur as $result) {
			 
	  		 $sql = "INSERT INTO `messageout` (`Id`, `MessageTo`, `MessageFrom`, `MessageText`) VALUES (Null, '".$result->OWNERPHONE."','Janno','FROM Bachelor of Science and Entrepreneurs : Your  product has been ordered'. The amount is '".$result->PAYMENT. "')";
	        $mydb->setQuery($sql);
	        $mydb->executeQuery();
	 
			}
      

			message("El Pedido ha sido ".$summary->ORDEREDSTATS."!", "success");
			redirect("index.php");
		
	}
	 
	function doDelete(){

	if (isset($_POST['selector'])==''){
		message("Primero seleccione las filas antes de eliminar","info");
		redirect('index.php');
	}else{

		$id = $_POST['selector'];
		$key = count($id);

		for($i=0;$i<$key;$i++){

			$order = New Order();
			$order->pdelete($id[$i]);

			$payment = New Payment();
			$payment->delete($id[$i]);

			message("El producto ha sido eliminado","info");
			redirect('index.php?view=add');
		}

	}
	}
 
function cartInsert(){
	 

   if(isset($_GET['id'])){
    $pid= $_GET['id'];
    $price= $_GET['price'];

      addtocart($pid,1,$price);

			message("1 artículo ha sido agregado al carrito", "success");
			redirect("index.php?view=add");
			
		}
		 

	}

	function cartEdit(){

 

    $max=count($_SESSION['fixnmix_cart']);
    for($i=0;$i<$max;$i++){

      $pid=$_SESSION['fixnmix_cart'][$i]['productid'];

      $qty=intval(isset($_REQUEST['QTY'.$pid]) ? $_REQUEST['QTY'.$pid] : "");
       $price=intval(isset($_REQUEST['TOT'.$pid]) ? $_REQUEST['TOT'.$pid] : "");

     
      if($qty>0 && $qty<=9999){
      	// la pa natapos... price

        $_SESSION['fixnmix_cart'][$i]['qty']=$qty;
        $_SESSION['fixnmix_cart'][$i]['price']=$price;
      }
     
    }
 
			message("El carrito ha sido actualizado", "success");
			redirect("index.php?view=add");
  
	}


	function cartDelete(){
	 
 
		if(isset($_GET['id'])) {
		removetocart($_GET['id']);
		}else{
		unset($_SESSION['fixnmix_cart']);
		}
			

		message("1 artículo ha sido removido del carro","info");
		 redirect('index.php?view=addtocart');
		 

		
	}

	function processorder(){

 

			$count_cart = count($_SESSION['fixnmix_cart']);
             for ($i=0; $i < $count_cart  ; $i++) { 
			$order = New Order();
			$order->PRODUCTID		= $_SESSION['fixnmix_cart'][$i]['productid'];
			$order->DATEORDER		=  date("Y-m-d h:i:s");
			$order->O_QTY			= $_SESSION['fixnmix_cart'][$i]['qty'];
			$order->O_PRICE			= $_SESSION['fixnmix_cart'][$i]['price'];
			$order->ORDERTYPE 		=$_SESSION['paymethod'];
			$order->DATECLAIM		= date("Y-m-d h:i:s");
			$order->STATS 			= 'Confirmado';			
			$order->ORDERNUMBER		= $_SESSION['ORDERNUMBER'];
			$order->CUSTOMERID		=   $_SESSION['CUSTOMERID'] ;
		  	$order->create(); 


		  	$product = New Product();			 
			$product->updateqty($_SESSION['fixnmix_cart'][$i]['productid'],$_SESSION['fixnmix_cart'][$i]['qty']);
		 
		  }

		  $payment = New Payment();
		  $payment->ORDERNUMBER			=  $_SESSION['ORDERNUMBER'] ;
		  $payment->CUSTOMERID			=   $_SESSION['CUSTOMERID'] ;
		  $payment->DATEORDER			=  date("Y-m-d h:i:s");	
		  $payment->PAYMENTMETHOD		= $_SESSION['paymethod'];
		  $payment->CLAIMDATE			= date("Y-m-d h:i:s");	
		  $payment->TOTALPRICE			=   $_SESSION['alltot'];	
		  $payment->STATS 				= 'Confirmado';
		  $payment->REMARKS 			= '';
		  $payment->create();

		  $autonum = New Autonumber(); 
		  $autonum->auto_update(3);

		  	$customer = New Customer();
			$customer->CUSTOMERID 		=  $_SESSION['CUSTOMERID'];
			$customer->FIRSTNAME 		= $_SESSION['FIRSTNAME'];
			$customer->LASTNAME 		= $_SESSION['LASTNAME'];
			// $customer->CITYADDRESS 		= $_POST['CITYADDRESS'];
			$customer->ADDRESS 			= $_SESSION['ADDRESS'];
			$customer->CONTACTNUMBER 	= $_SESSION['CONTACTNUMBER'];
			// $customer->ZIPCODE 			= $_POST['ZIPCODE'];
			// $customer->IMAGE 			= $location;
			$customer->create();


			$user = New User();
			$user->USERID			=	 $_SESSION['CUSTOMERID'];
			$user->NAME				=	$_SESSION['FIRSTNAME']. ' ' .$_SESSION['LASTNAME'];
			$user->UEMAIL			=	 $_SESSION['CUSTOMERID'];
			$user->PASS				=	sha1(1234);						
			$user->TYPE				=	'Customer';
			$user->create();

			$autonum = New Autonumber(); 
			$autonum->auto_update(1);


		 // 	unset($_SESSION['fixnmix_cart']);
		 // 	unset($_SESSION['FIRSTNAME']);
			// unset($_SESSION['LASTNAME']);
			// unset($_SESSION['ADDRESS']);
 		// 	unset($_SESSION['CONTACTNUMBER']);
			// unset($_SESSION['CLAIMEDDATE']);
			// unset($_SESSION['CUSTOMERID']); 
			// unset($_SESSION['paymethod']) ;
			// unset($_SESSION['ORDERNUMBER']);
 		// 	unset($_SESSION['alltot']);
			message("NUEVO PEDIDO GENERADO SATISFACTORIAMENTE", "success"); 		 
			redirect("index.php?view=billing");

	}
 
?>