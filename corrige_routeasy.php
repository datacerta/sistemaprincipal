<?php
require_once("inc/config.inc");
$qry  = new consulta($con);
$qry2  = new consulta($con);

$_Exec  = HOST."/Exec/corrige_routeasy_ajax.php";
require_once("inc/header.inc");
?>

<table class="tabela" style="width:800px; margin:0 auto">
    <tr bgcolor="#eeeeee">
      <td align="center"><font size="6"><b><font size="4">..:: </font></b><font color="#990000" size="3"><b>Corrige Routeasy</b></font></font><font size="4"><b> ::..</b></font></td>
    </tr>
</table>
<br><br>    
<table class="tabela" style="width:800px; margin:0 auto">
<form action="#" method="POST">
<tr bgcolor="#FFFFFF">
    <td colspan="7"><label>RA: </label><input type="text" name="ra" /><input type="submit" name="enviar" value="Enviar"/></td>
</tr>
</form>
<tr bgcolor=#eeeeee>
    <td align='left'><b>ID</b></td>
    <td align='left'><b>SETOR</b></td>
    <td align='left'><b>NOME</b></td>
    <td align='left'><b>ENDEREÇO</b></td>
    <td align='left'><b>CIDADE</b></td>
    <td align='left'><b>BAIRRO</b></td>
    <td align='left'><b>CEP</b></td>
    <td align='left'><b>GEO</b></td>
    <td align='left'><b>OBS</b></td>
    <td align='left'></td>
    <td align='left'></td>
    <td align='left'></td>
</tr>
<?php
if(empty($_POST['enviar']) or empty($_POST['ra'])){
    $qry->executa("SELECT *
        FROM tb_demillus_revend
        WHERE latitude = '0' ORDER BY id_setor ASC, id DESC");
}else{
    $qry->executa("SELECT *
        FROM tb_demillus_revend
        WHERE id_revend = '".$_POST['ra']."'");
}
$i = 0;
for($i=0;$i<$qry->nrw;$i++){
    $qry->navega($i);
    $pos = $qry->data['id'];

    if($i % 2 == 0){
?>
        <tr class='marcar<?php echo $pos; ?>' <?php if($qry->data['marcar'] == 't'): echo "style='background-color:#FFE4C4'"; endif; ?> bgcolor="#FFFFFF" <?php if($setorantigo != $qry->data['id_setor']): echo "style='border-top:2px solid #A9A9A9'"; endif;?>>
<?php
    }else{
?>
        <tr class='marcar<?php echo $pos; ?>' <?php if($qry->data['marcar'] == 't'): echo "style='background-color:#FFE4C4'"; endif; ?> bgcolor="#f6f6f6" <?php if($setorantigo != $qry->data['id_setor']): echo "style='border-top:2px solid #A9A9A9'"; endif;?>>
<?php
    }
?>
        <td><?php echo $qry->data['id_revend'];?></td>
        <td><?php echo $qry->data['id_setor'];?></td>
        <td><?php echo $qry->data['nome_revend'];?></td>
        <td><?php echo $qry->data['endereco'];?></td>
        <td><?php echo $qry->data['cidade'];?></td>
        <td><?php echo $qry->data['bairro'];?></td>
        <td><?php echo $qry->data['cep'];?></td>
        <td><input type="text" name="geo<?php echo $pos;?>" <?php 
            if(!empty($_POST['enviar']) or !empty($_POST['ra'])){
                echo " value = '".$qry->data['latitude'].",".$qry->data['longitude']."' ";
            }
        ?> /></td>
        <td><input type="text" name="obs<?php echo $pos;?>" <?php echo " value = '".$qry->data['obs']."' ";
        ?> /></td>
        <td><button value="<?php echo $pos; ?>" class="botao-gravar btn-gravar<?php echo $pos; ?>">Gravar</button></td>
        <td><a style="margin-right: 10px" href="https://www.google.com.br/maps/place/<?php echo urlencode(trim($qry->data['endereco']));?>" target="_blank">Ver Mapa</a></td>
        <td><button value="<?php echo $pos; ?>" class="botao-marcar">Marcar</button></td>
    </tr>
<?php
    $setorantigo = $qry->data['id_setor'];
}
?>
</table>
<div style="width:400px; margin:20px auto;">
    <?php echo $i." RA's"; ?>
</div>
<script>
( function( $ ) {
    $(function() {
        $('.botao-marcar').on('click',function(){
            var pos = $(this).val();
            var geo = $('input[name = "geo'+pos+'"');

            $.ajax({
                method: "POST",
                url: "<?php echo $_Exec; ?>",
                dataType:"json",
                data: { 
                    pos: pos,
                    marcar: 1,
                }
            })
            .done(function( obj ) {
                if(obj.status == 2){
                    $('.marcar'+pos).css({'background-color':'#FFE4C4'});
                }else if(obj.status == 1){
                    $('.marcar'+pos).css({'background-color':'white','color':'#00008B'});
                }
            });
        });

        $('.botao-gravar').on('click',function(){
            var pos = $(this).val();
            var geo = $('input[name = "geo'+pos+'"');
            var obs = $('input[name = "obs'+pos+'"');

            $.ajax({
                method: "POST",
                url: "<?php echo $_Exec; ?>",
                dataType:"json",
                data: { 
                    pos: pos,
                    geo: geo.val(),
                    obs: obs.val()
                }
            })
            .done(function( obj ) {
                if(obj.status == 1){
                    $('.btn-gravar'+pos).prop('disabled', true);
                    $('.btn-gravar'+pos).text('Salvo');
                    $('.btn-gravar'+pos).css({'background-color':'green','color':'white'});
                }else if(obj.status == 3){
                    alert('Faça o login novamente');
                }
                else if(obj.status == 4){
                    alert('observação gravada');
                }else{
                    alert('Erro ao gravar');
                }
            });
        });
    });
} )( jQuery );
</script>
<?php
// pega o Footer
require_once("inc/footer.inc");
