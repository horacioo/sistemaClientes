<?php

if (isset($_REQUEST)):
    header('Access-Control-Allow-Origin: *');

    require 'classe/conex.php';
    require_once 'classe/cadastro.php';

    $x       = new cadastro($_REQUEST['dados']);
    
    $x->cadastra();
    $x->CadastraEmail();
    $x->CadastraTelefone();
    $x->Associa();
    
    
    $retorno = array("mensagem" => $x->mensagem);
    echo json_encode($retorno);

endif;