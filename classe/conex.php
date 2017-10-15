<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of conex
 *
 * @author horacio
 */
class conex{

    protected $mysqli;

    public function __construct() {
        $this->Conex();
    }










    public function Conex() {
        $servidor = '108.167.132.48';
        $usuario  = 'plane827_app';
        $senha    = 'fanzine2017';
        $banco    = 'plane827_app';
        $mysqli   = new mysqli($servidor, $usuario, $senha, $banco);
        if (mysqli_connect_errno())
            trigger_error(mysqli_connect_error());
        return $mysqli;
    }










}
