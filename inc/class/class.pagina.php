<?php
/**
 * Classe de Paginacao
 * ---------------------------------------------------------------------------------
 * Metodos desta classe:
 * ---------------------------------------------------------------------------------
 * menu() ......: mostra a paginacao
 * limite() ....: controla o limite da query
 * linhas() ....: retorna o numero de linhas de query
 * getPaginas() : retorna o numero de paginas
 * ---------------------------------------------------------------------------------
 * @author Claudio Monteoliva
 * @version 1.0
 * @copyright 2015
 */
class PAGINA {
   // Caso queira mudar os valores default, altere aqui
   private $phpSELF = "";

   // parametros privados
   private $num     = 10;
   private $sql     = "";
   private $URL     = "";
   private $lin     = 0;
   private $LIN     = "";
   private $lastpag = 0;
   private $width   = "1000";
   private $classe  = "divPaginacao";
   private $parent  = "N";
   private $dba;

   // icones da paginacao
   private $imgPRIon  = "";  // seta Primeiro registro On
   private $imgULTon  = "";  // seta Ultimo   registro On
   private $imgPROon  = "";  // seta Proximo  registro On
   private $imgANTon  = "";  // seta Anterior registro On
   private $imgPRIoff = "";  // seta Primeiro registro Off
   private $imgULToff = "";  // seta Ultimo   registro Off
   private $imgPROoff = "";  // seta Proximo  registro Off
   private $imgANToff = "";  // seta Anterior registro Off

  /**
   * Constructor
   */
  public function __construct() {
    // pega variavel global
    global $lastpag;
    global $dba;

    // pega a conexao
    $this->dba = $con;

    //  Controla registros na query
    $this->lastpag = (empty($lastpag)) ? 0 : (int)$lastpag;

    // seta o nome das imagens (ON)
    $this->imgPRIon = "<img src='".HOST."/inc/img/paginacao/primeiro.gif' alt='' title='Primeira p&aacute;gina'       align='top' />";
    $this->imgULTon = "<img src='".HOST."/inc/img/paginacao/ultimo.gif'   alt='' title='&Uacute;ltima p&aacute;gina'  align='top' />";
    $this->imgPROon = "<img src='".HOST."/inc/img/paginacao/proximo.gif'  alt='' title='Pr&oacute;xima p&aacute;gina' align='top' />";
    $this->imgANTon = "<img src='".HOST."/inc/img/paginacao/anterior.gif' alt='' title='P&aacute;gina anterior'       align='top' />";

    // seta o nome das imagens (OFF)
    $this->imgPRIoff = "<img src='".HOST."/inc/img/paginacao/primeiro_off.gif' alt='' title='' align='top' />";
    $this->imgULToff = "<img src='".HOST."/inc/img/paginacao/ultimo_off.gif'   alt='' title='' align='top' />";
    $this->imgPROoff = "<img src='".HOST."/inc/img/paginacao/proximo_off.gif'  alt='' title='' align='top' />";
    $this->imgANToff = "<img src='".HOST."/inc/img/paginacao/anterior_off.gif' alt='' title='' align='top' />";
  }

