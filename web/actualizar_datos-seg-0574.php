<?php require("cw-conexion-seg-0011.php"); global $db_prefix;
db_query("UPDATE {$db_prefix}members SET dar_dia=5 WHERE ID_GROUP=11",__FILE__, __LINE__);
db_query("UPDATE {$db_prefix}members SET dar_dia=5 WHERE ID_GROUP=7",__FILE__, __LINE__);
db_query("UPDATE {$db_prefix}members SET dar_dia=10 WHERE ID_GROUP=2",__FILE__, __LINE__);
db_query("UPDATE {$db_prefix}members SET dar_dia=10 WHERE ID_GROUP=1",__FILE__, __LINE__);
Header("Location: /");
die(); exit(); ?>