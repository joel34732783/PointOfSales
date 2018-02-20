	<!--Inicio: Documentos requeridos -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="http://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<?php
  include_once('inc/conectar.php');
  include_once('inc/classes.php');
  include_once('inc/classesExclusivas.php');
  $_SESSION['actionsBack']= $_SERVER['REQUEST_URI'];
  $bancos     = new bancos;
  $chequesE   = new chequesE;
 
  $fecha = $_POST['fecha'];

  if($fecha=="0")
  {
    $fecDesde=0;
    $fecHasta=0;
  }
  else
  {
    $reciveFecha=$_POST['fecha'];
    $fechaSinEspacios=str_replace(" ","",$reciveFecha);
    $fechaSinEspacios2=substr($fechaSinEspacios, 4);
    $fechaDividida=explode("-",$fechaSinEspacios2);

    $fechaInicio=$fechaDividida[0];
    $fechaInicioCambioDeSignos=str_replace( "/" , "-" ,$fechaInicio);
    $fechaInicioFormateda=date("Y-m-d",strtotime($fechaInicioCambioDeSignos));
    $fecDesde=$fechaInicioFormateda;

    $fechafin=$fechaDividida[1];
    $fechafinCambioDeSignos=str_replace( "/" , "-" ,$fechafin);
    $fechaFinFormateda=date("Y-m-d",strtotime($fechafinCambioDeSignos));
    $fecHasta=$fechaFinFormateda;
  }  


  if ($_POST['ID_banB']=='0') 
  { 
    $ID_ban = "";
  }
  else
  {
    $ID_ban = "AND cheques.ID_ban=".$_POST['ID_banB'];
  }  
  if ($_POST['che_libradorB']=='0') 
  {
     $che_librador = "";
  }
  else
  {
    $che_librador = "AND che_librador='". $_POST['che_libradorB']."'";
  }  
  if ($_POST['che_tipoB']=='0') 
  {
   
    $che_tipo = "";
  }
  else
  {
     $che_tipo = "AND che_tipo='". $_POST['che_tipoB']."'";
  }  

  if ($_POST['ID_che']=='0') 
  {
    $ID_che = "";
  }
  else
  {
     $ID_che = "AND cheques.ID_che='". $_POST['ID_che']."'";
  }  

