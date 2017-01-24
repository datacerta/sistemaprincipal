<?
        class categorias {
            var $bd;
            var $qry;
            var $id_cat;
            var $descricao;

            function categorias($bd) {
                $this->bd = $bd;
                $this->qry = new consulta($bd);
                $this->id_cat = 0;
                $this->descricao = "";
            }

        function monta_select($reg=0) {
          //categorias outro
            $sql = "SELECT * FROM categorias";
                $this->qry->executa($sql);

                if(!$this->qry->res OR $this->qry->nrw<=0)
                      return "";
                else {
                echo "<option value='-' selected> -- Selecione uma categoria --</option>";
                      for($i=0;$i<$this->qry->nrw;$i++) {
                       $opcoes.= "<option value=\"" . $this->qry->data["id_cat"] . "\"";
                           $opcoes.= (($this->qry->data["id_cat"]==$reg) ? " SELECTED>" : ">");
                           $opcoes.= $this->qry->data["descricao"] . "</option>\n";
                           $this->qry->proximo();
                      }
                }
                 return $opcoes;
         }


        function monta_select2($reg=0) {
          //categorias pro sub outro
            $sql = "SELECT * FROM categorias";
                $this->qry->executa($sql);

                if(!$this->qry->res OR $this->qry->nrw<=0)
                      return "";
                else {
                echo "<option value='-' selected> -- Selecione uma sub categoria -- </option>";
                      for($i=0;$i<$this->qry->nrw;$i++) {
                       $opcoes.= "<option value=\"" . $this->qry->data["id_cat"] . "\"";
                           $opcoes.= (($this->qry->data["id_cat"]==$reg) ? " SELECTED>" : ">");
                           $opcoes.= $this->qry->data["descricao"] . "</option>\n";
                           $this->qry->proximo();
                      }
                }
                 return $opcoes;
         }

        function monta_select3($reg=0) {
         //link outro
            $sql = "SELECT * FROM link";
                $this->qry->executa($sql);

                if(!$this->qry->res OR $this->qry->nrw<=0)
                      return "";
                else {
                echo "<option value='-' selected> -- Selecione a palavra chave -- </option>";
                      for($i=0;$i<$this->qry->nrw;$i++) {
                       $opcoes.= "<option";
                           $opcoes.= (($this->qry->data["id_link"]==$reg) ? " SELECTED>" : ">");
                           $opcoes.= $this->qry->data["nome"] . "</option>\n";
                           $this->qry->proximo();
                      }
                }
                 return $opcoes;
         }

        function monta_select4($reg=0) {
          //categorias
            $sql = "SELECT * FROM categorias_link";
                $this->qry->executa($sql);

                if(!$this->qry->res OR $this->qry->nrw<=0)
                      return "";
                else {
                echo "<option value='-' selected> -- Selecione a categoria -- </option>";
                      for($i=0;$i<$this->qry->nrw;$i++) {
                       $opcoes.= "<option value=".$this->qry->data["id_cat"];
                           $opcoes.= (($this->qry->data["id_cat"]==$reg) ? " SELECTED>" : ">");
                           $opcoes.= $this->qry->data["descricao"] . "</option>\n";
                           $this->qry->proximo();
                      }
                }
                 return $opcoes;
         }

        function monta_select5($reg=0,$where) {
        //sub categoria
            $sql = "SELECT * FROM sub_categorias_link $where";
                $this->qry->executa($sql);

                if(!$this->qry->res OR $this->qry->nrw<=0)
                      return "<option value='0'>Nenhuma sub categoria</option>";
                else {
                echo "<option value='-' selected> -- Selecione a sub categoria -- </option>";
                      for($i=0;$i<$this->qry->nrw;$i++) {
                           $opcoes.= "<option value=".$this->qry->data["id_sub_cat"];
                           $opcoes.= (($this->qry->data["id_sub_cat"]==$reg) ? " SELECTED>" : ">");
                           $opcoes.= $this->qry->data["descricao"] . "</option>\n";
                           $this->qry->proximo();
                      }
                }
                 return $opcoes;
         }

        function monta_select6($reg=0) {
         //link
            $sql = "SELECT * FROM link_link ";
                $this->qry->executa($sql);

                if(!$this->qry->res OR $this->qry->nrw<=0)
                      return "<option value='0'>Nenhum Link</option>";
                else {
                    echo "<option value='-' selected> -- Selecione um Link -- </option>";
                      for($i=0;$i<$this->qry->nrw;$i++) {
                           $opcoes.= "<option value=".$this->qry->data["id_link"];
                           $opcoes.= (($this->qry->data["id_link"]==$reg) ? " SELECTED>" : ">");
                           $opcoes.= $this->qry->data["descricao"] . "</option>\n";
                           $this->qry->proximo();
                      }
                }
                 return $opcoes;
         }

        function monta_select7($reg=0,$where,$num) {
         //link generico
            $sql = "SELECT * FROM link_link $where";
                $this->qry->executa($sql);

                if(!$this->qry->res OR $this->qry->nrw<=0)
                      return "<option value='0'>Nenhum Link</option>";
                else {
                    echo "<option value='-' selected> -- Selecione um Link -- </option>";
                      for($i=0;$i<$this->qry->nrw;$i++) {
                           $opcoes.= "<option value=".$this->qry->data["id_link"];
                           $opcoes.= (($this->qry->data["id_link"]==$reg) ? " SELECTED>" : ">");
                           if ($num == 1)
                               $opcoes.= $this->qry->data["descricao"] . "</option>\n";
                           else
                               $opcoes.= $this->qry->data["url"] . "</option>\n";

                           $this->qry->proximo();
                      }
                }
                 return $opcoes;
         }


        function monta_select8($reg=0,$arg="") {
         //usuario
               if ($reg != 0){
                $opcoes2[] .= "<option value=0>Visitante</option>";
                $opcoes2[] .= "<option value=1>Aluno</option>";
                $opcoes2[] .= "<option value=2>Secretária</option>";
                $opcoes2[] .= "<option value=3>Diretor</option>";
                $opcoes2[] .= "<option value=4>Supervisor</option>";

                for($i=0;$i<$reg;$i++){
                    $opcoes .= $opcoes2[$i];
                }
                  $opcoes = "<select name=acesso>".$arg.$opcoes."</select>";
              }
              else
                $opcoes = "<font color=red>Você não tem permissão para cadastrar nenhum usuário</font><input type=hidden name=acesso value=-1>";
             return $opcoes;
         }
         function monta_select9($reg=0) {
          //categorias outro
            $sql = "SELECT * FROM forum_topico";
                $this->qry->executa($sql);

                if(!$this->qry->res OR $this->qry->nrw<=0)
                      return "";
                else {
                echo "<option value='-' selected> -- Selecione um tópico --</option>";
                      for($i=0;$i<$this->qry->nrw;$i++) {
                       $opcoes.= "<option value=\"" . $this->qry->data["id_topico"] . "\"";
                           $opcoes.= (($this->qry->data["id_topico"]==$reg) ? " SELECTED>" : ">");
                           $opcoes.= $this->qry->data["subject"] . "</option>\n";
                           $this->qry->proximo();
                      }
                }
                 return $opcoes;
         }



     }
?>