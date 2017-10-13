<?php

class cadastro extends conex
    {

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
    private $arrayDados;
    private $booleanInf;

    public function __construct($dados = '') {
        $this->mensagem = "ok";
        if (is_array($dados)) {
            $this->entradaDados = $dados;
            $this->vinculo      = 0;
        }
        $this->conn = $this->Conex();
        $this->id   = 0;
    }





    public function VerificaDados($sel = 'user') {
        if (isset($sel)):

            $this->cpf   = $this->entradaDados['cpf'];
            $this->login = $this->entradaDados['login'];
            $this->senha = md5($this->entradaDados['senha']);
            $this->nome  = $this->entradaDados['nome'];


            if (!isset($this->entradaDados['nome'])) {
                $this->mensagem = "falta o nome";
                die();
        }
            if (!isset($this->entradaDados['cpf']))   {
                $this->mensagem = "falta o cpf";
                die();
        }
            if (!isset($this->entradaDados['login'])) {
                $this->mensagem = "falta o login";
                die();
        }
            if (!isset($this->entradaDados['senha'])) {
                $this->mensagem = "falta a senha";
                die();
        }


            if ($sel === "user"){
                $select = "select * from cadastro where cpf='" . $this->cpf . "' and login='" . $this->login . "' and senha='" . $this->senha . "'";
            }
            if ($sel === "email"){
                $select = "select * from email where email='" . $this->email . "'";
            }
            if ($sel === "emailCadastro"){
                $select = "select * from `cadastro-email` where email='" . $this->email_id . "' and cadastro ='" . $this->id . "'";
            }

            //echo "<hr><hr><hr>$sel";

            $x     = $this->conn->query($select);
            while ($dados = mysqli_fetch_array($x)):
                if ($sel === "email"){
                    $this->email_id = 0;
                    $this->email_id = $dados['id'];
                }
                if ($sel === "user"){
                    $this->id = 0;
                    $this->id = $dados['id'];
                }

            endwhile;
        endif;
    }





    public function CadastraEmail() {
        $this->entradaDados['email'];
        if (is_array($this->entradaDados['email'])):
            foreach ($this->entradaDados['email'] as $email):
                $this->email = $email;

                if (!is_null($email) && !empty($email)){
                    //echo "<hr><hr>!!!informação " . $email;

                    $this->VerificaDados('email');
                    $this->arrayDados[] = $this->email_id;

                    if ($this->email_id == 0){
                        $x                  = $this->Query("insert into email (email)values('" . $this->email . "')");
                        $this->email_id     = $x->insert_id;
                        $this->arrayDados[] = $this->email_id;
                  }
                  }
            endforeach;
        endif;
    }





    public function EmailUsuario() {

        //echo"<p> array email -- ";
        print_r($this->arrayDados);

        if (count($this->arrayDados) > 0 && $this->id > 0){
            $this->Query("delete from `cadastro-email` where cadastro = '" . $this->id . "'");
            foreach ($this->arrayDados as $mail):

                if (!empty($mail)):
                    $this->email_id = $mail;
                    $this->Query("insert into `cadastro-email` (cadastro, email)values('" . $this->id . "','" . $this->email_id . "')");
                endif;

            endforeach;
        }
    }





    public function cadastra() {
        $this->VerificaDados('user');
        if ($this->id != 0) {
            return $this->id;
        }
        $cpf            = $this->cpf;
        $login          = $this->login;
        $senha          = $this->senha;
        $rg             = $this->rg;
        $nascimento     = $this->nascimento;
        $vinculo        = $this->vinculo;
        $data_expedicao = $this->data_expedicao;
        $nome           = $this->nome;

        $sql      = "insert into cadastro (login,senha,nome,cpf,rg,vinculo,data_nascimento,data_expedicao) "
                . "values('" . $login . "','" . $senha . "' ,'" . $nome . "','" . $cpf . "','" . $rg . "','" . $vinculo . "','" . $this->nascimento . "','" . $data_expedicao . "')";
        $cc       = $this->Query($sql);
        $this->id = $cc->insert_id;
    }





    private function Query($x = "") {
        //echo"<br><br>";
        //echo "consulta " . $x;
        $query = $this->conn;
        if ($query->query($x)) {
            //echo"funcionou";
            return $query;
        } else {
            //echo"deu erro";
            $this->mensagem = $query->error;
        }
    }





    }
