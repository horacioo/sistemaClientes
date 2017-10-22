<?php

class cadastro extends conex {

    private $conn;
    private $id;
    public $id_cliente;
    public $nome;
    public $cpf;
    public $login;
    public $senha;
    public $entradaDados;
    public $mensagem;
    public $rg;
    public $nascimento;
    public $data_expedicao;
    private $vinculo;
    private $email;
    private $email_id;
    private $telefone_id;
    private $arrayDados;
    private $booleanInf;
    private $loopDados;
    private $consultasEstruturadas;
    private $ConjuntoDeDadosAssociacao;

    public function __construct($dados = '') {
        $this->mensagem = "ok";
        if (is_array($dados)) {
            $this->entradaDados = $dados;
            $this->vinculo      = 0;
        }
        $this->conn       = $this->Conex();
        $this->id         = 0;
        $this->id_cliente = 0;
        //$this->email_id    = 0;
        //$this->telefone_id = 0;
    }










    public function cadastra($x = '') {
        $dados = $this->entradaDados;
        $this->CadastraUsuario($this->entradaDados['cadastro']);
        echo $this->id_cliente;
    }










    private function CadastraUsuario($usuario = '') {
        if (isset($usuario['id'])):
        else:
            $senha       = hash('whirlpool', $this->Limpeza($usuario['senha']));
            $this->senha = $senha;
            /*             * *********** */
            $sel         = "where cpf='" . $usuario['cpf'] . "' and login='" . $usuario['login'] . "' and senha='$senha' ";
            $this->VerificaExistencia("cadastro", $sel);
            if ($this->id_cliente === 0):
                $ins                           = "insert into cadastro(nome,login,senha,cpf,rg,data_nascimento,data_expedicao) values('" . $usuario['nome'] . "','" . $usuario['login'] . "','" . $senha . "','" . $usuario['cpf'] . "','" . $usuario['rg'] . "','" . $usuario['data_nascimento'] . "','" . $usuario['data_expedicao'] . "')";
                $x                             = $this->conn->query($ins);
                $this->id_cliente              = $this->conn->insert_id;
                $this->loopDados['cadastro'][] = $this->conn->insert_id;
            else:
                $this->loopDados['cadastro'] = $this->id_cliente;
            endif;
        /*         * *********** */

        endif;
    }










    public function CadastraEmail() {
        $this->chave = 0;
        if (isset($email['id'])) {
            
        } else {
            $array = $this->entradaDados['email'];
            $this->insere("email", "email", $array);
        }
    }










    public function CadastraTelefone() {
        if (isset($telefone['id'])) {
            
        } else {
            $array = $this->entradaDados['telefone'];
            $this->insere("telefone", "telefone", $array);
        }
        //print_r($this->loopDados);
    }










    public function Associa() {
        print_r($this->loopDados);
        $usuario  = $this->id_cliente;//$this->loopDados['cadastro'];
        $email    = $this->loopDados['email'];
        $telefone = $this->loopDados['telefone'];
        
        if($usuario > 0){}else{exit();}
        
        foreach ($email as $e):
            $tabela = "`cadastro-email`";
            $sel    = "where cadastro = '$usuario' and email= '$e'";
            $this->VerificaExistencia($tabela, $sel);
            if ($this->chave === 0):
                $insert = "insert into $tabela (cadastro,email)values('" . $usuario . "','" . $e . "')";
                //echo "<br> $insert";
                $this->conn->query($insert);
            endif;
            $this->chave=0;
        endforeach;
        foreach ($telefone as $e):
            $tabela = "`cadastro-telefone`";
            $sel    = "where cadastro = '$usuario' and telefone= '$e'";
            $this->VerificaExistencia($tabela, $sel);
            if ($this->chave === 0):
                $insert = "insert into $tabela (cadastro,telefone)values('" . $usuario . "','" . $e . "')";
                //echo "<br> $insert";
                $this->conn->query($insert);
            endif;
            $this->chave=0;
        endforeach;
    }










    private function insere($tabela = '', $tipo = '', $array = '') {
        foreach ($array as $valor):
            if (!empty($valor)) {
                ///////////////////////////////////////
                switch ($tabela):
                    case "email": $this->email_id    = 0;
                        break;
                    case "telefone": $this->telefone_id = 0;
                        break;
                endswitch;
                $sel = "where $tabela='" . $valor . "'";
                $this->VerificaExistencia($tabela, $sel);
                if ($this->chave === 0):
                    $ins = "insert into $tabela($tipo) values('" . $valor . "')";
                    $x   = $this->conn->query($ins);
                    switch ($tabela):
                        case "cadastro": {
                                $this->id_cliente              = $this->conn->insert_id;
                                $this->loopDados['cadastro'][] = $this->conn->insert_id;
                            } break;
                        case "email": {
                                $this->email_id             = $this->conn->insert_id;
                                $this->loopDados['email'][] = $this->conn->insert_id;
                            } break;
                        case "telefone": {
                                $this->telefone_id             = $this->conn->insert_id;
                                $this->loopDados['telefone'][] = $this->conn->insert_id;
                            }break;
                    endswitch;
                else: $this->loopDados[$tabela][] = $this->chave;
                endif;
                $this->chave = 0;
                ///////////////////////////////////////
            }
        endforeach;
    }










    private $chave = 0;
    private function VerificaExistencia($tabela = '', $consulta = '') {
        $sel   = "select * from " . $tabela . " " . $consulta;  ///echo"<br><br>$sel";
        $x     = $this->conn->query($sel);
        while ($dados = mysqli_fetch_array($x)):

            if ($dados['id'] > 0 && !is_null($dados['id'])) {
                if (is_numeric($dados['id'])):

                    $this->chave = $dados['id'];  ///echo"<hr>$sel"; echo"<br>a chave é ".$this->chave;

                    switch ($tabela):
                        case "cadastro": {
                                $this->id_cliente = $dados['id'];
                                $this->chave      = $dados['id'];
                            }
                            break;
                        case "email": {
                                $this->email_id = $dados['id'];
                                $this->chave    = $dados['id'];
                            }
                            break;
                        case "telefone": {
                                $this->telefone_id = $dados['id'];
                                $this->chave       = $dados['id'];
                            }
                            break;
                    endswitch;
                endif;
            }
        endwhile;
    }










    private function Limpeza($x = '') {
        if (!is_null($x)):
            $informacao = strip_tags($x);
            $informacao = trim($informacao);
            return $informacao;
        endif;
    }










}
