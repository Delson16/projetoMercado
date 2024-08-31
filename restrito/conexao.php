<?php

function pegarConexao($tipoUsuario){
    $servername = "localhost";
    
    if($tipoUsuario === 'usuario'){
        $username = 'usuario';
        $password = 'F7!vR$4x^Tp9@aJq';
    } else if ($tipoUsuario === 'lojista'){
        $username = 'lojista';
        $password = '7$Lz*R@8e%wX!qGm';
    }
    $dbname = "mercazon";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if($conn->connect_error){
        return 0;
    } else {
        return $conn;
    }
}