  /**
   * Metodo que mostra a paginacao
   */
  public function menu() {
    // pega variavel global
    global $txt;

    // numero randomico
    $rnd = rand(0, 999999999999999999);

    // Linhas de retorno da query
    $this->lin = $this->linhas();

    // retorna o numero de paginas
    $paginas = $this->getPaginas();
    $pagina  = (int)$paginas;

    // seta o phpSELF
    $phpSELF = $this->phpSELF;
    
    // calcula a Ultima pagina
    $priNUM = 0;
    $ultNUM = ($paginas - 1) * $this->num;
    $antNUM = $this->lastpag - $this->num;
    $proNUM = $this->lastpag + $this->num;
    $antNUM = ($antNUM <= 0         ) ? $priNUM : $antNUM;
    $proNUM = ($proNUM >= $this->lin) ? $ultNUM : $proNUM;

	// monta o SELF
	$self = (strpos($this->phpSELF, "?") > -1) ? $this->phpSELF."&" : $this->phpSELF."?";
	
    // seta os links de paginacao pelas imagens
    $priLINK = $self."lastpag={$priNUM}&idmenu=0&token={$rnd}";
    $ultLINK = $self."lastpag={$ultNUM}&idmenu=0&token={$rnd}";
    $antLINK = $self."lastpag={$antNUM}&idmenu=0&token={$rnd}";
    $proLINK = $self."lastpag={$proNUM}&idmenu=0&token={$rnd}";

    // seta parent
    $parent = ($this->parent === "S") ? "window.parent." : "";

    // funcoes em javascript para a paginacao
    echo "<script type='text/javascript'>\n";
    echo "  function pagSubmit()   { {$parent}document.fmenu.submit(); }\n";
    echo "  function pagPrimeiro() { {$parent}window.location.href = '{$priLINK}'; }\n";
    echo "  function pagUltimo()   { {$parent}window.location.href = '{$ultLINK}'; }\n";
    echo "  function pagAnterior() { {$parent}window.location.href = '{$antLINK}'; }\n";
    echo "  function pagProximo()  { {$parent}window.location.href = '{$proLINK}'; }\n";
    echo "</script>\n";

    // seta as paginas
    $pagPrim = $priNUM + 1;
    $pagUlti = $ultNUM + 1;
    $pagAtua = $this->lastpag + 1;

    // seta a paginacao pelas imagens
    $first = ((int)$pagAtua > (int)$pagPrim) ? "<a href='javascript:void()' onclick='pagPrimeiro()'>{$this->imgPRIon}</a>" : $this->imgPRIoff;
    $prev  = ((int)$pagAtua > (int)$pagPrim) ? "<a href='javascript:void()' onclick='pagAnterior()'>{$this->imgANTon}</a>" : $this->imgANToff;
    $last  = ((int)$pagAtua < (int)$pagUlti) ? "<a href='javascript:void()' onclick='pagUltimo()'  >{$this->imgULTon}</a>" : $this->imgULToff;
    $next  = ((int)$pagAtua < (int)$pagUlti) ? "<a href='javascript:void()' onclick='pagProximo()' >{$this->imgPROon}</a>" : $this->imgPROoff;

    // monta tabela
    echo "<center>\n";
    echo "<div class='{$this->classe}'>\n";
    echo "<form action='{$this->phpSELF}' method='get' name='fmenu'>\n";
	echo "<input type='hidden' name='idmenu' value='0' />\n";
	echo "<input type='hidden' name='token'  value='{$rnd}' />\n";
    echo "<table>\n";
    echo "<tr>\n";

    // verifica o numero de paginas
    if ($paginas > 0) {
      // mostra imagens de paginacao
      echo "  <td width='120' align='left'>{$first}&nbsp;{$prev}&nbsp;{$next}&nbsp;{$last}&nbsp;</td>\n";
      echo "  <td class='fonte' align='left'>\n";
      echo "      P&aacute;gina: \n";
      echo "      <select name='lastpag' class='campo' size='1' onchange='pagSubmit()'>\n";
      for($li=0;$li<$paginas;$li++) {
        // seta variaveis
        $pagg = $li * $this->num;
        $num  = $li + 1;
        $sele = ($this->lastpag == $pagg) ? "selected='selected'" : "";

        // mostra a opcao
        echo "<option value='{$pagg}' {$sele}>".number_format($num,0,",",".")."</option>\n";
      }
      
      // seta o numero de linhas
      $nrecord = ($this->lin > 1) ? "registros"      : "registro";
      $npage   = ($paginas   > 1) ? "P&aacute;ginas" : "P&aacute;gina";
      
      // mostra o final
      echo "      </select>\n";
      echo "      de <span class='cfonte'>".number_format($paginas,0,",",".")."</span> {$npage}";
      echo "  </td>\n";
      echo "  <td class='fonte' align='right'>".number_format($this->lin,0,",",".")." {$nrecord}</td>\n";
    }
    
    // mostra o restante
    echo "</tr>\n";
    echo "</table>\n";
    echo "</form>\n";
    echo "</div>\n";
    echo "</center>\n";
  }
	 
  /**
   * Metodo que controla o limite da query
   */
  public function limite() {
    // Caso o inicio seja maior que o numero de registros (ja aconteceu quando estava paginado e fez uma busca)
    if ($this->lastpag > $this->linhas()) { $this->lastpag = 0; }

    // Retorna o limite que vai no fim da query
    return " LIMIT {$this->num} OFFSET {$this->lastpag}";
  }

  /**
   * Metodo que retorna o numero de linhas de query
   */
  public function linhas() {
    // Passa query para caixa baixa para comparar
    $sql = strtolower($this->sql);

    // executa a query
    $cons = new consulta($this->dba);
    $cons->executa($sql);
    $lin  = $cons->nrw;
    $lin  = (int)$lin;

    // retorna o numero de linhas
    return $lin;
  }

  /**
   * Metodo que retorna o numero de paginas
   */
  public function getPaginas() {
    // pega a quantidade de paginas
    $pround  = intval($this->lin / $this->num);
    $paginas =       ($this->lin / $this->num);
    $dec     = 0;

    // verifica as paginas
    if ($pround == 0) { $paginas = 1; }
    else
    {
      // pega o decimal
      if     (strlen($pround) == 1) { $dec = substr($paginas,2,2); }
      elseif (strlen($pround) == 2) { $dec = substr($paginas,3,2); }
      elseif (strlen($pround) == 3) { $dec = substr($paginas,4,2); }

      // verifica o decimal
      if (intval($dec) > 0) { $paginas = $pround + 1; }
    }

    // retorna as paginas
    return intval($paginas);
  }

  /**
   * Metodo Setter
   */
  public function setSQL($sql)     { $this->sql     = $sql;      }
  public function setWidth($txt)   { $this->width   = $txt;      }
  public function setClasse($txt)  { $this->classe  = $txt;      }
  public function setParent($txt)  { $this->parent  = $txt;      }
  public function setNum($num)     { $this->num     = (int)$num; }
  public function setPhpSELF($txt) { $this->phpSELF = $txt;      }
}