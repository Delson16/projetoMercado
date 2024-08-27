<?php 

include "../validarLojista.php";
include "conexao.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = clear($conn,$_POST['id']);
    $nome = clear($conn, $_POST['nome']);
    $categoria = clear($conn, $_POST['categoria']);
    $preco = clear($conn, $_POST['preco']);
    
    $nomeFoto = salvarFoto($_FILES['imagem'], "../img/");
        if ($nomeFoto == 1) {
            echo "arquivo no formato incorreto ou muito grande";
        }
         if (empty($nomeFoto)) {
            $sqlUptade = "UPDATE produtos SET nome=?, categoria=?, preco=? WHERE id=?";
            $stmt = $conn->prepare($sqlUptade);
            $stmt->bind_param('ssdi', $nome, $categoria, $preco, $id);
            //s=string, d=double, i=integer

            if ($stmt->execute()) {
                echo "Item atualizado com sucesso!";
            } else {
                echo "Erro ao atualizar o item: " . $conn->error;
            }

            $stmt->close();
        }
        else {
            $sqlUptade = "UPDATE produtos SET nome=?, categoria=?, preco=?, imagem=? WHERE id=?";
            $stmt = $conn->prepare($sqlUptade);
            $stmt->bind_param('ssdsi', $nome, $categoria, $preco, $nomeFoto, $id);

            if ($stmt->execute()) {
                echo "Item atualizado com sucesso!";
            } else {
                echo "Erro ao atualizar o item: " . $conn->error;
            }

            $stmt->close();
        }
        $conn->close();
        header('Location: cadastroProduto.php');

} 

