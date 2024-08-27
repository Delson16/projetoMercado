<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['idUser'])) {
    $user = $_SESSION['idUser'];
} else {
    session_destroy();
    header('Location: ../index.php?msg=Você precisa estar logado para acessar essa página!');
}
