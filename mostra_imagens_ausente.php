<?


require_once("classes/diversos.inc.php");
$qry = new consulta($con);
$qry_n_baixa = new consulta($con);

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

$sql = "select * from tb_easy_courier where 
        nr_encomenda = '$idd2'  ";
       

	   $qry_n_baixa->executa($sql);
	  
	   
	   								
	
		
				
				
				echo "<table >";
				for($z=0;$z<$qry_n_baixa->nrw;$z++){
				$qry_n_baixa->navega($z);
					   $id = $qry_n_baixa->data["id"];  
					   $datamov = $qry_n_baixa->data["datacliente"];
					   //$id = $qry_n_baixa->data["idinterno"];
					   //pegando a RA
					   $sql_ra = 	"select nomeentrega from tbenderecoentrega where idinterno = $id";
						//			 $qry_ra->executa($sql_ra);
							//		 $ra = substr($qry_ra->data["nomeentrega"],0,20);
										
					   
				       
					   echo "<tr>";
					 
					  
					   echo mostra_data($datamov,1);
					   echo "<iframe src='mostra_imagens_final.php?id=$id' 
				        width = 100% height = 100% marginwidth=0
						marginheight=0 scrolling=no frameborder=0 align = center
	                   
					   <tr></tr>
					   
					   </iframe>";
					    
					  
					   echo "</tr>";
					   echo "<br>";
					   
					   ;
					   
					   
 				}
   				
				echo "</table>";

?>