<?php
   if (!isset($_SESSION['USERID'])){
      redirect(web_root."index.php");
     }

      // $autonum = New Autonumber();
      // $result = $autonum->single_autonumber(4);

?> 
 <form class="form-horizontal span6" action="controller.php?action=add" method="POST" enctype="multipart/form-data"    >
 <div class="row">
         <div class="col-lg-12">
            <h1 class="page-header">AGREGAR NUEVO PRODUCTO</h1>
          </div>
          <!-- /.col-lg-12 -->
       </div> 

              <div class="form-group">
                    <div class="col-md-8">
                      <label class="col-md-4 control-label" for=
                      "OWNERNAME">PROVEEDOR:</label>

                      <div class="col-md-8">
                            <input class="form-control input-sm" id="OWNERNAME" name="OWNERNAME" placeholder=
                            "Ingrese el nombre del proveedor" type="text" value="">
                      </div>
                    </div>
                  </div>  

                   <div class="form-group">
                    <div class="col-md-8">
                      <label class="col-md-4 control-label" for=
                      "OWNERPHONE">TELÉFONO:</label>

                      <div class="col-md-8">
                             <input class="form-control input-sm" id="OWNERPHONE" name="OWNERPHONE" placeholder=
                            "(056) 956023456" type="number" value="">
                      </div>
                    </div>
                  </div> 

                 <div class="form-group">
                    <div class="col-md-8">
                      <label class="col-md-4 control-label" for=
                      "PRODESC">DESCRIPCIÓN:</label>

                      <div class="col-md-8"> 
                      <textarea class="form-control input-sm" id="PRODESC" name="PRODESC" cols="1" rows="3" ></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-md-8">
                      <label class="col-md-4 control-label" for=
                      "CATEGORY">CATEGORÍA:</label>

                      <div class="col-md-8">
                       <select class="form-control input-sm" name="CATEGORY" id="CATEGORY">
                          <option value="None">Seleccionar Categoría</option>
                          <?php
                            //Statement
                          $mydb->setQuery("SELECT * FROM `tblcategory`");
                          $cur = $mydb->loadResultList();

                        foreach ($cur as $result) {
                          echo  '<option value='.$result->CATEGID.' >'.$result->CATEGORIES.'</option>';
                          }
                          ?>
          
                        </select> 
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-md-8">
                      <label class="col-md-4 control-label" for=
                      "ORIGINALPRICE">COSTO:</label>

                      <div class="col-md-3">
                         <input class="form-control input-sm" id="ORIGINALPRICE" name="ORIGINALPRICE" placeholder=
                            "&#36 Precio Costo" type="number" value=""  step="any">
                      </div>
                       <label class="col-md-2 control-label" for=
                      "PROPRICE">PRECIO:</label>

                      <div class="col-md-3">
                         <input class="form-control input-sm" id="PROPRICE"  step="any" name="PROPRICE" placeholder=
                            "&#36 Precio Venta " type="number" value="">
                      </div>
                    </div>
                  </div>

                   <div class="form-group">
                    <div class="col-md-8">
                      <label class="col-md-4 control-label" for=
                      "PROQTY">CANTIDAD:</label>

                      <div class="col-md-8">
                         <input class="form-control input-sm" id="PROQTY" name="PROQTY" placeholder=
                            "Stock en Inventario" type="number" value="">
                      </div>
                       
                    </div>
                  </div>

  
                  <div class="form-group">
                    <div class="col-md-8">
                      <label class="col-md-4" align = "right"for=
                      "image">CARGAR IMAGEN:</label>

                      <div class="col-md-8">
                      <input type="file" name="image" value="" id="image"/>
                      </div>
                    </div>
                  </div>
            
             <div class="form-group">
                    <div class="col-md-8">
                      <label class="col-md-4 control-label" for=
                      "idno"></label>

                      <div class="col-md-8">
                        <button class="btn  btn-primary btn-sm" name="save" type="submit" ><span class="fa fa-save fw-fa"></span> GRABAR</button>
                      </div>
                    </div>
                  </div>

               
        <div class="form-group">
                <div class="rows">
                  <div class="col-md-6">
                    <label class="col-md-6 control-label" for=
                    "otherperson"></label>

                    <div class="col-md-6">
                   
                    </div>
                  </div>

                  <div class="col-md-6" align="right">
                   

                   </div>
                  
              </div>
              </div>
          
        </form>
      

       