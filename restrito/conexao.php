<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mercazon";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// validando a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
} else {
    // echo "Conexão bem-sucedida";
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




