<?php
session_start(); //before we store information of our member, we need to start first the session
	
	//create a new function to check if the session variable member_id is on set
	function logged_in() {
		return isset($_SESSION['USERID']);
        
	}
	//this function if session member is not set then it will be redirected to login.php
	function confirm_logged_in() {
		if (!logged_in()) {?>

			<script type="text/javascript">
				window.location = "login.php";
			</script>

		<?php
		}
	}
function admin_confirm_logged_in() {
		if (@!$_SESSION['USERID']) {?>
			<script type="text/javascript">
				window.location ="login.php";
			</script>

		<?php
		}
	}

	function studlogged_in() {
		return isset($_SESSION['CUSID']);
        
	}
	function studconfirm_logged_in() {
		if (!studlogged_in()) {?>
			<script type="text/javascript">
				window.location = "index.php";
			</script>

		<?php
		}
	}

	function message($msg="", $msgtype="") {
	  if(!empty($msg)) {
	    // then this is "set message"
	    // make sure you understand why $this->message=$msg wouldn't work
	    $_SESSION['message'] = $msg;
	    $_SESSION['msgtype'] = $msgtype;
	  } else {
	    // then this is "get message"
			return $message;
	  }
	}
		function check_message(){
  
    if(isset($_SESSION['message'])){
      if(isset($_SESSION['msgtype'])){
        if ($_SESSION['msgtype']=="info"){
          echo  '<label class="alert alert-info" style="width:100%;padding:5px;">'. $_SESSION['message'] . '</label>';
           
        }elseif($_SESSION['msgtype']=="error"){
          echo  '<label class="alert alert-danger" style="width:100%;padding:5px;">' . $_SESSION['message'] . '</label>';
                  
        }elseif($_SESSION['msgtype']=="success"){
          echo  '<label class="alert alert-success" style="width:100%;padding:5px;">' . $_SESSION['message'] . '</label>';
        } 
        unset($_SESSION['message']);
        unset($_SESSION['msgtype']);
        }
  
    }

 }

function cusmsg($num=""){
  if(!empty($num)){
    $_SESSION['gcNotify'] = $num;
  }else{
    return $gcNotify;
  }
}

function notifycheck(){
  if(isset($_SESSION['gcNotify'])){
      echo $_SESSION['gcNotify'];
  }else{
      echo "";
  }
  unset($_SESSION['gcNotify']);
}


 function keyactive($key=""){
 	 if(!empty($key)) {
	    // then this is "set message"
	    // make sure you understand why $this->message=$msg wouldn't work
	    $_SESSION['active'] = $key; 
	  } else {
	    // then this is "get message"
			return $keyactive;
	  }
  
 }

 function check_active(){
 	 if(isset($_SESSION['active'])){
         switch ($_SESSION['active']) {

        case 'basicInfo' :
        $_SESSION['basicInfo']   = "active";
        break;
        case 'otherInfo' :
        $_SESSION['otherInfo']= 'active';
        break;
        
        case 'work' :
        $_SESSION['work'] = 'active' ;
        break;
      }
      }else{

      	  $active = (isset($_GET['active']) && $_GET['active'] != '') ? $_GET['active'] : '';
                 switch ($active) {

                  case 'otherInfo' :
                   $_SESSION['otherInfo']= 'active';
        			break;

                  case 'work' :
                   $_SESSION['work'] = 'active' ;
       				 break;

                  default :

                    $_SESSION['basicInfo']   = "active";
       			 break;





        // if(isset($_GET['active'])){
        //    $_SESSION['work'] = 'active' ;
        // }elseif(isset($_GET['active'])){
        //   $_SESSION['otherInfo']='active';
        // }else{
        //   $_SESSION['basicInfo']   = "active";
        // }
        
      }
 }
}
 
