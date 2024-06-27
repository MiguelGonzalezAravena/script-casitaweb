<?php require ('cw-conexion-seg-0011.php');

global $db_prefix, $tranfer1;

if ($_GET['can'] < 1) {
    $can = '20';
} elseif ($_GET['can'] <= 5) {
    $can = '5';
} elseif ($_GET['can'] > 49) {
    $can = '50';
} elseif ($_GET['can'] < '50') {
    $can = $_GET['can'];
}
if (!$_GET['cat']) {
    $cat = '';
} elseif ($_GET['cat'] === '142') {
    $cat = '';
} else {
    $cat = "m.ID_BOARD={$_GET['cat']} AND ";
}
if (!$_GET['an']) {
    $an = '183';
} else {
    $an = $_GET['an'] - 17;
}
$rs = db_query("
SELECT b.description,m.subject,b.name,m.ID_TOPIC
FROM ({$db_prefix}messages AS m, {$db_prefix}boards AS b)
WHERE {$cat} m.ID_BOARD=b.ID_BOARD AND m.ID_BOARD<>142
ORDER BY m.ID_TOPIC DESC
LIMIT {$can}", __FILE__, __LINE__);
$context['widget'] = array();
while ($row = mysqli_fetch_assoc($rs)) {
    $row['subject'] = censorText($row['subject']);
    $context['widget'][] = array(
        'description' => $row['description'],
        'titulo' => $row['subject'],
        'name' => $row['name'],
        'ID_TOPIC' => $row['ID_TOPIC'],
    );
}
mysqli_free_result($rs); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es" ><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>CasitaWeb! - WidGet</title>
<link rel="stylesheet" type="text/css" href="<?php echo $tranfer1; ?>/estilo.php" />
<style type="text/css">
body{font-family: Arial,Helvetica,sans-serif;
    font-size:12px;
    margin: 0px;
	padding:0px;
    background: #E1E1E1 url(<?php echo $tranfer1; ?>/bg_widget.gif) repeat-x;}
a{color: #000; text-decoration:none}
a:hover{color:#D35F2C;}
*:focus{outline:0px;}
.item{
    width:<?php echo $an; ?>px;
    overflow:hidden;
    height:16px;
    margin: 2px 0px 0px 0px;
    padding:0px;
    border-bottom: 1px solid #F4F4F4;}
.exterior{width:<?php echo $an; ?>px;}</style></head>
<body><div class="exterior">
<?php foreach ($context['widget'] as $post) { ?>
<div class="item"><a target="_blank" title="<?php echo $post['titulo']; ?>" href="<?php echo $boardurl; ?>/post/<?php echo $post['ID_TOPIC']; ?>/<?php echo $post['description']; ?>/<?php echo urls(censorText($post['titulo'])); ?>.html" class="categoriaPost <?php echo $post['description']; ?>"><?php echo censorText($post['titulo']); ?></a></div>

<?php } ?>
<center><a href="<?php echo $boardurl; ?>/" target="_parent">[ Ver m&aacute;s posts ]</a></center></body></html>