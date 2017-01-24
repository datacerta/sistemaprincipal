<?php
include_once('classes/googlemap.php');
 
?>
<html>
<body>
<div id="map" style="width:100%; height:100%;" >
<?php
require_once("classes/diversos.inc.php");
$qry = new consulta($con);
$qry2 = new consulta($con);
$qry3 = new consulta($con);
$qry_motivo = new consulta($con);

$data = (($_GET['data']) ? $_GET['data'] : "");

if(empty($data))
{	$formata_data = date("Y-m-d");
} else 
{
	$data_ = explode("/", $data);

	$ano = $data_[2];
	$mes = $data_[1];
	$dia = $data_[0];
	$formata_data = $ano."-".$mes."-".$dia;
}


	//echo "<meta HTTP-EQUIV='refresh' CONTENT='30;URL=$_SERVER[PHP_SELF]'>";

$sql = "select '0' as  Encomenda, '0' as st, 888 as idmotivo,  latitude,longitude,datacliente from tb_easy_courier_geo 
			where login = '$login' and  datacliente >= '$formata_data' 
			union
			select tb_easy_courier.nr_encomenda,st,idmotivo,latitude,longitude,datacliente from tbentrega,tb_easy_courier where tbentrega.idexterno = tb_easy_courier.nr_encomenda
			and login = '$login' and st is not null and  datacliente >= '$formata_data' order by datacliente , st desc LIMIT 1";
       

	   $qry->executa($sql);
		
		


$la = $qry->data["latitude"];
$lo = $qry->data["longitude"];
$pri= $qry->data["idgeo"];







	

$map=new GOOGLE_API_3();
$map->center_lat=$la; // set latitude for center location
$map->center_lng=$lo; // set langitude for center location
$map->zoom=15;
$primeiro = $qry->primeiro;
	$total = $qry->nrw;
	
	
	$sql = "select '0' as  Encomenda, '0' as st, 888 as idmotivo,  latitude,longitude,datacliente from tb_easy_courier_geo 
			where login = '$login' and  datacliente >= '$formata_data' 
			union
			select tb_easy_courier.nr_encomenda,st,idmotivo,latitude,longitude,datacliente from tbentrega,tb_easy_courier where tbentrega.idexterno = tb_easy_courier.nr_encomenda
			and login = '$login' and st is not null and  datacliente >= '$formata_data' order by datacliente , st desc";
			$qry->executa($sql);
			//echo $sql;
			//die;
	
	
	//$sql = "select idgeo,latitude,longitude,datacliente from tb_easy_courier_geo 
     //  where login = '$login' and  datacliente >= '$formata_data' order by datacliente";
     //  $qry->executa($sql);

	
	
	for($i=0;$i<$qry->nrw;$i++)
	
	{
    	 $qry->navega($i);
     	
		 $la = $qry->data["latitude"];
		 $lo = $qry->data["longitude"];
		 if($la<>$tla and $lo <>$tlo)
		 {
		 
		 $pri_atual = $qry->data["idgeo"];
		 $st = $qry->data["st"];
		 $nr_encomenda = trim($qry->data["encomenda"]);
		
		//echo $lng;
		//die;
		
		$isclickable='true';
		$title= mostra_data($qry->data["datacliente"],1);
		$info=mostra_data($qry->data["datacliente"],1);
		$data_entrega = mostra_data($qry->data["datacliente"],1);
		
		
		
       	
		//DEFINE O SPIN - ICONE ONDE FOI FEITA A BAIXA
	    if ($st == 'E')
		    $icon = 'img/entregue_ok.png';
		elseif ($st == 'T' or $st == 'D' or $st == 'F' or $st == 'E')
		    {
			   $icon = 'img/pino_dev_preto.png';
			   //Verifica o motivo 
			   $sql = "select motivo,tbenderecoentrega.nomeentrega from tbentrega,tbmotivo,tbenderecoentrega where 
			           tbentrega.idinterno = tbenderecoentrega.idinterno
					   and
					   tbentrega.idmotivo = tbmotivo.idmotivo and 
				       tbentrega.idexterno = '".$nr_encomenda."'";
				       $qry_motivo->executa($sql);
					   $motivo = $qry_motivo->data["motivo"];
					  
					  // echo $sql;
					  // die;
			   
			}
        elseif ($st == '0' or $st='')
		    
		    $icon = 'img/pino_vermelho.png';
	
		 
		 
		
		  
		  $title = $qry_motivo->data["nomeentrega"];
		  $nome_recebedor = $qry_motivo->data["nomeentrega"];
		  if($st=='E')
			$info="<div class=info><img src='img/logo_mobidata_pequeno.jpg'><br><br><b>Encomenda:</b> ".$nr_encomenda."<br><b>Destinatario:</b> ".$nome_recebedor."<br><b>Data da entrega:</b> ".$data_entrega."<br>"."<b>OCORRENCIA = ENTREGUE AO DESTINATARIO</b>";
	      elseif ($st == 'T' or $st == 'D' or $st == 'F' or $st == 'E')
	        $info="<div class=info><img src='img/logo_mobidata_pequeno.jpg'><br><br><b>Encomenda:</b> ".$nr_encomenda."<br><b>Destinatario:</b> ".$nome_recebedor."<br><b>Data da visita:</b> ".$data_entrega."<br>"."<b>OCORRENCIA = ". $motivo. "</b>";
		  
		
			
		 
		
	
		
    	//$info=$destinatario;
		
		
	
		$map->addMarker($la,$lo,$isclickable,$title,$info,$icon);
		
		
		$tla = $la ;
		$tlo = $lo;
		}
	
		
}		
					
	
	
					


	
		
					

echo $map->showmap();
?>
</div>





</body>
</html>
