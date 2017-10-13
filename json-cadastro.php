<?php

if (isset($_REQUEST)):
    header('Access-Control-Allow-Origin: *');

    print_r($_REQUEST['dados']);

    $dados             = array();
    $dados['login']    = $_REQUEST['dados']['login'];
    $dados['senha']    = $_REQUEST['dados']['senha'];
    $dados['cpf']      = $_REQUEST['dados']['cpf'];
    $dados['nome']     = $_REQUEST['dados']['nome'];
    $dados['email']    = $_REQUEST['dados']['email'];
    $dados['telefone'] = $_REQUEST['dados']['telefone'];
    
    require 'classe/conex.php';
    require_once 'classe/cadastro.php';


    $x = new cadastro($dados);
    $x->cadastra();
    $x->CadastraEmail();
    $x->EmailUsuario();

    $retorno = array("mensagem" => $x->mensagem);
    echo json_encode($retorno);

endif;