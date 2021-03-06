<?php
session_start();
include_once('inc/conectar.php');
include_once('inc/classes.php');
include_once('inc/classesExclusivas.php');

$ID_usu                 = $_SESSION['ID_usu'];
$usu_usuario            = $_SESSION['usu_usuario'];
$usu_clave              = $_SESSION['usu_clave'];
$usu_tipo               = $_SESSION['usu_tipo'];
$cuentas                = new cuentas;
$cuentasE               = new cuentasE;
$fechaDeHoy             = date("Y-m-d");
$HoraDeHoy              = date("H:i:s");
$FechayHora             = date("Y-m-d H:i:s");
$action                 = $_POST['action'];
@$atras                 = $_SESSION['actionsBack'];
$cuentas_movimientosE   = new cuentas_movimientosE;
$cuentas_movimientos    = new cuentas_movimientos;
$cuentas_impuestosE     = new cuentas_impuestosE;
$cuentas_impuestos      = new cuentas_impuestos;
$clientesE              = new clientesE;
$clientes               = new clientes;
$cuenta_cteE            = new cuenta_cteE;

  if($action=="listarClientes")
  { 
      $clientes=$_POST['buscarCliente'];
      $num_search = strlen($clientes);
      $get_clientes_byNombreYApellido=$clientesE->get_clientes_byNombreYApellido($clientes);
      $num_get_clientes_byNombreYApellido=mysql_num_rows($get_clientes_byNombreYApellido);
      echo "<div class='col-md-12' id='mostradorDeDatosDeCliente' style='text-align:center; margin-top:2%;'>"; 
      echo "<input hidden name='clienteSeleccionadoB' id='clienteSeleccionadoB' type='text' value=''>";
      echo "<input hidden name='clienteNombre' id='clienteNombre' type='text' value=''>";
        for ($CountClientes=0; $CountClientes < $num_get_clientes_byNombreYApellido; $CountClientes++) 
        { 
          if ($num_search>=2)
          {
          $assoc_get_clientes_byNombreYApellido=mysql_fetch_assoc($get_clientes_byNombreYApellido);
                        
                         echo "<div class='col-md-3' style='cursor:pointer' id='clienteSeleccionado".$assoc_get_clientes_byNombreYApellido['ID_cli']."' >"; 
                          echo '<div class="panel panel-info">';
                                echo '<div class="panel-heading">';
                                  echo '<h2 class="panel-title"><strong><i class="material-icons">accessibility</i> '.$assoc_get_clientes_byNombreYApellido['cli_apellido']. ' '.$assoc_get_clientes_byNombreYApellido['cli_nombre'].'</strong></h2>';
                              echo '</div>';
                              echo '<div class="panel-body">';
                                  echo '<input hidden type="text" id="ID_cli" name="ID_cli" value="'.$assoc_get_clientes_byNombreYApellido['ID_cli'].'">';
                                  echo '<input hidden type="text" id="cli_nombre'.$assoc_get_clientes_byNombreYApellido['ID_cli'].'" name="cli_nombre" value="'.$assoc_get_clientes_byNombreYApellido['cli_apellido']. ' '.$assoc_get_clientes_byNombreYApellido['cli_nombre'].'">';
                                  echo "<div class='col-md-7' style='text-align:left'>"; 
                                  echo "Saldo Deudor:"; 
                                   echo "</div>";
                                   echo "<div class='col-md-5' style='text-align:right'>";
                                   echo "$ ".$assoc_get_clientes_byNombreYApellido['cte_monto'];
                                   echo "</div>";
                                   echo "<div class='col-md-7' style='font-size:80%; text-align:left'>";
                                   echo "Ultimo Movimiento:"; 
                                   echo "</div>";
                                   echo "<div class='col-md-5' style='font-size:80%; text-align:right;'>";
                                   echo $assoc_get_clientes_byNombreYApellido['cte_fec'];
                                  echo "</div>";  
                              echo '</div>';    
                          echo '</div>';  
                          echo '</div>'; 

                          echo "<script>$('#clienteSeleccionado".$assoc_get_clientes_byNombreYApellido['ID_cli']."').click(function(){
                              var ID_cliSeleccionado = ".$assoc_get_clientes_byNombreYApellido['ID_cli'].";
                              var cli_nombreSeleccionado = '".$assoc_get_clientes_byNombreYApellido['cli_apellido']." ".$assoc_get_clientes_byNombreYApellido['cli_nombre']."';
                              $('#clienteSeleccionadoB').val(ID_cliSeleccionado);
                              $('#clienteNombre').val(cli_nombreSeleccionado);
                          });</script>";


          }
        }  
         echo '</div>';     
  }

  if($action=='modificarCtaCte')
  {
    $ID_cli             = $_POST['ID_cli'];
    $cte_fec                = $fechaDeHoy;
    $cte_monto              = $_POST['cte_monto'];
    $cte_tipo               = 1;
    $ID_fpo                 = 2;

    $get_cuenta_cteById=$cuenta_cteE->get_cuenta_cteById($ID_cli);
    $assoc_get_cuenta_cteById=mysql_fetch_assoc($get_cuenta_cteById);

    $ID_cte=$assoc_get_cuenta_cteById['ID_cte'];
    $montoNuevo=$cte_monto+$assoc_get_cuenta_cteById['cte_monto'];

    $update_cuenta_cteById  = $cuenta_cteE->update_cuenta_cteById($ID_cte, $ID_cli, $cte_fec, $montoNuevo, $cte_tipo, $ID_fpo);

    echo "<input hidden type='text' name='RespuestaIdMovCuentaCtaCte' id='RespuestaIdMovCuentaCtaCte' value='".$ID_cte."'>";
  }
  
  if($action=='EliminarMovimientoEnCtaCte')
  {
    $ID_cte                 = $_POST['ID_cte'];
    $cte_monto              = $_POST['cte_monto'];
 
    $montoNuevo=$cte_monto+$assoc_get_cuenta_cteById['cte_monto'];

    $update_cuenta_cteById  = $cuenta_cteE->update_CtaCte($ID_cte, $cte_monto);

  }
?>