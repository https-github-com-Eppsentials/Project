	
<?php
date_default_timezone_set('America/Lima');
require_once ("../include/initialize.php");
  if (!isset($_SESSION['CUSID'])){
      redirect("index.php");
     }



	
// if (isset($_POST['id'])){

// if ($_POST['actions']=='confirm') {
// 							# code...
// 	$status	= 'Confirmado';	
// 	// $remarks ='Your order has been confirmado. The ordered products will be yours anytime.';
	 
// }elseif ($_POST['actions']=='cancel'){
// 	// $order = New Order();
// 	$status	= 'Cancelado';
// 	// $remarks ='Your order has been cancelado due to lack of communication and incomplete information.';
// }

// $summary = New Summary();
// $summary->ORDEREDSTATS     = $status;
// $summary->update($_POST['id']);


// }

if(isset($_POST['close'])){
	unset($_SESSION['ordernumber']);
	redirect(web_root.'index.php'); 
}

if (isset($_POST['ordernumber'])){
	$_SESSION['ordernumber'] = $_POST['ordernumber'];
}

// unsetting notify msg
$summary = New Summary();
$summary->HVIEW = 1;
$summary->update($_SESSION['ordernumber']);  

// end


$query = "SELECT * FROM `tblsummary` s ,`tblcustomer` c 
		WHERE   s.`CUSTOMERID`=c.`CUSTOMERID` and ORDEREDNUM='".$_SESSION['ordernumber']."'";
		$mydb->setQuery($query);
		$cur = $mydb->loadSingleResult();

// $query = "SELECT * FROM tblusers
// 				WHERE   `USERID`='".$_SESSION['cus_id']."'";
// 		$mydb->setQuery($query);
// 		$row = $mydb->loadSingleResult();
?>
 

<div class="modal-dialog" style="width:60%">
  <div class="modal-content">
	<div class="modal-header">
		<button class="close" id="btnclose" data-dismiss="modal" type= "button">×</button>
		 <span id="printout">
 
		<table>
			<tr>
				<td align="center"> 
				<img src="<?php echo web_root; ?>images/home/logo.png"   alt="Image">
        		</td> 
			</tr>
		</table>
		<!-- <h2 class="modal-title" id="myModalLabel">Billing Details </h2> -->
		
		 
 	 <div class="modal-body"> 