?>
            <table id="listadoCheques" class="table table-striped table-bordered" cellspacing="0" style="cursor: pointer;">
                <thead>
                    <tr>
                        <th>FECHA</th>
                        <th>Nº</th>
                        <th>TIPO</th>
                        <th>BANCO</th>
                        <th>LIBRADOR</th>
                        <th>BENEFICIARIO</th>
                        <th>MONTO</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>FECHA</th>
                        <th>Nº</th>
                        <th>TIPO</th>
                        <th>BANCO</th>
                        <th>LIBRADOR</th>
                        <th>BENEFICIARIO</th>
                        <th>MONTO</th>
                    </tr>
                </tfoot>
                <tbody>
                  <?php 

                  if ($ID_che!='') 
                          {
                            $sql_cheques='SELECT * FROM cheques, bancos WHERE cheques.ID_ban=bancos.ID_ban '.$ID_che.'';
                          }
                          else
                          {
                             $sql_cheques='SELECT * FROM cheques, bancos WHERE cheques.ID_ban=bancos.ID_ban AND che_fecha BETWEEN "'.$fecDesde.'" AND "'.$fecHasta.'" '.$ID_ban.' '.$che_librador.' '.$che_tipo.' ORDER BY che_fecha DESC';
                          } 
                     $get_chequesE =mysql_query($sql_cheques);
                    //$get_chequesE=$chequesE->get_chequesFiltrosE($fecDesde, $fecHasta, $ID_ban, $che_librador, $che_tipo, $ID_che);
                    $num_get_chequesE=mysql_num_rows($get_chequesE);
                    for ($countCheques=0; $countCheques < $num_get_chequesE; $countCheques++) 
                    { 
                      $assoc_get_chequesE=mysql_fetch_assoc($get_chequesE);

                      if ($assoc_get_chequesE['che_tipo']) 
                      {
                          
                      }
                      /* Inicio Modal nuevo cheque */                          
                        echo '<div class="modal fade" id="VerCheque'.$assoc_get_chequesE['ID_che'].'" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" >
                                <div class="modal-dialog modal-lg" role="document" >
                                  <div class="modal-content">
                                     <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel"><div class="alert alert-dismissible alert-info" style="text-align: center;">
                                          <h3><i class="material-icons">list</i> CHEQUE '.$assoc_get_chequesE['che_num'].'</h3>
                                        </div> </h4>
                                      </div>
                                      <div class="modal-body" style="text-align:center;">
                                      <div class="col-md-12" id="editarCartel'.$assoc_get_chequesE['ID_che'].'" style="display:none;"></div>
                                      <input hidden type="text" name="action" value="modificarCheque">
                                      <input hidden type="text" name="ID_che'.$assoc_get_chequesE['ID_che'].'" id="Input_ID_che'.$assoc_get_chequesE['ID_che'].'" value="'.$assoc_get_chequesE['ID_che'].'">

                                          <div class="col-md-12" style="border: 2px solid #333; text-align:center;">
                                           <div class="col-md-12" style="margin-top:10px;">
                                              <div class="col-md-4">
                                                <img src="'.$assoc_get_chequesE['ban_logo'].'" style="width:160px;" id="ID_ban'.$assoc_get_chequesE['ID_che'].'">
                                                  <div class="form-group" id="Edit_ID_ban'.$assoc_get_chequesE['ID_che'].'" style="display: none">
                                                   <label class="control-label"><i class="material-icons">account_balance</i> BANCO</label>
                                                    <select class="form-control" id="Input_ID_ban'.$assoc_get_chequesE['ID_che'].'" name="ID_ban">
                                                    <option selected value="'.$assoc_get_chequesE['ID_ban'].'">'.$assoc_get_chequesE['ban_desc'].'</option>';
                                                      $get_bancos=$bancos->get_bancos();
                                                      $num_get_bancos=mysql_num_rows($get_bancos);
                                                      for ($countBancos=0; $countBancos < $num_get_bancos; $countBancos++) 
                                                      { 
                                                        $assoc_get_bancos=mysql_fetch_assoc($get_bancos);
                                                        echo "<option value='".$assoc_get_bancos['ID_ban']."'>".$assoc_get_bancos['ban_desc']."</option>";
                                                      }
                                              echo '</select>
                                              </div>
                                              </div>
                                              <div class="col-md-4">
                                                <H3 id="che_fecha'.$assoc_get_chequesE['ID_che'].'">'.$assoc_get_chequesE['che_fecha'].'</H3>
                                                 <div class="form-group" id="Edit_che_fecha'.$assoc_get_chequesE['ID_che'].'" style="display:none">
                                                  <label for="librador"><i class="material-icons">date_range</i> FECHA</label>
                                                  <input type="date" class="form-control" id="Input_che_fecha'.$assoc_get_chequesE['ID_che'].'" name="che_fecha" value="'.$assoc_get_chequesE['che_fecha'].'">
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                   <H3 id="che_num'.$assoc_get_chequesE['ID_che'].'">Nº '.$assoc_get_chequesE['che_num'].'</H3>
                                                   <div class="form-group" id="Edit_che_num'.$assoc_get_chequesE['ID_che'].'" style="display:none">
                                                      <label for="che_num "><i class="material-icons">fingerprint</i> NUMERO</label>
                                                      <input type="text" class="form-control" id="Input_che_num'.$assoc_get_chequesE['ID_che'].'" name="che_num" placeholder="" value="'.$assoc_get_chequesE['che_num'].'" required>
                                                    </div>
                                              </div>
                                            </div>  
                                            <div class="col-md-12" style="text-align:left; margin-top:10px; border-bottom: 1px solid #000;">
                                              <H4 id="che_beneficiario'.$assoc_get_chequesE['ID_che'].'"><strong>PAGUESE A:</strong> &nbsp&nbsp&nbsp&nbsp';

                                              if($assoc_get_chequesE['che_tipo']=="AL BENEFICIARIO" OR $assoc_get_chequesE['che_tipo']=="DE CAJA" OR $assoc_get_chequesE['che_tipo']=="DE VENTANILLA")
                                              {
                                                echo $assoc_get_chequesE['che_beneficiario'];
                                              }
                                              if ($assoc_get_chequesE['che_tipo']=="DE VIAJERO" OR $assoc_get_chequesE['che_tipo']=="A LA ORDEN") 
                                              {
                                                echo "A LA ORDEN";
                                              }

                                          echo '</H4>

                                          <div class="form-group" style="display:none;" id="Edit_che_beneficiario'.$assoc_get_chequesE['ID_che'].'">
                                            <label for="librador"><i class="material-icons">face</i> BENEFICIARIO</label>
                                            <input type="text" class="form-control" id="Input_che_beneficiario'.$assoc_get_chequesE['ID_che'].'" name="che_beneficiario">
                                          </div>

                                            </div>
                                            <div class="col-md-12" style="text-align:left; margin-top:5px; border-bottom: 1px solid #000;">
                                              <H4 id="che_importe'.$assoc_get_chequesE['ID_che'].'"><strong>LA SUMA DE:</strong> &nbsp&nbsp&nbsp&nbsp$ '.$assoc_get_chequesE['che_importe'].'</H4>
                                                 <div class="form-group" id="Edit_che_importe'.$assoc_get_chequesE['ID_che'].'" style="display:none;">
                                                      <label class="control-label"><i class="material-icons">monetization_on</i> IMPORTE</label>
                                                      <div class="input-group">
                                                        <span class="input-group-addon">$</span>
                                                      <input type="text" name="che_importe" id="Input_che_importe'.$assoc_get_chequesE['ID_che'].'" class="form-control" aria-label="Amount (to the nearest dollar)" placeholder="00.00" value="'.$assoc_get_chequesE['che_importe'].'" required>
                                                  </div>
                                                </div>
                                            </div>
                                             <div class="col-md-12" style="text-align:right; margin-top:20px; border-bottom: 1px solid #000;">
                                             <div class="col-md-4" style="border: 2px solid #000;">';
                                              echo '<h5 id="che_tipo'.$assoc_get_chequesE['ID_che'].'" >'.$assoc_get_chequesE['che_tipo'].'</h5>';

                                          echo '<div class="form-group" id="Edit_che_tipo'.$assoc_get_chequesE['ID_che'].'" style="display:none">
                                                 <label class="control-label"><i class="material-icons">bookmark_border</i> TIPO</label>
                                                  <select class="form-control" id="Select_che_tipo'.$assoc_get_chequesE['ID_che'].'" name="che_tipo">
                                                    <option selected value="'.$assoc_get_chequesE['che_tipo'].'">'.$assoc_get_chequesE['che_tipo'].'</option>
                                                    <option value="CRUZADOS">CRUZADOS</option>
                                                    <option value="CERTIFICADO">CERTIFICADO</option>
                                                    <option value="AL BENEFICIARIO">AL BENEFICIARIO</option>
                                                    <option value="DE CAJA">DE CAJA</option>
                                                    <option value="DE VENTANILLA">DE VENTANILLA</option>
                                                    <option value="DE VIAJERO">DE VIAJERO</option>
                                                    <option value="A LA ORDEN">A LA ORDEN</option>
                                                  </select>
                                                </div>'; 
                                            echo '</div>
                                              <div class="col-md-8">
                                                <H3 id="che_librador'.$assoc_get_chequesE['ID_che'].'">'.$assoc_get_chequesE['che_librador'].'</H3>
                                                <div class="form-group" id="Edit_che_librador'.$assoc_get_chequesE['ID_che'].'" style="display:none;">
                                                  <label for="librador"><i class="material-icons">account_circle</i> LIBRADOR</label>
                                                  <input type="text" class="form-control" id="Input_che_librador'.$assoc_get_chequesE['ID_che'].'" name="che_librador" placeholder="Librador" value="'.$assoc_get_chequesE['che_librador'].'" required>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                       </div>
                                      <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                                      <div class="modal-footer">
                                           <div class="col-md-12" style="text-align:center; margin-top:10px;">
                                           <div class="col-md-6">
                                              <button class="btn btn-primary" id="editar'.$assoc_get_chequesE['ID_che'].'"><i class="material-icons">edit</i></button> 
                                              <button class="btn btn-primary" id="ver'.$assoc_get_chequesE['ID_che'].'" style="display:none"><i class="material-icons">visibility</i></button>    
                                              <button class="btn btn-success" id="salvar'.$assoc_get_chequesE['ID_che'].'" style="display:none"><i class="material-icons">save</i>GUARDAR CAMBIOS</button>   
                                            
                                           </div> 
                                           <div class="col-md-6">
                                              <button class="btn btn-danger"><i class="material-icons">delete_forever</i></button>      
                                           </div> 
                                        </div>
                                    </div>
                                  </div>
                                </div>';
                            /* Fin Modal nuevo cheque */

                       echo"<tr data-toggle='modal' data-placement='top' data-target='#VerCheque".$assoc_get_chequesE['ID_che']."'>";
                          echo"<th>".$assoc_get_chequesE['che_fecha']."</th>";
                          echo"<th>".$assoc_get_chequesE['che_num']."</th>";
                          echo"<th>".$assoc_get_chequesE['che_tipo']."</th>";
                          echo"<th><img src='".$assoc_get_chequesE['ban_logo']."' style='width:80px;'></th>";
                          echo"<th>".$assoc_get_chequesE['che_librador']."</th>";
                          echo"<th>".$assoc_get_chequesE['che_beneficiario']."</th>";
                          echo"<th>$ ".$assoc_get_chequesE['che_importe']."</th>";
                         
                      echo"</tr>";

                      echo '<script>$("#editar'.$assoc_get_chequesE['ID_che'].'").click(function(){
                        $("#Edit_ID_ban'.$assoc_get_chequesE['ID_che'].'").fadeIn(500);
                        $("#ID_ban'.$assoc_get_chequesE['ID_che'].'").fadeOut(500);
                        $("#che_fecha'.$assoc_get_chequesE['ID_che'].'").fadeOut(500);
                        $("#Edit_che_fecha'.$assoc_get_chequesE['ID_che'].'").fadeIn(500);
                        $("#che_num'.$assoc_get_chequesE['ID_che'].'").fadeOut(500);
                        $("#Edit_che_num'.$assoc_get_chequesE['ID_che'].'").fadeIn(500);
                        $("#che_importe'.$assoc_get_chequesE['ID_che'].'").fadeOut(500);
                        $("#Edit_che_importe'.$assoc_get_chequesE['ID_che'].'").fadeIn(500);
                        $("#che_tipo'.$assoc_get_chequesE['ID_che'].'").fadeOut(500);
                        $("#Edit_che_tipo'.$assoc_get_chequesE['ID_che'].'").fadeIn(500);
                        $("#che_librador'.$assoc_get_chequesE['ID_che'].'").fadeOut(500);
                        $("#Edit_che_librador'.$assoc_get_chequesE['ID_che'].'").fadeIn(500); 
                        $("#editar'.$assoc_get_chequesE['ID_che'].'").fadeOut(500);
                        $("#ver'.$assoc_get_chequesE['ID_che'].'").fadeIn(500);
                        $("#salvar'.$assoc_get_chequesE['ID_che'].'").fadeIn(500);
                      });


                      $("#ver'.$assoc_get_chequesE['ID_che'].'").click(function(){
                        $("#Edit_ID_ban'.$assoc_get_chequesE['ID_che'].'").fadeOut(500);
                        $("#ID_ban'.$assoc_get_chequesE['ID_che'].'").fadeIn(500);
                        $("#che_fecha'.$assoc_get_chequesE['ID_che'].'").fadeIn(500);
                        $("#Edit_che_fecha'.$assoc_get_chequesE['ID_che'].'").fadeOut(500);
                        $("#che_num'.$assoc_get_chequesE['ID_che'].'").fadeIn(500);
                        $("#Edit_che_num'.$assoc_get_chequesE['ID_che'].'").fadeOut(500);
                        $("#che_importe'.$assoc_get_chequesE['ID_che'].'").fadeIn(500);
                        $("#Edit_che_importe'.$assoc_get_chequesE['ID_che'].'").fadeOut(500);
                        $("#che_tipo'.$assoc_get_chequesE['ID_che'].'").fadeIn(500);
                        $("#Edit_che_tipo'.$assoc_get_chequesE['ID_che'].'").fadeOut(500); 
                        $("#che_librador'.$assoc_get_chequesE['ID_che'].'").fadeIn(500);
                        $("#Edit_che_librador'.$assoc_get_chequesE['ID_che'].'").fadeOut(500);                         
                        $("#editar'.$assoc_get_chequesE['ID_che'].'").fadeIn(500);
                        $("#ver'.$assoc_get_chequesE['ID_che'].'").fadeOut(500);
                        $("#salvar'.$assoc_get_chequesE['ID_che'].'").fadeOut(500);
                      });

                        $("#Select_che_tipo'.$assoc_get_chequesE['ID_che'].'").change(function(){
                          var che_tipo = $("#Select_che_tipo'.$assoc_get_chequesE['ID_che'].'").val();
                            if (che_tipo=="AL BENEFICIARIO" || che_tipo=="DE CAJA" || che_tipo=="DE VENTANILLA") 
                            {
                              $("#Edit_che_beneficiario'.$assoc_get_chequesE['ID_che'].'").fadeIn(500);
                              $("#che_beneficiario'.$assoc_get_chequesE['ID_che'].'").fadeOut(500);
                            }
                            else
                           {
                            $("#Edit_che_beneficiario'.$assoc_get_chequesE['ID_che'].'").fadeOut(500);
                            $("#che_beneficiario'.$assoc_get_chequesE['ID_che'].'").fadeIn(500);
                           }   
                        });

                                $ ("#salvar'.$assoc_get_chequesE['ID_che'].'").click(function (){
                                
                                    var ID_che             =$("#Input_ID_che'.$assoc_get_chequesE['ID_che'].'").val();
                                    var action             ="modificarCheque" ;
                                    var ID_ban             =$("#Input_ID_ban'.$assoc_get_chequesE['ID_che'].'").val();
                                    var che_fecha          =$("#Input_che_fecha'.$assoc_get_chequesE['ID_che'].'").val();
                                    var che_num            =$("#Input_che_num'.$assoc_get_chequesE['ID_che'].'").val();
                                    var che_beneficiario   =$("#Input_che_beneficiario'.$assoc_get_chequesE['ID_che'].'").val();
                                    var che_importe        =$("#Input_che_importe'.$assoc_get_chequesE['ID_che'].'").val();
                                    var che_tipo           =$("#Select_che_tipo'.$assoc_get_chequesE['ID_che'].'").val();
                                    var che_librador       =$("#Input_che_librador'.$assoc_get_chequesE['ID_che'].'").val();

                                var dataString = "&ID_che="+ID_che 
                                + "&action="+action 
                                + "&ID_ban="+ID_ban 
                                + "&che_fecha="+che_fecha 
                                + "&che_num="+che_num 
                                + "&che_beneficiario="+che_beneficiario 
                                + "&che_importe="+che_importe 
                                + "&che_tipo="+che_tipo 
                                + "&che_librador="+che_librador;

                                $.ajax(
                                              {
                                                  type: "POST",
                                                  url: "accionesCheques.php",
                                                  data: dataString,
                                                  success: function(data)
                                                   {
                                                      $("#editarCartel'.$assoc_get_chequesE['ID_che'].'").fadeIn(1000).html(data);

                                                      
                                                   }

                                               }) });
</script>';
                    }
                  ?>  
                </tbody>
           </table>
           
            <script type='text/javascript'>

             


    $(document).ready( function () {
    $('#listadoCheques').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'print',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                download: 'open'
            }
        ],
        responsive: true,
    });
    });

     </script>
  