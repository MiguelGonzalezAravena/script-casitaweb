<?php require("cw-conexion-seg-0011.php");
global $context,$tranfer1,$ajaxError;
if(empty($context['ajax'])){echo $ajaxError; die();}
$_GET['id']=isset($_GET['id']) ? (int) $_GET['id'] : $_GET['id'];
if(empty($_GET['id'])){die();}
$request = db_query("
		SELECT m.subject
		FROM ({$db_prefix}messages AS m, {$db_prefix}boards AS b)
		WHERE m.ID_TOPIC='{$_GET['id']}' AND m.ID_BOARD=b.ID_BOARD
		LIMIT 1", __FILE__, __LINE__);
if(mysqli_num_rows($request)==0)die();
$row=mysqli_fetch_assoc($request);
mysqli_free_result($request);

echo'<div style="width:500px;">
<div id="resultadoEP" style="display:none;"></div>
<div id="contenidoEP" align="center"><font class="size11"><b>Recomendarle este post hasta a seis amigos:</b></font><br /><b class="size11">1 - </b><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email" class="r_email" size="28" maxlength="60" /> <strong class="size11">2 - </strong><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email1" class="r_email1" size="28" maxlength="60" />
<br />
<strong class="size11">3 - </strong><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email2" class="r_email2" size="28" maxlength="60" /> <strong class="size11">4 - </strong><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email3" class="r_email3" size="28" maxlength="60" />
<br />
<strong class="size11">5 - </strong><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email4" class="r_email4" size="28" maxlength="60" /> <strong class="size11">6 - </strong><input type="text" onfocus="foco(this);" onblur="no_foco(this);" name="r_email5" class="r_email5" size="28" maxlength="60" /><br /><br />
					<font class="size11"><b>Asunto:</b></font><br /><input size="40" name="titulo" id="titulo" value="'.censorText($row['subject']).'" type="text" onfocus="foco(this);" onblur="no_foco(this);"><br /><br />
					<font class="size11"><b>Mensaje:</b></font><br />
					<textarea onfocus="foco(this);" onblur="no_foco(this);" cols="70" rows="8" wrap="hard" tabindex="6" name="comment" id="comment">Hola! Te recomiendo que veas este post! 

Saludos!

', $context['user']['name'], '</textarea>
<br /><br /><font class="size11"><b>C&oacute;digo de la im&aacute;gen:</b></font><br />';

echo'<div style="width:127px;"><div style="float:left;"><img src="/web/captcha/index.php?id='.captcha(1,1).'" alt="" style="margin-bottom:2px;" /></div><div style="float:right;"><input size="10" type="text" onfocus="foco(this);" onblur="no_foco(this);" style="text-transform:uppercase;text-align:center;" maxlength="4" id="code" name="code" /></div> <div class="clearfix"></div></div><br />';

echo'<input onclick="recomendarPost(\''.$_GET['id'].'\');" type="submit" class="login" name="send" value="Recomendar post" /></div> </div>'; ?>