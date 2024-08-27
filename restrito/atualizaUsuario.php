<?php

include "../validarUsuario.php";
include "conexao.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = clear($conn, $_POST['idUsuario']);
    $nome = clear($conn, $_POST['nomeUsuario']);
    $emailUsuario = clear($conn, $_POST['emailUsuario']);
    $enderecoUsuario = clear($conn, $_POST['enderecoUsuario']);
    $dataUsuario = clear($conn, $_POST['dataUsuario']);
    $nomeFoto = salvarFoto($_FILES['imagem'], '../img/');

    // Verificar se o email já existe para outro usuário
    $sqlVerificaEmail = "SELECT id FROM usuarios WHERE email = ? AND id != ?";
    $stmtVerificaEmail = $conn->prepare($sqlVerificaEmail);
    $stmtVerificaEmail->bind_param('si', $emailUsuario, $id);
    $stmtVerificaEmail->execute();
    $stmtVerificaEmail->store_result();

    if ($stmtVerificaEmail->num_rows > 0) {
        echo "O email já está em uso por outro usuário.";
        exit;
    }

    if ($nomeFoto == 1) {
        echo "arquivo no formato incorreto ou muito grande";
        exit;
    }

    if (empty($nome) || empty($emailUsuario) || empty($enderecoUsuario) || empty($dataUsuario)) {
        $sqlBuscaImagem = "SELECT imagem_usuario FROM usuarios WHERE id = ?";
        $stmtBuscaImagem = $conn->prepare($sqlBuscaImagem);
        $stmtBuscaImagem->bind_param('i', $id);
        $stmtBuscaImagem->execute();
        $stmtBuscaImagem->bind_result($imagemAntiga);
        $stmtBuscaImagem->fetch();
        $stmtBuscaImagem->close();

        // Excluir a imagem antiga do sistema de arquivos
        if (!empty($imagemAntiga) && file_exists("../img/$imagemAntiga")) {
            unlink("../img/$imagemAntiga");
        }

        $sqlUpdate2 = "UPDATE usuarios SET imagem_usuario=? WHERE id=?";
        $stmtUpdate = $conn->prepare($sqlUpdate2);
        $stmtUpdate->bind_param('si', $nomeFoto, $id);
    }

    else if (!empty($nomeFoto)) {
        //Buscar a imagem antiga no banco de dados
        $sqlBuscaImagem = "SELECT imagem_usuario FROM usuarios WHERE id = ?";
        $stmtBuscaImagem = $conn->prepare($sqlBuscaImagem);
        $stmtBuscaImagem->bind_param('i', $id);
        $stmtBuscaImagem->execute();
        $stmtBuscaImagem->bind_result($imagemAntiga);
        $stmtBuscaImagem->fetch();
        $stmtBuscaImagem->close();

        // Excluir a imagem antiga do sistema de arquivos
        if (!empty($imagemAntiga) && file_exists("../img/$imagemAntiga")) {
            unlink("../img/$imagemAntiga");
        }

        // Atualizar o registro com a nova imagem
        $sqlUpdate = "UPDATE usuarios SET nome=?, email=?, endereco=?, data_nascimento=?, imagem_usuario=? WHERE id=?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param('sssssi', $nome, $emailUsuario, $enderecoUsuario, $dataUsuario, $nomeFoto, $id);
    } else {
        // Atualizar o registro sem alterar a imagem
        $sqlUpdate = "UPDATE usuarios SET nome=?, email=?, endereco=?, data_nascimento=? WHERE id=?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param('ssssi', $nome, $emailUsuario, $enderecoUsuario, $dataUsuario, $id);
    }

    if ($stmtUpdate->execute()) {
        echo "Item atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar o item: " . $conn->error;
    }

    $stmtUpdate->close();
    $stmtVerificaEmail->close();
    $conn->close();
    header('Location: usuario.php');
    exit;
}