function product_exists($pid){
    $pid=intval($pid);
    $max=count($_SESSION['gcCart']);
    $flag=0;
    for($i=0;$i<$max;$i++){
      if($pid==$_SESSION['gcCart'][$i]['productid']){
        $flag=1;
        message("El artículo ya está en el carrito","error");
        break;
      }
    }
    return $flag;
  }
 function addtocart($pid,$q,$price){
    if($pid<1 or $q<1) return;
    if (!empty($_SESSION['gcCart'])){


    if(is_array($_SESSION['gcCart'])){
      if(product_exists($pid)) return;
      $max=count($_SESSION['gcCart']);
      $_SESSION['gcCart'][$max]['productid']=$pid;
      $_SESSION['gcCart'][$max]['qty']=$q;
      $_SESSION['gcCart'][$max]['price']=$price;
    }
    else{
     $_SESSION['gcCart']=array();
      $_SESSION['gcCart'][0]['productid']=$pid;
      $_SESSION['gcCart'][0]['qty']=$q;
      $_SESSION['gcCart'][0]['price']=$price;
    }
}else{
     $_SESSION['gcCart']=array();
      $_SESSION['gcCart'][0]['productid']=$pid;
      $_SESSION['gcCart'][0]['qty']=$q;
      $_SESSION['gcCart'][0]['price']=$price;
}
	
         message("1 Artículo se agregó al carrito","success");
}
  function removetocart($pid){
		$pid=intval($pid);
		$max=count($_SESSION['gcCart']);
		for($i=0;$i<$max;$i++){
			if($pid==$_SESSION['gcCart'][$i]['productid']){
				unset($_SESSION['gcCart'][$i]);
				break;
			}
		}
		$_SESSION['gcCart']=array_values($_SESSION['gcCart']);
	}



function product_existstwo($ordernum){
    $ordernum=intval($ordernum);
    $max=count($_SESSION['gcCart']);
    $flag=0;
    for($i=0;$i<$max;$i++){
      if($ordernum==$_SESSION['gcCart'][$i]['productid']){
        $flag=1;
        break;
      }
    }
    return $flag;
  }
 function addtocarttwo($ordernum,$tprice,$fdate,$pmethod){
 if($ordernum<1) return;
    if (!empty($_SESSION['gcCarttwo'])){
     if(product_existstwo($ordernum)) return;

    if(is_array($_SESSION['gcCarttwo'])){
     
      $max=count($_SESSION['gcCart']);
      $_SESSION['gcCarttwo'][$max]['ordernum']=$ordernum;
      $_SESSION['gcCarttwo'][$max]['tprice']=$tprice;
      $_SESSION['gcCarttwo'][$max]['fdate']=$fdate;
      $_SESSION['gcCarttwo'][$max]['pmethod']=$pmethod;
    }
    else{
      $_SESSION['gcCarttwo']=array();
      $_SESSION['gcCarttwo'][0]['ordernum']=$ordernum;
      $_SESSION['gcCarttwo'][0]['tprice']=$tprice;
      $_SESSION['gcCarttwo'][0]['fdate']=$fdate;
      $_SESSION['gcCarttwo'][0]['pmethod']=$pmethod;
    }
}else{
      $_SESSION['gcCarttwo']=array();
      $_SESSION['gcCarttwo'][0]['ordernum']=$ordernum;
      $_SESSION['gcCarttwo'][0]['tprice']=$tprice;
      $_SESSION['gcCarttwo'][0]['fdate']=$fdate;
      $_SESSION['gcCarttwo'][0]['pmethod']=$pmethod;
}
}
  function removetocarttwo($pid){
		$pid=intval($pid);
		$max=count($_SESSION['gcCarttwo']);
		for($i=0;$i<$max;$i++){
			if($pid==$_SESSION['gcCarttwo'][$i]['productid']){
				unset($_SESSION['gcCarttwo'][$i]);
				break;
			}
		}
		$_SESSION['gcCart']=array_values($_SESSION['gcCarttwo']);
	}


function header_subheader($header,$subheader){

  $setheader = (isset($header) && $header != '') ? $header : '';

switch ($setheader) {
 

  case 'product' :
       echo $title="Productos"  . (isset($subheader) ?  '  |  ' .$subheader: '' );   
   
  case 'cart' :
       echo $title="Lista de Compra";   
    break;
  case 'profile' :
      echo  $title="Perfil";  
    break;
  case 'orderdetails' : 
    echo $title = "Lista de Compra/Detalle Pedido";
 
     break;

  case 'billing' :   
      echo $title = "Lista de Compra/Detalle Pedido/Detalle Facturación";
    break;

  case 'contact' :
      echo  $title="Contáctanos";   
    break;
  case 'single-item' :
      echo  $ $title="Productos"  . (isset($subheader) ?  '  |  ' .$subheader: '' ); 
    break;
  default :
   echo   $title="Inicio";  
  
}
}



?>
