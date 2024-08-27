<?php
session_start();
include_once 'conexao.php';

if (isset($_POST['idFavorito']) && isset($_POST['user'])) {
    $idProduto = $_POST['idFavorito'];
    $user = $_POST['user'];

    $sql = "SELECT id FROM usuario_favorita_produto WHERE id_usuario = $user AND id_produto = $idProduto";
    $resultado = $conn->query($sql);
    $numLinha = mysqli_num_rows($resultado);

    if ($numLinha > 0) {
        $sql = "DELETE FROM usuario_favorita_produto WHERE id_usuario = $user AND id_produto = $idProduto;";
        if ($conn->query($sql)) {
        } else {
        }
    } else {
        $sql = "INSERT INTO usuario_favorita_produto (id_usuario, id_produto) VALUES ('$user', '$idProduto');";
        if ($conn->query($sql)) {
        } else {
        }
    }
}
