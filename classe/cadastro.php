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
        $this->arrayDados = array("cadastro", "email", "telefone");
        $this->mensagem   = "ok";

        if (is_array($dados)) {
            $this->entradaDados = $dados;
            $this->vinculo      = 0;
        }
        $this->conn        = $this->Conex();
        $this->id          = 0;
        $this->id_cliente  = 0;
        $this->email_id    = 0;
        $this->telefone_id = 0;
    }










    public function cadastra($x = '') {
        $dados = $this->entradaDados;
        foreach ($this->arrayDados as $d):
//--------------------------
            $this->CriaEconsulta($dados[$d], $d);
//--------------------------
        endforeach;
        $this->AssociaDados();
    }










    private function AssociaDados() {
        $cadastro = $this->ConjuntoDeDadosAssociacao['cadastro'];
        unset($this->ConjuntoDeDadosAssociacao['cadastro']);
        foreach ($this->arrayDados as $dados):
            $x = $this->ConjuntoDeDadosAssociacao[$dados];
            if (is_array($x)):
                foreach ($x as $zz):
                    $sel = "insert into `cadastro-" . $dados . "` (cadastro,email) values('" . $cadastro . "','" . $zz . "')";
                    $this->conn->query($sel);
                endforeach;
            endif;
        endforeach;
    }










    private function CriaEconsulta($x = '', $type = '') {
        $chave = array_keys($x);
        switch ($type):
            case "cadastro": {
                    $this->nome           = $this->Limpeza($x['nome']);
                    $this->cpf            = $this->Limpeza($x['cpf']);
                    $this->login          = $this->Limpeza($x['login']);
                    $this->rg             = $this->Limpeza($x['rg']);
                    $this->nascimento     = $this->Limpeza($x['data_nascimento']);
                    $this->data_expedicao = $this->Limpeza($x['	data_expedicao']);
                    $this->senha          = hash('whirlpool', $this->Limpeza($x['senha']));
                    $this->ProcessaDados(array("type" => "cadastro"));
                }break;
            case"email": {
                    foreach ($x as $email):
                        $this->email = $email;
                        $this->ProcessaDados(array("type" => "email", "email" => $this->email));
                    endforeach;
                } break;
            case"telefone": {
                    foreach ($x as $telefone):
                        $this->telefone = $telefone;
                        $this->ProcessaDados(array("type" => "telefone", "telefone" => $this->telefone));
                    endforeach;
                } break;
        endswitch;
    }










    /*     * * enviar os dados no formato array('type'=>"cadastro");*  */

    private function ProcessaDados($info) {
        if (is_array($info)):
            $consultas = $this->DecideQuery($info['type']);
            print_r($consultas);
            /*             * ************************** */
            $x         = $this->conn->query($consultas['procura']);
            while ($dados     = mysqli_fetch_array($x)):
                switch ($info['type']):
                    case "cadastro": {
                            $this->id_cliente                            = $dados['id'];
                            $this->ConjuntoDeDadosAssociacao['cadastro'] = $dados['id'];
                        }
                        break;
                    case "email": {
                            $this->ConjuntoDeDadosAssociacao['email'][] = $dados['id'];
                            $this->email_id                             = $dados['id'];
                        } break;
                    case "telefone": {
                            $this->telefone_id                             = $dados['id'];
                            $this->ConjuntoDeDadosAssociacao['telefone'][] = $dados['id'];
                        } break;
                        break;
                endswitch;
            endwhile;


            /*             * ************************** */
            if ($this->id_cliente === 0) {
                $this->conn->query($consultas['insere']);
            }
            if ($this->email_id === 0) {
                //echo "<hr>insere " . $consultas['insere'];
                $this->conn->query($consultas['insere']);
            }
            if ($this->telefone_id === 0) {
                //echo "<hr>insere " . $consultas['insere'];
                $this->conn->query($consultas['insere']);
            }
        /*         * ************************** */
        endif;
    }










    /*     * *********** */

    private function DecideQuery($x = "") {

        switch ($x):
            case "cadastro": {
                    $dados = array(
                        "procura" => "select * from cadastro where cpf = '" . $this->cpf . "' and login ='" . $this->login . "' and senha='" . $this->senha . "'",
                        "insere"  => "insert into cadastro(nome,login,senha,cpf,rg,data_nascimento,data_expedicao) values('" . $this->nome . "','" . $this->login . "','" . $this->senha . "','" . $this->cpf . "','" . $this->rg . "','" . $this->nascimento . "','" . $this->data_expedicao . "')"
                    );
                    return $dados;
                }
                break;
            case "email": {
                    $dados = array(
                        "procura" => "select * from email where email = '" . $this->email . "'",
                        "insere"  => "insert into email(email) values('" . $this->email . "')"
                    );
                    return $dados;
                }break;
            case "telefone": {
                    $dados = array(
                        "procura" => "select * from telefone where telefone = '" . $this->telefone . "'",
                        "insere"  => "insert into telefone(telefone) values('" . $this->telefone . "')"
                    );
                    return $dados;
                }break;

        endswitch;
    }










    protected function Query($x = "") {
        $query = $this->conn;
        //echo "<br>" . $x . "<br>";
        if ($query->query($x)) {
            return $query;
        } else {
            $this->mensagem = $query->error;
        }
    }










    private function Limpeza($x = '') {
        if (!is_null($x)):
            $informacao = strip_tags($x);
            $informacao = trim($informacao);
            return $informacao;
        endif;
    }










}
