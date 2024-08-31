<?php
include "../validarLojista.php";
include "conexao.php";
$conn = pegarConexao('lojista');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    
    $SQL = "DELETE FROM produtos WHERE id = '$id'"; 

    if ($conn->query($SQL) === TRUE) {
        unlink($imagem);
        header('Location: lojistaLojista.php');
    } else {
        echo "Erro ao excluir item: " . $conn->error;
    }
} else {
    echo "Requisição inválida para exclusão";
}

$conn->close();
