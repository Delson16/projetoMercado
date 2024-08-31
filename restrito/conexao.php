<?php

function pegarConexao($tipoUsuario){
    $servername = "localhost";
    
    if($tipoUsuario === 'usuario'){
        $username = 'root';
        $password = '';
    } else if ($tipoUsuario === 'lojista'){
        $username = 'root';
        $password = '';
    }
    $dbname = "mercazon";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if($conn->connect_error){
        return 0;
    } else {
        return $conn;
    }
}