<?php 

$sesion_i=session()->get('rol');

if($sesion_i=="Administrador"){
   include ("../Views/navbar/_nabar.php");
}else{
  include("../Views/navbar/_nabar2.php");
}

?>