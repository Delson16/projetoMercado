<?php
include "../validar.php";
include "conexao.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $imagem = $_POST['imagemProdutoExclusao'];
    
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
