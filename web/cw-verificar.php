<?php require("cw-conexion-seg-0011.php");
global $context,$db_prefix,$tranfer1,$user_info;
$no='<div style="float: left;"><img alt="" src="'.$tranfer1.'/icons/no.png" class="png" width="16px" height="16px" /></div>';
$si='<div style="float: left;"><img alt="" src="'.$tranfer1.'/icons/si.png" class="png" width="16px" height="16px" /></div>';

if($user_info['is_guest']){    
if($_GET['seg']=='001' || $_GET['seg']=='002'){
if($_GET['seg']=='001'){

function verificaExistencia($apodo){ global $db_prefix;
$registro=mysqli_fetch_row(db_query("SELECT realName FROM {$db_prefix}members WHERE realName='$apodo'", __FILE__, __LINE__)); if(!empty($registro))return TRUE; else return FALSE;}

if(empty($_POST["verificacion"])){?>
<div style="height:16px;width:122px;border:solid 1px #C25B43;background-color:#F7ABA1;font-size:11px;font-family:Arial;padding:2px;"><?php echo $no;?> <div>Debes agregar el nick</div></div>

<?php }else{ if(!preg_match('~[\s]~',stripslashes($_POST['verificacion']))==0){ ?>

<div style="height:16px;width:122px;border:solid 1px #C25B43;background-color:#F7ABA1;font-size:11px;font-family:Arial;padding:2px;"><?php echo $no;?> <div>Nick sin espacios</div></div>

<?php }else{ if(!preg_match('~[^a-zA-Z0-9_\-]~',stripslashes($_POST["verificacion"]))==0){ ?>

<div style="height:16px;width:122px;border:solid 1px #C25B43;background-color:#F7ABA1;font-size:11px;font-family:Arial;padding:2px;"><?php echo $no;?> <div>Car&aacute;cteres Inv&aacute;lidos</div></div>

<?php }else{
if(isset($_POST["verificacion"])){
$valor=$_POST["verificacion"];
if(verificaExistencia($valor)){ ?>
<div style="height:16px;width:122px;border:solid 1px #C25B43;background-color:#F7ABA1;font-size:11px;font-family:Arial;padding:2px;"><?php echo $no;?> <div>El nick no est&aacute; <span title="disponible">disp</span></div></div>
<?php } else { ?>


<div style="height:16px;width:122px;font-family:Arial;border:solid 1px #2D832A;background-color:#B2DBA8;font-size:11px;padding:2px;"><?php echo $si;?> <div>El nick est&aacute; <span title="disponible">disp</span></div></div>


<?php }}}}}}

elseif($_GET['seg']=='002'){

function verificaExistencia($mail){ global $db_prefix;
$registro=mysqli_fetch_row(db_query("SELECT emailAddress FROM {$db_prefix}members WHERE emailAddress='$mail'", __FILE__, __LINE__));
if(!empty($registro))return TRUE; else return FALSE;}


if(empty($_POST["emailverificar"])){?>

<div style="height:16px;width:122px;border:solid 1px #C25B43;background-color:#F7ABA1;font-size:11px;font-family:Arial;padding:2px;"><?php echo $no;?> <div>Debes agrear el mail</div></div>
<?php
}else{
if(preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST["emailverificar"])) == 0){ ?>

<div style="height:16px;width:122px;border:solid 1px #C25B43;background-color:#F7ABA1;font-size:11px;font-family:Arial;padding:2px;"><?php echo $no;?> <div>E-mail invalido</div></div>
<?php 
}else{
if(isset($_POST["emailverificar"])){
$valor=$_POST["emailverificar"];
if(verificaExistencia($valor)){ ?>

<div style="height:16px;width:122px;border:solid 1px #C25B43;background-color:#F7ABA1;font-size:11px;font-family:Arial;padding:2px;"><?php echo $no;?> <div>E-mail no disponible</div></div>

<?php }else { ?>

<div style="height:16px;width:122px;font-family:Arial;border:solid 1px #2D832A;background-color:#B2DBA8;font-size:11px;padding:2px;"><?php echo $si;?> <div>E-mail disponible</div></div>

<?php }}}}}

}}else{die('<div style="height:14px;width:122px;border:solid 1px #C25B43;background-color:#F7ABA1;font-size:11px;font-family:Arial;padding:2px;">'.$no.'  No podes estar aca</div>');}?>