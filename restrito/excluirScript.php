<?php
include "../validarLojista.php";
include "conexao.php";
include "../scripts.php";

$conn = pegarConexao('lojista');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = clear($conn, $_POST['id']);
    
    // Obter o caminho da imagem do produto antes de deletar
    $queryImagem = "SELECT imagem, imagem2, imagem3 FROM produtos WHERE id = '$id'";
    $result = $conn->query($queryImagem);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imagem = '../img/' . $row['imagem'];
        $imagem2 = '../img/' . $row['imagem2'];
        $imagem3 = '../img/' . $row['imagem3'];

        // Excluir o produto
        $SQL = "DELETE FROM produtos WHERE id = '$id'"; 

        if ($conn->query($SQL) === TRUE) {
            unlink($imagem);
            unlink($imagem2);
            unlink($imagem3);
            header('Location: lojistaLojista.php');
        } else {
            echo "Erro ao excluir item: " . $conn->error;
        }
    } else {
        echo "Produto não encontrado.";
    }
} else {
    echo "Requisição inválida para exclusão";
}

$conn->close();