<?php 
	 $query = "SELECT * FROM `tblsummary` s ,`tblcustomer` c 
				WHERE   s.`CUSTOMERID`=c.`CUSTOMERID` and ORDEREDNUM=".$_SESSION['ordernumber'];
		$mydb->setQuery($query);
		$cur = $mydb->loadSingleResult();

		if($cur->ORDEREDSTATS=='Confirmado'){

		
		if ($cur->PAYMENTMETHOD=="Cash on Pickup") {
			 
		
?>
 	 <h4>Su pedido ha sido confirmado y listo para ser recogido</h4><br/>
		<h5>Estimado(a),</h5>
		<h5>Como ha pedido efectivo al momento de la recolección, tenga la cantidad exacta de efectivo para pagar a nuestro personal y presente estos detalles de facturación.</h5>
		 <hr/>
		 <h4><strong>Recoger Información</strong></h4>
		 <div class="row">
		 	<!-- <div class="col-md-6">
		 		<p> ORDER NUMBER : <?php echo $_SESSION['ordernumber']; ?></p>
		 		<?php 
		 			$query="SELECT sum(ORDEREDQTY) as 'countitem' FROM `tblorder` WHERE `ORDEREDNUM`='".$_SESSION['ordernumber']."'";
		 			$mydb->setQuery($query);
					$res = $mydb->loadResultList();
					?>
		 		<p>Items to be pickup : <?php
		 		foreach ( $res as $row) echo $row->countitem; ?></p> 
		 	</div> -->
		 	<div class="col-md-6">
		 	<p>Nombre : <?php echo $cur->FNAME . ' '.  $cur->LNAME ;?></p>
		 	<p>Dirección : <?php echo $cur->CUSHOMENUM . ' ' . $cur->STREETADD . ' ' .$cur->BRGYADD . ' ' . $cur->CITYADD . ' ' .$cur->PROVINCE . ' ' .$cur->COUNTRY; ?></p>
		 		<!-- <p>Contact Number : <?php echo $cur->CONTACTNUMBER;?></p> -->
		 	</div>
		 </div>
<?php 
}elseif ($cur->PAYMENTMETHOD=="Cash on Delivery"){
		 
?>
 	 <h4>Su pedido confirmado y entregado</h4><br/>
 		<h5>Estimado,</h5>
		<h5>¡Su pedido está en camino! Como ha ordenado a través de Pago contra reembolso, tenga la cantidad exacta de efectivo para nuestro proveedor.	</h5>
		 <hr/>
		 <h4><strong>Información de Entrega</strong></h4>
		 <div class="row">
		 	<div class="col-md-6">
		 		<p> Número de Pedido : <?php echo $_SESSION['ordernumber']; ?></p>

		 			<?php 
		 			$query="SELECT sum(ORDEREDQTY) as 'countitem' FROM `tblorder` WHERE `ORDEREDNUM`='".$_SESSION['ordernumber']."'";
		 			$mydb->setQuery($query);
					$res = $mydb->loadResultList();
					?>
		 		<p>Artículos a entregar: <?php
		 		foreach ( $res as $row) echo $row->countitem; ?></p> 

		 	</div>
		 	<div class="col-md-6">
		 	<p>Nombre : <?php echo $cur->FNAME . ' '.  $cur->LNAME ;?></p>
		 	<!-- <p>Address : <?php echo $cur->ADDRESS;?></p> -->
		 		<!-- <p>Contact Number : <?php echo $cur->CONTACTNUMBER;?></p> -->
		 	</div>
		 </div>
<?php 
}
}elseif($cur->ORDEREDSTATS=='Cancelado'){

	 echo "Su pedido ha sido cancelado debido a la falta de comunicación e información incompleta.";

}else{
	echo "<h5>Tu pedido está en proceso. Verifique su perfil para recibir notificaciones de confirmación.</h5>";
} 
?>
<hr/>
 <h4><strong>Información del Pedido</strong></h4>
		<table id="table" class="table">
			<thead>
				<tr>
					<!-- <th>PRODUCT</th>? -->
					<th>Producto</th>
					<!-- <th>DATE ORDER</th>  -->
					<th>Precio</th>
					<th>Cantidad</th>
					<th>Precio Total</th>
					<th></th> 
				</tr>
				</thead>
				<tbody>
 
				<?php
				 $subtot=0;
				  $query = "SELECT * 
							FROM  `tblproduct` p, tblcategory ct,  `tblcustomer` c,  `tblorder` o,  `tblsummary` s
							WHERE p.`CATEGID` = ct.`CATEGID` 
							AND p.`PROID` = o.`PROID` 
							AND o.`ORDEREDNUM` = s.`ORDEREDNUM` 
							AND s.`CUSTOMERID` = c.`CUSTOMERID` 
							AND o.`ORDEREDNUM`=".$_SESSION['ordernumber'];
				  		$mydb->setQuery($query);
				  		$cur = $mydb->loadResultList(); 
						foreach ($cur as $result) {
						echo '<tr>';  
				  		// echo '<td ><img src="'.web_root.'admin/modules/product/'. $result->IMAGES.'" width="60px" height="60px" title="'.$result->PRODUCTNAME.'"/></td>';
				  		// echo '<td>' . $result->PRODUCTNAME.'</td>';
				  		// echo '<td>'. $result->FIRSTNAME.' '. $result->LASTNAME.'</td>';
				  		echo '<td>'. $result->PRODESC.'</td>';
				  		// echo '<td>'.date_format(date_create($result->ORDEREDDATE),"M/d/Y h:i:s").'</td>';
				  		echo '<td> &#36 '. number_format($result->PROPRICE,2).' </td>';
				  		echo '<td align="center" >'. $result->ORDEREDQTY.'</td>';
				  		?>
				  		 <td> &#36 <output><?php echo  number_format($result->ORDEREDPRICE,2); ?></output></td> 
				  		<?php
				  		
				  		// echo '<td id="status" >'. $result->STATS.'</td>';
				  		// echo '<td><a  href="#"  data-id="'.$result->ORDERID.'"  class="cancel btn btn-danger btn-xs">Cancel</a>
				  		// 		<a href="#"  data-id="'.$result->ORDERID.'"   class="confirm btn btn-primary btn-xs">Confirm</a></td>';
				  		
				  		echo '</tr>';

				  		$subtot +=$result->ORDEREDPRICE;
				 
				}
				?> 
			</tbody>
		<tfoot >
		<?php 
				 $query = "SELECT * FROM `tblsummary` s ,`tblcustomer` c 
				WHERE   s.`CUSTOMERID`=c.`CUSTOMERID` and ORDEREDNUM=".$_SESSION['ordernumber'];
		$mydb->setQuery($query);
		$cur = $mydb->loadSingleResult();

		if ($cur->PAYMENTMETHOD=="Cash on Delivery") {
			# code...
			$price = $cur->DELFEE;
		}else{
			$price = 0.00;
		}


		// $tot =   $cur->PAYMENT  + $price;
		?>

	 </tfoot>
       </table> <hr/>
 		<div class="row">
		  	<div class="col-md-6 pull-left">
		  	 <div>Fecha de Orden : <?php echo date_format(date_create($cur->ORDEREDDATE),"M/d/Y h:i:s"); ?></div> 
		  		<div>Método de Pago : <?php echo $cur->PAYMENTMETHOD; ?></div>

		  	</div>
		  	<div class="col-md-6 pull-right">
		  		<p align="right">Precio Total : &#36 <?php echo number_format($subtot,2);?></p>
		  		<p align="right">Gasto de envío : &#36 <?php echo number_format($price,2); ?></p>
		  		<p align="right">Importe Total : &#36 <?php echo number_format($cur->PAYMENT,2); ?></p>
		  	</div>
		  </div>
		 
		  <?php
		  if($cur->ORDEREDSTATS=="Confirmado"){
		  ?>
		   <hr/> 
		  <div class="row">
		 		 <p>Imprima esto como comprobante de compra</p><br/>
		  	  <p>Esperamos que disfrute de sus productos comprados. ¡Que tengas un buen día!</p>
		  	  <p>Sinceramente.</p>
		  	  <h4>MoliService El Chotano E.I.R.L.</h4>
		  </div>
		  <?php }?>
  </div> 

