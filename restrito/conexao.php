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

function clear($conexao, $texto){
    $textoLimpo = mysqli_real_escape_string($conexao, $texto);
    $textoLimpo = htmlspecialchars($texto);
    return $textoLimpo;
}

// fução que valida e faz upload da imagem. 
function salvarFoto($foto, $local){

    if(!($foto['error'])){

        $nomeExtensao = explode('/', $foto['type']);

        // tamanho limite do arquivo 1.5mb. Deve ter a extensão image
        if($foto['size'] <= 10000000){
            
            $nomeFoto =  $foto['name'] . date('Y-m-d H:i:s');
            $nomeFoto = md5($nomeFoto) . "." . $nomeExtensao[1];

            move_uploaded_file($foto['tmp_name'], $local . $nomeFoto);

            return $nomeFoto;
        } else{
            // arquivo no formato incorreto ou muito grande
            return 1;
        }
    } else{
        // erro no upload de arquivo
        return 0;
    }
}

/*

USE mercazon;

CREATE USER 'usuario'@'localhost' IDENTIFIED BY 'F7!vR$4x^Tp9@aJq';

GRANT INSERT, UPDATE, DELETE, SELECT ON mercazon.usuarios TO 'usuario'@'localhost';
GRANT INSERT, UPDATE, DELETE, SELECT ON mercazon.usuario_favorita_produto TO 'usuario'@'localhost';
GRANT SELECT ON mercazon.produtos TO 'usuario'@'localhost';
GRANT SELECT ON mercazon.lojistas TO 'usuario'@'localhost';

CREATE USER 'lojista'@'localhost' IDENTIFIED BY '7$Lz*R@8e%wX!qGm';

GRANT SELECT, INSERT, UPDATE, DELETE ON mercazon.produtos TO 'lojista'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON mercazon.lojistas TO 'lojista'@'localhost';

FLUSH PRIVILEGES;
*/




