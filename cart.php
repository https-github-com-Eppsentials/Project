<section id="cart_items">
    <div class="container">
      <div class="breadcrumbs">
        <ol class="breadcrumb">
          <li><a href="#">Casa</a></li>
          <li class="active">Carrito de Compras</li>
        </ol>
      </div>
      <div class="table-responsive cart_info"> 
        <?php  

  // if (!isset($_SESSION['USERID'])){
  //     redirect("index.php"); 
check_message();  
 //.
?>
            
                         <table  class="table table-condensed" id="table" >
                         <thead> 
                          <tr class="cart_menu"> 
                             <td  >Producto</td>
                             <td >Descripción</td>
                             <td  width="15%" >Precio</td>
                             <td  width="15%" >Cantidad</td> 
                             <td  width="15%" >Total</td>  
                          </tr>
                         </thead>  
                          
                             <?php



                              if (!empty($_SESSION['gcCart'])){ 

                                echo '<script>totalprice()</script>';

                                  $count_cart = count($_SESSION['gcCart']);

                                for ($i=0; $i < $count_cart  ; $i++) { 
 
                                       $query = "SELECT * FROM `tblpromopro` pr , `tblproduct` p , `tblcategory` c
                                                 WHERE pr.`PROID`=p.`PROID` AND  p.`CATEGID` = c.`CATEGID`  and p.`PROID` = '".@$_SESSION['gcCart'][$i]['productid']."'";
                                       $mydb->setQuery($query);
                                      $cur = $mydb->loadResultList();
                                
                                
                                 foreach ($cur as $result) {

                                ?>
                                <tr>
                                  <td>  
                                    <img src="<?php echo web_root. 'admin/products/'.$result->IMAGES; ?>"  onload="  totalprice() " width="50px" height="50px"> 
                                  <br/> 
                                        <?php    
                                          
                                              
                                            if (isset($_SESSION['CUSID'])){  

                                              echo ' <a href="'.web_root. 'customer/controller.php?action=addwish&proid='.$result->PROID.'" title="Agregar a Lista de Deseos">Agregar a Lista de Deseos</a>
             ';
                                           
                                             }else{
                                               echo   '<a href="#" title="Agregar a Lista de Deseos" class="proid"  data-target="#smyModal" data-toggle="modal" data-id="'.  $result->PROID.'">Agregar a Lista de Deseos</a>
             ';
                                            } 
                                  



                                          ?>




                                 </td>
                                  <td>  
                                    <?php echo  $result->PRODESC ; ?>
                                  </td>
                                  <td>
                                    <input type="hidden"    id ="PROPRICE<?php echo $result->PROID;  ?>" name="PROPRICE<?php echo $result->PROID; ?>" value="<?php echo  $result->PRODISPRICE ; ?>" >
                                     
                                  &#36  <?php echo  $result->PRODISPRICE ; ?>
                                  </td>
                                  <td class="input-group custom-search-form" >
                                       <input type="hidden" maxlength="3" class="form-control input-sm"  autocomplete="off"  id ="ORIGQTY<?php echo $result->PROID;  ?>" name="ORIGQTY<?php echo $result->PROID; ?>" value="<?php echo $result->PROQTY; ?>"   placeholder="Search for...">
                                        
                                        <input type="number" maxlength="3" data-id="<?php echo $result->PROID;  ?>" class="QTY form-control input-sm"  autocomplete="off"  id ="QTY<?php echo $result->PROID;  ?>" name="QTY<?php echo $result->PROID; ?>" value="<?php echo $_SESSION['gcCart'][$i]['qty']; ?>"   placeholder="Search for...">
                                        <span class="input-group-btn">
                                                <a title="Remove Item"  class="btn btn-danger btn-sm" id="btnsearch" name="btnsearch" href="cart/controller.php?action=delete&id=<?php echo $result->PROID; ?>">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </span>
                                        </td>
                                      
                                        <input type="hidden"    id ="TOT<?php echo $result->PROID;  ?>" name="TOT<?php echo $result->PROID; ?>" value="<?php echo  $result->PRODISPRICE ; ?>" >
                                   
                                     <td> &#36 <output id="Osubtot<?php echo $result->PROID ?>"><?php echo   $_SESSION['gcCart'][$i]['price'] ; ?></output></td>
                                </tr>
         
                            <?php  
                                 }
                               }
                               }else{ 
                                  echo "<h1>There is no item in the cart.</h1>";
                               } 
                            ?>  
                                
                      </table> 

     
                        <h3 align="right"> Total  &#36<span id="sum">0</span></h3> 
    </div>
  </div>  
 
</section>
<section id="do_action">
    <div class="container">
      <div class="heading">
        <h3>¿Qué te gustaría hacer después?</h3>
        <p>Elija si tiene un código de descuento o puntos de recompensa que desea usar o si desea estimar el costo de envío.</p>
      </div>
      <div class="row">
     <form action="index.php?q=orderdetails" method="post">
   <a href="index.php?q=product" class="btn btn-default check_out pull-left ">
   <i class="fa fa-arrow-left fa-fw"></i>
   Agregar nueva orden
   </a>

     <?php    
  
                     $countcart =isset($_SESSION['gcCart'])? count($_SESSION['gcCart']) : "0";
                   if ($countcart > 0){
  
                  if (isset($_SESSION['CUSID'])){  
               
                    echo '<button type="submit"  name="proceed" id="proceed" class="btn btn-default check_out btn-pup pull-right">
                            Proceder y Pagar
                            <i class="fa  fa-arrow-right fa-fw"></i>
                            </button>';
                 
                   }else{
                     echo   '<a data-target="#smyModal" data-toggle="modal" class="btn btn-default check_out signup pull-right" href="">
                              Proceder y Pagar
                              <i class="fa  fa-arrow-right fa-fw"></i>
                              </a>';
                  } 
                }



                ?>
 </form>
      </div>
    </div>
  </section><!--/#do_action-->