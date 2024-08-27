<?php

include "../validarLojista.php";
include "conexao.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = clear($conn, $_POST['idUsuario']);
    $nome = clear($conn, $_POST['nomeUsuario']);
    $emailUsuario = clear($conn, $_POST['emailUsuario']);
    $enderecoUsuario = clear($conn, $_POST['enderecoUsuario']);
    $telefoneLojista = clear($conn, $_POST['telefone']);
    $nomeEstabelecimento = clear($conn, $_POST['nomeEstabelecimento']);
    $nomeFoto = salvarFoto($_FILES['imagem'], '../img/');
    $nomeFotoEstabelecimento = salvarFoto($_FILES['imagem_estabelecimento'], '../img/');

    // Verificar se o email já existe para outro usuário
    $sqlVerificaEmail = "SELECT id FROM Lojistas WHERE email = ? AND id != ?";
    $stmtVerificaEmail = $conn->prepare($sqlVerificaEmail);

    // Verifica se houve erro na preparação da consulta
    if (!$stmtVerificaEmail) {
        echo "Erro na preparação da consulta: " . $conn->error;
        exit;
    }

    $stmtVerificaEmail->bind_param('si', $emailUsuario, $id);
    $stmtVerificaEmail->execute();

    // Verifica se houve erro na execução da consulta
    if (!$stmtVerificaEmail) {
        echo "Erro na execução da consulta: " . $conn->error;
        exit;
    }

    $stmtVerificaEmail->store_result();

    if ($stmtVerificaEmail->num_rows > 0) {
        echo "O email já está em uso por outro usuário.";
        exit;
    }

    if ($nomeFoto == 1 || $nomeFotoEstabelecimento == 1) {
        echo "Arquivo no formato incorreto ou muito grande.";
        exit;
    }

    // Atualizar a imagem do usuário, se houver
    if (!empty($nomeFoto)) {
        // Buscar a imagem antiga do usuário no banco de dados
        $sqlBuscaImagemUsuario = "SELECT imagem_usuario FROM lojistas WHERE id = ?";
        $stmtBuscaImagemUsuario = $conn->prepare($sqlBuscaImagemUsuario);
        $stmtBuscaImagemUsuario->bind_param('i', $id);
        $stmtBuscaImagemUsuario->execute();
        $stmtBuscaImagemUsuario->bind_result($imagemUsuarioAntiga);
        $stmtBuscaImagemUsuario->fetch();
        $stmtBuscaImagemUsuario->close();

        // Excluir a imagem antiga do usuário do sistema de arquivos
        if (!empty($imagemUsuarioAntiga) && file_exists("../img/$imagemUsuarioAntiga")) {
            unlink("../img/$imagemUsuarioAntiga");
        }

        // Atualizar o registro com a nova imagem do usuário
        $sqlUpdateUsuario = "UPDATE lojistas SET nome=?, nome_estabelecimento=?, endereco=?, email=?, telefone=?, imagem_usuario=? WHERE id=?";
        $stmtUpdateUsuario = $conn->prepare($sqlUpdateUsuario);
        $stmtUpdateUsuario->bind_param('ssssssi', $nome, $nomeEstabelecimento, $enderecoUsuario, $emailUsuario, $telefoneLojista, $nomeFoto, $id);
        $stmtUpdateUsuario->execute();
        $stmtUpdateUsuario->close();
    }

    // Atualizar a imagem do estabelecimento, se houver
    if (!empty($nomeFotoEstabelecimento)) {
        // Buscar a imagem antiga do estabelecimento no banco de dados
        $sqlBuscaImagemEstabelecimento = "SELECT imagem_empresa FROM lojistas WHERE id = ?";
        $stmtBuscaImagemEstabelecimento = $conn->prepare($sqlBuscaImagemEstabelecimento);
        $stmtBuscaImagemEstabelecimento->bind_param('i', $id);
        $stmtBuscaImagemEstabelecimento->execute();
        $stmtBuscaImagemEstabelecimento->bind_result($imagemEstabelecimentoAntiga);
        $stmtBuscaImagemEstabelecimento->fetch();
        $stmtBuscaImagemEstabelecimento->close();

        // Excluir a imagem antiga do estabelecimento do sistema de arquivos
        if (!empty($imagemEstabelecimentoAntiga) && file_exists("../img/$imagemEstabelecimentoAntiga")) {
            unlink("../img/$imagemEstabelecimentoAntiga");
        }

        // Atualizar o registro com a nova imagem do estabelecimento
        $sqlUpdateEstabelecimento = "UPDATE lojistas SET nome=?, nome_estabelecimento=?, endereco=?, email=?, telefone=?, imagem_empresa=? WHERE id=?";
        $stmtUpdateEstabelecimento = $conn->prepare($sqlUpdateEstabelecimento);
        $stmtUpdateEstabelecimento->bind_param('ssssssi', $nome, $nomeEstabelecimento, $enderecoUsuario, $emailUsuario, $telefoneLojista, $nomeFotoEstabelecimento, $id);
        $stmtUpdateEstabelecimento->execute();
        $stmtUpdateEstabelecimento->close();
    }

    // Se nenhuma imagem foi atualizada
    if (empty($nomeFoto) && empty($nomeFotoEstabelecimento)) {
        // Atualizar o registro sem alterar as imagens
        $sqlUpdateSemImagem = "UPDATE lojistas SET nome=?, nome_estabelecimento=?, endereco=?, email=?, telefone=? WHERE id=?";
        $stmtUpdateSemImagem = $conn->prepare($sqlUpdateSemImagem);
        $stmtUpdateSemImagem->bind_param('sssssi', $nome, $nomeEstabelecimento, $enderecoUsuario, $emailUsuario, $telefoneLojista, $id);
        $stmtUpdateSemImagem->execute();
        $stmtUpdateSemImagem->close();
    }

    // Verificar se algum dos updates teve sucesso
    if ($stmtUpdateUsuario || $stmtUpdateEstabelecimento || $stmtUpdateSemImagem) {
        echo "Item atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar o item: " . $conn->error;
    }

    $stmtVerificaEmail->close();
    $conn->close();
    header('location: lojistaLojista.php');
    exit;
}

