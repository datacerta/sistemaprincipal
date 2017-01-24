
<HTML>
<BODY>


 <form name="exporta_Redecard" action="exporta_demillus_cte_final.php?op=<?$setor?> " method="post">
  
  
<TABLE BORDER=0>
        <tr>
             <td>
        Numero do Setor
    </td>
    <td>
        <input type="text" name=setor value='<?=$setor;?>'>
    </td>
            <td><input type=submit value="Gerar Arquivo para DeMillus, Após geração favor enviar para Site DeMillus"></td>
			<td> <input type=hidden name=ok value=1></td>
        </tr>

      </table>
</form>
</BODY>
</HTML>
