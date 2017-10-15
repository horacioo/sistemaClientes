<?php

if (isset($_REQUEST)):
    header('Access-Control-Allow-Origin: *');

    require 'classe/conex.php';
    require_once 'classe/cadastro.php';

   ///print_r($_REQUEST['dados']);

    $x       = new cadastro($_REQUEST['dados']);
    $x->cadastra();
    
    $retorno = array("mensagem" => $x->mensagem);
    echo json_encode($retorno);

endif;