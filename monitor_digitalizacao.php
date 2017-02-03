<?php 
require_once("classes/diversos.inc.php");
$qry  = new consulta($con);
$qry2  = new consulta($con);

?>
<head>
    <meta HTTP-EQUIV='refresh' CONTENT='10;URL=monitor_digitalizacao.php'>
</head>
<body>
<a href="monitor_dm_b.php">trocar</a>
<table width="100%">
    <tr>
        <th>LISTA</th>
        <th>QUANTIDADE</th>
        <th>DIGITALIZADOS</th>
    </tr>
</table>
</body>