</span>

		<div class="modal-footer">
		 <div id="divButtons" name="divButtons">
		<?php if($cur->ORDEREDSTATS!='Pendiente' || $cur->ORDEREDSTATS!='Cancelado' ){ ?> 
     
                <button  onclick="tablePrint();" class="btn btn_fixnmix pull-right "><span class="glyphicon glyphicon-print" ></span> Imprimir</button>     
             
        <?php } ?>
			<button class="btn btn-pup" id="btnclose" data-dismiss="modal" type=
			"button">Cerrar</button> 
		 </div> 
		<!-- <button class="btn btn-primary"
			name="savephoto" type="submit">Upload Photo</button> -->
		</div>
	<!-- </form> -->
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
 </div>
  <script>
function tablePrint(){ 
 // document.all.divButtons.style.visibility = 'hidden';  
    var display_setting="toolbar=no,location=no,directories=no,menubar=no,";  
    display_setting+="scrollbars=no,width=500, height=500, left=100, top=25";  
    var content_innerhtml = document.getElementById("printout").innerHTML;  
    var document_print=window.open("","",display_setting);  
    document_print.document.open();  
    document_print.document.write('<body style="font-family:verdana; font-size:12px;" onLoad="self.print();self.close();" >');  
    document_print.document.write(content_innerhtml);  
    document_print.document.write('</body></html>');  
    document_print.print();  
    //document_print.document.close(); 
    //document.all.divButtons.style.visibility = 'Show';  
   
    return false; 

    } 
 
</script>