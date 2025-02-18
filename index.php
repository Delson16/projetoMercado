<!DOCTYPE html>
<html lang="pt-br">

<!-- Códigos de acessibilidade -->
<script src="https://cdn.userway.org/widget.js" data-account="kCDHqw9ltL"></script>
  <div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
      <div class="vw-plugin-top-wrapper"></div>
    </div>
  </div>
  <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
  <script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
  </script>

<head>
    <meta name="description" content="Descubra produtos únicos de microempreendedores locais em nosso marketplace. Apoie pequenos negócios e encontre artigos artesanais, moda exclusiva, e mais.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="pgInicial.css">
    <link rel="stylesheet" href="pgPadrao.css">
    <link rel="shortcut icon" href="../img/img pg inicial/logoAmareloEscuro.png" type="image/x-icon">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mercazon</title>
</head>
<!--PHP Login e cadastro-->
<?php
include "scripts.php";
session_start();
$user = isset($_SESSION['idUser']) ? $_SESSION['idUser'] : -1;
// Código referente ao login do usuário
if (isset($_POST['loginSubmit'])) {
    include_once "restrito/conexao.php";

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $sql = "SELECT id, email, senha, nome FROM usuarios WHERE email = '$email'";
    $resultado = $conn->query($sql);
    $numLinha = mysqli_num_rows($resultado);

    if ($numLinha > 0) {
        $linha = mysqli_fetch_array($resultado);
        $senha = mysqli_real_escape_string($conn, $_POST['senha']);

        if (password_verify($senha, $linha['senha'])) {

            session_start();
            $_SESSION['idUser'] = $linha['id'];
            $_SESSION['nome'] = $linha['nome'];
            header('location: restrito/usuario.php');
        } else {
                    echo "<script>
            alert('senha ou email errados. Tente novamente!');
                </script>";
            header('location: index.php');
        }
    } else {
                echo "<script>
            alert('senha ou email errados. Tente novamente!');
                </script>";
            header('location: index.php');
    }

    $conn->close();
}

// código referente ao cadastro do usuário
if (isset($_POST['cadastroSubmit'])) {

    include_once "restrito/conexao.php";

    $email = clear($conn, $_POST['email']);

    $sql = "SELECT id FROM usuarios WHERE email = '$email'";
    $resultado = mysqli_query($conn, $sql);
    $numLinha = mysqli_num_rows($resultado);

    if ($numLinha > 0) {
        echo "<script>
            alert('Este e-mail ja está em uso. Tente com outro');
                    </script>";
    } else {
        $nome = clear($conn, $_POST['nome']);
        $endereco = clear($conn, $_POST['endereco']);
        $data = clear($conn, $_POST['data_nascimento']);
        $senha = clear($conn, $_POST['senha']);
        //para aumentar a segurança (criptografia) 
        $senha = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nome, email, endereco, data_nascimento, senha) VALUE ('$nome', '$email', '$endereco', '$data', '$senha')";

        if ($conn->query($sql) === TRUE) {

            $sql = "SELECT id, nome FROM usuarios WHERE email = '$email'";
            $resultado = mysqli_query($conn, $sql);
            $linha = mysqli_fetch_array($resultado);

            session_start();
            $_SESSION['idUser'] = $linha['id'];
            $_SESSION['nome'] = $linha['nome'];
            header('location: restrito/usuario.php');
        } else {
            echo "<script>
            alert('Houve um problema no cadastro. Por favor, tente mais tarde.');
                    </script>";
            header('location: index.php');
        }
    }
    // fechar a conexão, ira abrir novamente quando outro usuário entrar
    $conn->close();
}

// Código referente ao login do lojista
if (isset($_POST['loginLojista'])) {

    include_once "restrito/conexao.php";

    $tipo_usuario = 'lojista';
    $conn = pegarConexao($tipo_usuario);

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $sql = "SELECT id, senha, nome FROM lojistas WHERE email = '$email'";
    $resultado = $conn->query($sql);
    $numLinha = mysqli_num_rows($resultado);

    if ($numLinha > 0) {
        $linha = mysqli_fetch_array($resultado);
        $senha = mysqli_real_escape_string($conn, $_POST['senha']);

        if (password_verify($senha, $linha['senha'])) {

            session_start();
            $_SESSION['idLojista'] = $linha['id'];
            $_SESSION['nome'] = $linha['nome'];
            header('location: restrito/lojistaLojista.php');
        } else {
            echo "<script>
            alert('senha ou email errados. Tente novamente!');
                    </script>";
                        header('location: index.php');
        }
    } else {
        echo "<script>
            alert('senha ou email errados. Tente novamente!');
                </script>";
            header('location: index.php');
    }

    $conn->close();
}

// codigo referente ao cadastro do lojista
if (isset($_POST['cadastroLojistaSubmit'])) {

    include_once "restrito/conexao.php";
    $email = clear($conn, $_POST['email']);

    $sql = "SELECT id FROM lojistas WHERE email = '$email'";
    $resultado = mysqli_query($conn, $sql);
    $numLinha = mysqli_num_rows($resultado);

    if ($numLinha > 0) {
        echo "<p class='aviso'>Este e-mail ja está em uso.</p>";
    } else {
        $fotoUsuario = salvarFoto($_FILES['imagemUsuario'], "img/");

        if ($fotoUsuario == 0) {
            echo "<script>
                    alert('Houve um erro no upload da imagem de usuário. Tente novamente mais tarde.');
                    </script>";
        } else if ($fotoUsuario == 1) {
            echo "<script>
                    alert('a imagem do lojista esta em um formato não aceito ou é muito grande.<br>Aceitamos arquivos nos seguintes formatos: JPEG, PNG ou SVG.<br>O tamanho limite para imagens é de 1.5mb');
                    </script>";
        } else {

            $fotoEmpresa = salvarFoto($_FILES['imagemEmpresa'], "img/");

            if ($fotoEmpresa == 0) {
                echo "<script>
                    alert('Houve um erro no upload da imagem da empresa. Tente novamente mais tarde.');
                    </script>";
            } else if ($fotoEmpresa == 1) {
                echo "<script>
                    alert('a imagem da empresa esta em um formato não aceito ou é muito grande.<br>Aceitamos arquivos nos seguintes formatos: JPEG, PNG ou SVG.<br>O tamanho limite para imagens é de 1.5mb');
                    </script>";
            } else {
                $endereco = CLEAR($conn, $_POST['endereco']);
                $senha = password_hash(CLEAR($conn, $_POST['senha']), PASSWORD_DEFAULT);
                $telefone = CLEAR($conn, $_POST['telefone']);
                $nome = CLEAR($conn, $_POST['nome']);
                $nomeEstabelecimento = CLEAR($conn, $_POST['nomeEstabelecimento']);

                $sql = "INSERT INTO lojistas (nome, nome_estabelecimento, endereco, email, senha, telefone, imagem_empresa, imagem_lojista) VALUES ('$nome', '$nomeEstabelecimento', '$endereco', '$email', '$senha', '$telefone', '$fotoEmpresa', '$fotoUsuario')";

                if (mysqli_query($conn, $sql)) {

                    $sql = "SELECT id, nome FROM lojistas WHERE email  = '$email'";

                    $resultado = mysqli_query($conn, $sql);
                    $linha = mysqli_fetch_array($resultado);

                    session_start();
                    $_SESSION['idLojista'] = $linha['id'];
                    $_SESSION['nome'] = $linha['nome'];
                    header('location: restrito/lojistaLojista.php');
                } else {
                    echo "<script>
                                    alert('Houve um erro na realização do cadastro. Tente novamente mais tarde.');
                                </script>";
                }
            }

        }

    }
    $conn->close();
}
?>
<!--PHP Login e cadastro-->

<body>

<header>
        <nav class="cabecalhoSuperior">
            <div class="d-flex">
                <a href="guiaDoLojista.php">Guia do lojista</a>
                <h9>|</h9>
                <a href="Contato.php">Suporte</a>
            </div>
        </nav>

        <nav class="cabecalhoInferior">
            <a class="logoMercazon" href="index.php">
                <img src="img/icons/logoAmareloEscuro.png" alt="Logo Mercazon" data-aos="zoom-in">
            </a>

            <form action="produtosBusca.php" class="filtroNome pesquisaCentral" method="POST" >
                <input type="text" placeholder="Busque Seus Produtos" name="nome" >
                <button type="submit" name="filtro" value="preco"><img src="img/icons/lupa.png" alt="Lupa de pesquisa"></button>
            </form>



            <div class="d-flex">
                <?php imagemPerfilHeader()?>
                <div class="dropdown">
                    <div aria-label="Adicionar aos favoritos" role="button" src="" alt="Coração de favoritos"
                        class="naoClicado" id="favoritos" data-bs-toggle="dropdown" aria-expanded="false"></div>
                    <ul class="dropdown-menu">
                        <?php dropdownHeader() ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
<!----------------------------- Conjunto de modais ----------------------------->

    <!-- Modal de login de usuário -->
    <div class="modal fade" id="modalLoginUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Login</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" id="formLoginUsuario"
                        onsubmit="RequisicaoPhpLogin('formLoginUsuario', event)">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email">
                        <label for="senha">Senha</label>
                        <div>
                            <img class="olho" src="img/icons/olhofechado.png" alt="icone de olho aberto"
                                onclick="mostrarSenha(this)">
                            <input type="password" name="senha" id="senha">
                        </div>
                        <span class="aviso"></span>
                        <a href="#">Esqueceu Sua Senha?</a>
                        <input type="hidden" name="loginUsuario">
                        <input type="submit" value="Entrar">
                    </form>
                </div>
                <div class="modal-footer">
                    <!--Botão pro modal de cadastro-->
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modalCadastroUsuario">
                        Não possui conta? <u>Cadastre-Se</u>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de login de usuário -->

    <!-- Modal de login de lojista -->
    <div class="modal fade" id="modalLoginLojista" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Login lojista</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" id="formLoginLojista" method="POST"
                        onsubmit="RequisicaoPhpLogin('formLoginLojista', event)">
                        <label for="emailL">E-mail</label>
                        <input type="email" name="email" id="emailL">
                        <label for="senhaL">Senha</label>
                        <div>
                            <img class="olho" src="img/icons/olhofechado.png" alt="icone de olho aberto"
                                onclick="mostrarSenha(this)">
                            <input type="password" name="senha" id="senhaL">
                        </div>
                        <span class="aviso"></span>
                        <a href="#">Esqueceu Sua Senha?</a>
                        <input type="hidden" name="loginLojista">
                        <span class="aviso"></span>
                        <input type="submit" value="Entrar">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modalCadastroLojista">
                        Não possui conta? <u>Cadastre-Se</u>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de login de lojista -->

    <!-- Modal de cadastro de usuário -->
    <div class="modal fade" id="modalCadastroUsuario" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Cadastro usuário</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" id="formCadastroUsuario"
                        onsubmit="RequisicaoPhpCadastro(this, event)" enctype="multipart/form-data">
                        <label for="nomeC">Nome</label>
                        <input type="text" name="nome" id="nomeC" class="campoAviso">

                        <label for="imagemUsuario" class="imagemLabel campoAviso">
                            <img src="img/icons/profile.png" alt="Imagem do usuário" id="previewUsuario" class="imagemUsuario">
                            <button type="button" onclick="adicionarFoto(this)">Adicionar uma foto? (opcional)</button>
                        </label>

                        <input type="file" name="imagem" id="imagemUsuario" onchange="PreviewFoto(this, 'previewUsuario')" accept="image/*">

                        <label for="emailC">E-mail</label>
                        <input type="email" name="email" id="emailC" class="campoAviso">

                        <label for="senhaC">Senha</label>
                        <div class="campoAviso">
                            <img class="olho" src="img/icons/olhofechado.png" alt="icone de olho aberto"
                                onclick="mostrarSenha(this)">
                            <input type="password" name="senha" id="senhaC">
                        </div>

                        <label for="enderecoC">Endereço</label>
                        <input type="text" name="endereco" id="enderecoC" class="campoAviso">

                        <label for="dataC">Data de Nascimento</label>
                        <input type="date" name="data_nascimento" id="dataC" class="campoAviso">

                        <input type="hidden" name="cadastroSubmit">
                        <input type="submit" value="Cadastre-se">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de cadastro de usuário -->

    <!-- Modal de cadastro de lojista -->
    <div class="modal fade" id="modalCadastroLojista" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Cadastro lojista</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" id="formCadastroLojista"
                        onsubmit="RequisicaoPhpCadastro(this, event)" enctype="multipart/form-data">

                        <label for="nomeCadastroLojista">Nome</label>
                        <input type="text" name="nome" id="nomeCadastroLojista" class="campoAviso">

                        <label for="nomeEstabelecimento">Nome do seu estabelecimento</label>
                        <input type="text" name="nomeEstabelecimento" id="nomeEstabelecimento" class="campoAviso">

                        <label for="enderecoLojista">Endereço do seu estabelecimento</label>
                        <input type="text" name="endereco" id="enderecoLojista" class="campoAviso">

                        <label for="emailLojista">E-mail</label>
                        <input type="email" name="email" id="emailLojista" class="campoAviso">

                        <label for="telefone">Telefone</label>
                        <input type="text" name="telefone" id="telefone" class="campoAviso" oninput="numeroTelefoneMascara(this)">

                        <label for="imagemLojista" class="campoAviso imagemLabel">
                            <img src="img/icons/profile.png" alt="Imagem do Lojista" id="previewLojista" class="imagemLojista">
                            <button type="button" onclick="adicionarFoto(this)">Adicionar sua foto</button>
                        </label>
                        <input type="file" name="imagemUsuario" id="imagemLojista" onchange="PreviewFoto(this, 'previewLojista')" accept="image/*">

                        <label for="imagemEmpresa" class="imagemLabel campoAviso">
                            <img src="img/icons/profile.png" alt="Imagem do Lojista" id="previewEmpresa" class="imagemEmpresa">
                            <button type="button" onclick="adicionarFoto(this)">Adicionar sua foto</button>
                        </label>
                        <input type="file" name="imagemEmpresa" id="imagemEmpresa" onchange="PreviewFoto(this, 'previewEmpresa')" accept="image/*">


                        <label for="senhaLojistaCadastro">Senha</label>
                        <div class="campoAviso">
                            <img class="olho" src="img/icons/olhofechado.png" alt="icone de olho aberto"
                                onclick="mostrarSenha(this)">
                            <input type="password" name="senha" id="senhaLojistaCadastro">
                        </div>

                        <input type="hidden" name="cadastroLojistaSubmit">
                        <input type="submit" value="Cadastre-se">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de cadastro de lojista -->

    <!----------------------------- Conjunto de modais ----------------------------->


    <main>        
        <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleInterval" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleInterval" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleInterval" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleInterval" data-bs-slide-to="3"
                    aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <button style="border: 0px;" type="button" data-bs-toggle="modal" data-bs-target="#modalCadastroLojista" class="carousel-item active" data-bs-interval="7000">
                    <img src="img/img pg inicial/carrossel.png" class="d-block w-100" alt="...">
                </button>
                <button style="border: 0px;" type="button" data-bs-toggle="modal" data-bs-target="#modalCadastroUsuario" class="carousel-item" data-bs-interval="7000">
                    <img src="img/img pg inicial/carrossel2.png" class="d-block w-100" alt="...">
                </button>
                <button style="border: 0px;" type="button" data-bs-toggle="modal" data-bs-target="#modalCadastroLojista" class="carousel-item" data-bs-interval="7000">
                    <img src="img/img pg inicial/carrossel.png" class="d-block w-100" alt="...">
                </button>
                <button style="border: 0px;" type="button" data-bs-toggle="modal" data-bs-target="#modalCadastroUsuario" class="carousel-item" data-bs-interval="7000">
                    <img src="img/img pg inicial/carrossel2.png" class="d-block w-100" alt="...">
                </button>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </main>

    <!-- Este carrosel foi obitido usando o Chat GPT (que, por sua vez, utlizo recursos do BootStrap), mas foi formatado e estilizado pelos devs do site -->
    <article>
        <div class="subtitulo">
            <h2>Procure Por Categoria</h2>
            <div>
                <button type="button" data-bs-target="#carouselExampleInterval2" data-bs-slide="prev">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#ffffff" height="45px" width="45px" version="1.1" id="Layer_1" viewBox="0 -72 280 460"xml:space="preserve" data-darkreader-inline-fill="" style="--darkreader-inline-fill: var(--darkreader-background-000000, #000000);" stroke="#ffffff" stroke-width="33" transform="matrix(-1, 0, 0, 1, 0, 0)">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                    <g id="SVGRepo_iconCarrier"> <path id="XMLID_222_" d="M250.606,154.389l-150-149.996c-5.857-5.858-15.355-5.858-21.213,0.001 c-5.857,5.858-5.857,15.355,0.001,21.213l139.393,139.39L79.393,304.394c-5.857,5.858-5.857,15.355,0.001,21.213 C82.322,328.536,86.161,330,90,330s7.678-1.464,10.607-4.394l149.999-150.004c2.814-2.813,4.394-6.628,4.394-10.606 C255,161.018,253.42,157.202,250.606,154.389z"/> </g>
                </svg>
                </button>
                <button type="button" data-bs-target="#carouselExampleInterval2" data-bs-slide="next">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#ffffff" height="45px" width="45px" version="1.1" id="Layer_1" viewBox="0 -72 280 460" xml:space="preserve" data-darkreader-inline-fill="" style="--darkreader-inline-fill: var(--darkreader-background-000000, #000000);" stroke="#ffffff" stroke-width="33">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                    <g id="SVGRepo_iconCarrier"> <path id="XMLID_222_" d="M250.606,154.389l-150-149.996c-5.857-5.858-15.355-5.858-21.213,0.001 c-5.857,5.858-5.857,15.355,0.001,21.213l139.393,139.39L79.393,304.394c-5.857,5.858-5.857,15.355,0.001,21.213 C82.322,328.536,86.161,330,90,330s7.678-1.464,10.607-4.394l149.999-150.004c2.814-2.813,4.394-6.628,4.394-10.606 C255,161.018,253.42,157.202,250.606,154.389z"/> </g>
                </svg>
                </button>
            </div>
        </div>
        <div id="carouselExampleInterval2" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner icons-categorias">
                <div class="carousel-item active" data-bs-interval="8000">
                    <div>
                        <a href="produtosBusca.php?categoria=eletrodomesticos">
                            <svg width="170" height="170" viewBox="0 0 175 175" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M138.542 0H36.4585C24.3981 0 14.5835 9.81458 14.5835 21.875V175H160.417V21.875C160.417 9.81458 150.602 0 138.542 0ZM145.833 160.417H29.1668V21.875C29.1668 17.8573 32.4335 14.5833 36.4585 14.5833H138.542C142.567 14.5833 145.833 17.8573 145.833 21.875V160.417ZM47.396 43.75C41.3585 43.75 36.4585 38.85 36.4585 32.8125C36.4585 26.775 41.3585 21.875 47.396 21.875C53.4335 21.875 58.3335 26.775 58.3335 32.8125C58.3335 38.85 53.4335 43.75 47.396 43.75ZM65.6252 32.8125C65.6252 26.775 70.5252 21.875 76.5627 21.875C82.6002 21.875 87.5002 26.775 87.5002 32.8125C87.5002 38.85 82.6002 43.75 76.5627 43.75C70.5252 43.75 65.6252 38.85 65.6252 32.8125ZM87.5002 58.3333C63.372 58.3333 43.7502 77.9552 43.7502 102.083C43.7502 126.211 63.372 145.833 87.5002 145.833C111.628 145.833 131.25 126.211 131.25 102.083C131.25 77.9552 111.628 58.3333 87.5002 58.3333ZM87.5002 131.25C73.945 131.25 62.6283 121.917 59.3689 109.375H80.2085V94.7917H59.3689C62.6283 82.25 73.945 72.9167 87.5002 72.9167C103.586 72.9167 116.667 85.9979 116.667 102.083C116.667 118.169 103.586 131.25 87.5002 131.25Z"
                                    fill="#FFBE00" />
                            </svg>
                            <h4 class='eletrodomesticoText'>Eletrodomésticos</h4>
                        </a>
                        <a href="produtosBusca.php?categoria=Pet">
                            <svg width="183" height="183" viewBox="0 0 183 183" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M62.2505 61C57.8336 60.9309 53.5779 59.3299 50.2106 56.4708C43.1869 50.0293 38.9318 41.1173 38.3385 31.6056C36.4856 12.5965 46.8556 1.38014 58.0567 0.129642C60.7556 -0.169589 63.4871 0.0771922 66.0886 0.855298C68.6902 1.6334 71.1086 2.92693 73.2 4.65889C76.3199 7.47125 78.8531 10.8727 80.6537 14.6676C82.4543 18.4625 83.4869 22.576 83.692 26.7714C85.4 44.4919 77.226 61 62.2505 61ZM59.7495 15.25C55.3498 15.738 52.7268 21.9829 53.5198 30.0883C53.7407 35.5335 55.9868 40.7001 59.8181 44.5758C60.5439 45.2888 61.5084 45.7072 62.525 45.75C65.2013 45.4526 69.5095 38.5749 68.4953 28.2125C65.8876 13.9843 59.9401 15.25 59.7495 15.2881V15.25ZM24.1255 106.75C19.7086 106.681 15.4529 105.08 12.0856 102.221C5.06191 95.7793 0.806783 86.8673 0.213461 77.3557C-1.63941 58.3465 8.73059 47.1301 19.9317 45.8797C22.6305 45.5839 25.3611 45.8324 27.9621 46.6103C30.5632 47.3883 32.9818 48.6799 35.075 50.4089C38.1949 53.2213 40.7281 56.6227 42.5287 60.4176C44.3293 64.2125 45.3619 68.326 45.567 72.5214C47.275 90.2419 38.7121 106.75 24.1255 106.75ZM21.6245 61C17.2248 61.488 14.6018 67.7329 15.3948 75.8383C15.6157 81.2835 17.8618 86.4501 21.6931 90.3258C22.4261 91.0281 23.3863 91.4447 24.4 91.5C27.0763 91.2027 31.3845 84.3249 30.3703 73.9625C28.3497 60.024 21.8151 61 21.6245 61.0381V61ZM120.749 61C107.726 61.0915 97.6152 44.4843 99.3461 26.7485C99.5512 22.5531 100.584 18.4396 102.384 14.6447C104.185 10.8498 106.718 7.44837 109.838 4.63602C111.927 2.91444 114.34 1.62874 116.935 0.854777C119.529 0.080811 122.252 -0.165749 124.943 0.129642C136.144 1.38014 146.514 12.5965 144.661 31.6056C144.07 41.12 139.815 50.0351 132.789 56.4784C129.421 59.3348 125.165 60.933 120.749 61ZM114.527 28.2125C113.513 38.5444 117.821 45.4221 120.498 45.75C121.495 45.7108 122.446 45.319 123.182 44.6444C127.013 40.7687 129.259 35.6021 129.48 30.1569C131.081 10.1718 115.9 11.4756 114.527 28.2125ZM158.874 106.75C144.677 106.75 135.74 90.2343 137.471 72.4985C137.676 68.3031 138.709 64.1897 140.509 60.3947C142.31 56.5998 144.843 53.1984 147.963 50.386C150.049 48.6582 152.461 47.3669 155.056 46.5889C157.651 45.8109 160.375 45.5619 163.068 45.8568C174.269 47.1073 184.639 58.3236 182.786 77.3328C182.195 86.8472 177.94 95.7622 170.914 102.206C167.549 105.07 163.293 106.677 158.874 106.75ZM152.652 73.9625C151.638 84.2944 155.946 91.1721 158.623 91.5C159.617 91.4475 160.564 91.0575 161.307 90.3944C165.138 86.5187 167.384 81.3521 167.605 75.9069C168.368 67.8015 165.775 61.5566 161.375 61.0686C161.185 61 153.377 60.3214 152.652 73.9625ZM122 183C115.482 183.13 109.045 181.536 103.342 178.379C99.6908 176.456 95.6264 175.451 91.5 175.451C87.3735 175.451 83.3092 176.456 79.6583 178.379C56.6842 191.258 29.9052 177.106 30.5 144.875C30.5 109.533 64.8658 76.25 91.5 76.25C118.134 76.25 152.5 109.526 152.5 144.875C152.5 167.323 139.957 183 122 183ZM91.5 160.125C98.0167 159.997 104.452 161.588 110.158 164.738C127.215 174.025 137.517 161.437 137.25 144.875C137.25 119.103 110.402 91.5 91.5 91.5C72.5976 91.5 45.75 119.103 45.75 144.875C45.506 161.414 55.7235 174.025 72.8416 164.738C78.5478 161.588 84.9832 159.997 91.5 160.125Z"  fill="#013989"/>
                                </svg>                                

                            <h4>Pet</h4>
                        </a>
                        <a href="produtosBusca.php?categoria=roupa">
                        <svg xmlns="http://www.w3.org/2000/svg" width="170px" height="170px" viewBox="0 2 20 20" fill="none">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                            <g id="SVGRepo_iconCarrier"> <path d="M3 7L6 4H9C9 4.39397 9.0776 4.78407 9.22836 5.14805C9.37913 5.51203 9.6001 5.84274 9.87868 6.12132C10.1573 6.3999 10.488 6.62087 10.8519 6.77164C11.2159 6.9224 11.606 7 12 7C12.394 7 12.7841 6.9224 13.1481 6.77164C13.512 6.62087 13.8427 6.3999 14.1213 6.12132C14.3999 5.84274 14.6209 5.51203 14.7716 5.14805C14.9224 4.78407 15 4.39397 15 4H18L21 7L20.5 12L18 10.5V20H6V10.5L3.5 12L3 7Z" stroke="#FFBE00" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="round" data-darkreader-inline-stroke="" style="--darkreader-inline-stroke: var(--darkreader-text-ffbe00, #f2ba19);"/> </g>
                        </svg>

                            <h4>Roupas</h4>
                        </a>
                        <a href="produtosBusca.php?categoria=servico">
                            <svg width="175" height="175" viewBox="0 0 175 175" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_374_1394)">
                                <path d="M131.098 94.9662C122.939 86.8287 110.587 85.3557 100.911 90.5401L65.6774 55.3068V32.8047C65.6774 25.4182 61.7035 18.5422 55.3087 14.8526L33.5722 2.31825C26.7837 -1.60467 18.1722 -0.459877 12.616 5.08179L5.142 12.5703C-0.406955 18.1047 -1.53716 26.7235 2.37846 33.512L14.9128 55.2485C18.6024 61.6505 25.4858 65.6245 32.8649 65.6245H55.367L90.5566 100.814C88.5295 104.518 87.4503 108.682 87.4503 113.02C87.4503 119.845 90.1118 126.247 94.9462 131.074L131.507 167.533C136.305 172.316 142.838 174.999 149.59 174.999C156.772 174.999 164.815 171.201 169.409 165.601C177.619 155.596 176.525 140.262 166.922 130.695L131.098 94.9662ZM27.542 47.9714L15.0076 26.2276C14.3805 25.1485 14.5628 23.7703 15.4524 22.8807L22.9264 15.3995C23.816 14.5172 25.1941 14.3276 26.2805 14.9547L48.0097 27.4964C49.9055 28.5901 51.0868 30.6318 51.0868 32.812V51.0412H32.8576C30.6701 51.0412 28.6358 49.8672 27.542 47.9714ZM158.136 156.347C156.123 158.805 153.28 160.241 150.137 160.402C147.067 160.547 144.005 159.388 141.81 157.208L105.249 120.749C103.178 118.686 102.041 115.944 102.041 113.02C102.041 110.096 103.186 107.362 105.249 105.291C107.393 103.155 110.215 102.083 113.03 102.083C115.844 102.083 118.659 103.155 120.802 105.291L156.634 141.013C160.98 145.352 161.636 152.082 158.136 156.347ZM76.1045 124.68C78.9482 127.538 78.941 132.146 76.0899 134.99L43.4451 167.526C38.6472 172.309 32.1212 174.992 25.3618 174.992C18.9378 174.992 10.1368 171.193 5.54305 165.586C-2.66008 155.589 -1.57362 140.255 8.0368 130.688L49.366 89.4537C52.2097 86.6099 56.8326 86.6099 59.6764 89.4682C62.5201 92.3193 62.5128 96.9349 59.6618 99.7787L18.3253 141.013C13.9795 145.352 13.3087 152.082 16.8087 156.34C18.8285 158.805 21.6722 160.241 24.8149 160.402C27.9212 160.54 30.9472 159.388 33.142 157.208L65.7941 124.672C68.6451 121.829 73.2535 121.829 76.1118 124.687L76.1045 124.68ZM72.1378 33.7891C78.8462 16.7047 94.1878 4.08283 112.176 0.852623C118.469 -0.270294 124.791 -0.292169 130.982 0.808873C135.838 1.66929 139.753 5.09637 141.204 9.73387C142.874 15.0495 141.205 20.8974 136.742 25.3745L119.716 41.7224C116.179 45.2589 115.603 50.9172 118.513 54.4755C120.19 56.5245 122.545 57.7203 125.156 57.8589C127.7 57.9828 130.216 57.0422 132.061 55.1974L151.121 36.8151C154.665 33.2714 159.966 31.9516 164.924 33.5339C169.766 35.0578 173.317 39.0901 174.199 44.0412C175.293 50.2245 175.278 56.561 174.156 62.861C172.107 74.3235 166.135 84.9985 157.341 92.9245C155.948 94.1787 154.198 94.7985 152.463 94.7985C150.472 94.7985 148.482 93.9891 147.045 92.3922C144.347 89.4026 144.588 84.787 147.577 82.0891C153.972 76.3287 158.311 68.5849 159.798 60.3016C160.513 56.3057 160.615 52.3172 160.112 48.4016L142.284 65.6026C137.653 70.2474 131.127 72.7776 124.434 72.4276C117.755 72.0922 111.484 68.9203 107.225 63.7141C99.6274 54.4245 100.634 40.1912 109.515 31.3172L126.534 14.9693L126.599 14.9037C122.698 14.4078 118.717 14.5099 114.75 15.2245C101.713 17.5578 90.593 26.7235 85.7149 39.1339C84.2493 42.8818 80.0128 44.7266 76.2649 43.2537C72.517 41.7808 70.6649 37.5516 72.1451 33.8037L72.1378 33.7891Z" fill="#013989"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_374_1394">
                                <rect width="175" height="175" fill="#013989"/>
                                </clipPath>
                                </defs>
                                </svg>
                                
                            <h4>Serviços</h4>
                        </a>
                        <a href="produtosBusca.php?categoria=lanche">
                            <svg width="175" height="175" viewBox="0 0 209 209" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_374_1396)">
                                <path d="M52.4938 209C9.13505 208.817 -9.11762 172.529 4.29321 129.066C20.4036 64.7029 130.756 -37.9858 194.71 14.2903C251.096 83.33 127.002 211.612 52.4938 209ZM156.48 17.4166C99.275 19.5937 36.9146 80.9874 20.9958 133.969C14.7171 155.313 16.7113 172.512 26.604 182.396C85.8206 228.829 228.829 85.8293 182.396 26.6039C178.906 23.4242 174.815 20.9737 170.365 19.3961C165.915 17.8184 161.194 17.1454 156.48 17.4166ZM130.625 121.917C130.893 116.128 129.95 110.346 127.857 104.942C125.763 99.5382 122.565 94.6304 118.467 90.5325C114.37 86.4347 109.462 83.2368 104.058 81.1434C98.6537 79.05 92.8724 78.1071 87.0833 78.3749C84.7738 78.3749 82.5587 79.2924 80.9256 80.9256C79.2925 82.5587 78.375 84.7737 78.375 87.0833C78.375 89.3929 79.2925 91.6079 80.9256 93.241C82.5587 94.8741 84.7738 95.7916 87.0833 95.7916C90.5865 95.5059 94.11 95.9856 97.4093 97.1975C100.709 98.4094 103.705 100.324 106.19 102.81C108.676 105.295 110.591 108.291 111.802 111.591C113.014 114.89 113.494 118.413 113.208 121.917C113.208 124.226 114.126 126.441 115.759 128.074C117.392 129.707 119.607 130.625 121.917 130.625C124.226 130.625 126.441 129.707 128.074 128.074C129.708 126.441 130.625 124.226 130.625 121.917ZM165.458 87.0833C165.726 81.2942 164.783 75.5129 162.69 70.1089C160.596 64.7049 157.399 59.7971 153.301 55.6992C149.203 51.6013 144.295 48.4035 138.891 46.3101C133.487 44.2167 127.706 43.2738 121.917 43.5416C119.607 43.5416 117.392 44.4591 115.759 46.0922C114.126 47.7253 113.208 49.9403 113.208 52.2499C113.208 54.5595 114.126 56.7745 115.759 58.4077C117.392 60.0408 119.607 60.9583 121.917 60.9583C125.42 60.6726 128.943 61.1523 132.243 62.3642C135.542 63.576 138.538 65.4911 141.024 67.9764C143.509 70.4618 145.424 73.458 146.636 76.7573C147.848 80.0566 148.327 83.5801 148.042 87.0833C148.042 89.3929 148.959 91.6079 150.592 93.241C152.225 94.8741 154.44 95.7916 156.75 95.7916C159.06 95.7916 161.275 94.8741 162.908 93.241C164.541 91.6079 165.458 89.3929 165.458 87.0833ZM95.7917 156.75C96.0595 150.961 95.1166 145.18 93.0232 139.776C90.9298 134.372 87.732 129.464 83.6341 125.366C79.5362 121.268 74.6284 118.07 69.2244 115.977C63.8204 113.883 58.0391 112.94 52.25 113.208C49.9404 113.208 47.7254 114.126 46.0923 115.759C44.4592 117.392 43.5417 119.607 43.5417 121.917C43.5417 124.226 44.4592 126.441 46.0923 128.074C47.7254 129.707 49.9404 130.625 52.25 130.625C55.7532 130.339 59.2766 130.819 62.576 132.031C65.8753 133.243 68.8715 135.158 71.3568 137.643C73.8422 140.128 75.7572 143.125 76.9691 146.424C78.181 149.723 78.6607 153.247 78.375 156.75C78.375 159.06 79.2925 161.275 80.9256 162.908C82.5587 164.541 84.7738 165.458 87.0833 165.458C89.3929 165.458 91.6079 164.541 93.2411 162.908C94.8742 161.275 95.7917 159.06 95.7917 156.75Z" fill="#FFBE00"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_374_1396">
                                <rect width="209" height="209" fill="white"/>
                                </clipPath>
                                </defs>
                                </svg>
                                
                            <h4>Lanches</h4>
                        </a>
                        <a href="produtosBusca.php?categoria=eletronico">
                            <svg width="175" height="175" viewBox="0 0 100 100" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_374_1415)">
                                    <path
                                        d="M62.5988 0.481689H37.8396C32.3695 0.488242 27.1254 2.66413 23.2574 6.53208C19.3895 10.4 17.2136 15.6442 17.207 21.1143V78.8856C17.2136 84.3557 19.3895 89.5999 23.2574 93.4678C27.1254 97.3358 32.3695 99.5117 37.8396 99.5182H62.5988C68.0689 99.5117 73.313 97.3358 77.181 93.4678C81.0489 89.5999 83.2248 84.3557 83.2314 78.8856V21.1143C83.2248 15.6442 81.0489 10.4 77.181 6.53208C73.313 2.66413 68.0689 0.488242 62.5988 0.481689ZM37.8396 8.73473H62.5988C65.882 8.73473 69.0308 10.039 71.3524 12.3606C73.6741 14.6822 74.9783 17.831 74.9783 21.1143V66.506H25.4601V21.1143C25.4601 17.831 26.7643 14.6822 29.086 12.3606C31.4076 10.039 34.5564 8.73473 37.8396 8.73473ZM62.5988 91.2652H37.8396C34.5564 91.2652 31.4076 89.9609 29.086 87.6393C26.7643 85.3177 25.4601 82.1689 25.4601 78.8856V74.7591H74.9783V78.8856C74.9783 82.1689 73.6741 85.3177 71.3524 87.6393C69.0308 89.9609 65.882 91.2652 62.5988 91.2652Z"
                                        fill="#013989" />
                                    <path
                                        d="M50.2193 87.1385C52.4983 87.1385 54.3458 85.291 54.3458 83.012C54.3458 80.733 52.4983 78.8855 50.2193 78.8855C47.9403 78.8855 46.0928 80.733 46.0928 83.012C46.0928 85.291 47.9403 87.1385 50.2193 87.1385Z"
                                        fill="#013989" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_374_1415">
                                        <rect width="99.0365" height="99.0365" fill="white"
                                            transform="translate(0.701172 0.481689)" />
                                    </clipPath>
                                </defs>
                            </svg>
                            <h4>Eletrônicos</h4>
                        </a>
                    </div>
                </div>
                <div class="carousel-item" data-bs-interval="8000">
                    <div>
                        <a href="produtosBusca.php?categoria=brinquedo">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Icons" xml:space="preserve" width="195" height="215" viewBox="0 4 32 24" fill="#FFBE00">

                            <g id="SVGRepo_bgCarrier" stroke-width="0"/>

                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>

                            <g id="SVGRepo_iconCarrier"> <style type="text/css"> .st0{fill:none;stroke:#FFBE00;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;} .st1{fill:none;stroke:#FFBE00;stroke-width:2;stroke-linejoin:round;stroke-miterlimit:10;} </style> <ellipse class="st0" cx="16" cy="12.9" rx="8" ry="8.1"/> <path class="st0" d="M8,12c-1.7-0.4-3-2-3-4C5,5.8,6.8,4,9,4c1.3,0,2.5,0.7,3.2,1.7"/> <path class="st0" d="M19.8,5.7C20.5,4.7,21.7,4,23,4c2.2,0,4,1.8,4,4.1c0,1.9-1.3,3.5-3,4"/> <line class="st0" x1="14" y1="12" x2="14" y2="13"/> <line class="st0" x1="18" y1="12" x2="18" y2="13"/> <line class="st0" x1="16" y1="14" x2="16" y2="16"/> <ellipse transform="matrix(0.7087 -0.7055 0.7055 0.7087 -14.8111 13.1311)" class="st0" cx="8.5" cy="24.5" rx="2.8" ry="4.1"/> <ellipse transform="matrix(0.7055 -0.7087 0.7087 0.7055 -10.441 23.8715)" class="st0" cx="23.5" cy="24.5" rx="4.1" ry="2.8"/> <path class="st0" d="M9,21.4c0-0.1,0-0.3,0-0.4c0-1.1,0.3-2.2,0.7-3.1"/> <path class="st0" d="M20.2,26.6C19,27.5,17.6,28,16,28c-1.6,0-3-0.5-4.2-1.4"/> <path class="st0" d="M22.3,17.9c0.5,0.9,0.7,2,0.7,3.1c0,0.1,0,0.3,0,0.4"/> <path class="st0" d="M6.2,21.3C6.1,20.9,6,20.4,6,20c0-2,1.1-3.7,2.6-4"/> <path class="st0" d="M23.4,16c1.5,0.2,2.6,1.9,2.6,4c0,0.4-0.1,0.9-0.2,1.3"/> <path class="st0" d="M10,18c0-2.2,2.7-4,6-4s6,1.8,6,4"/> </g>
                            </svg>
                            <h4>Brinquedos</h4>
                        </a>
                        <a href="produtosBusca.php?categoria=esporte">
                        <svg xmlns="http://www.w3.org/2000/svg"  width="195" height="215" viewBox="0 0 48 48" fill="#013989">

                            <g id="SVGRepo_bgCarrier" stroke-width="0"/>

                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>

                            <g id="SVGRepo_iconCarrier"> <title>sports-volleyball</title> <g id="Layer_2" data-name="Layer 2"> <g id="invisible_box" data-name="invisible box"> <rect width="48" height="48" fill="none"/> </g> <g id="Q3_icons" data-name="Q3 icons"> <path d="M35,4.9A22.2,22.2,0,0,0,4.9,13,22.2,22.2,0,0,0,13,43.1,22.2,22.2,0,0,0,43.1,35,22.2,22.2,0,0,0,35,4.9Zm3.1,7.9c-.7,7.6-5.8,15.7-10,18.3a14.6,14.6,0,0,1-2.6-6.3c4.4-3.1,7.4-9.8,7.8-16.2A19.6,19.6,0,0,1,38.1,12.8ZM15.8,24a14.2,14.2,0,0,1,6.7.9c.5,5.4,4.9,11.3,10.2,14.9a18.5,18.5,0,0,1-6,2C20.4,37.4,16,28.9,15.8,24ZM7.3,17.4c6.9-3.2,16.5-2.8,20.8-.5a14.5,14.5,0,0,1-4.2,5.4C19.1,20,11.7,20.8,6,23.6A19.1,19.1,0,0,1,7.3,17.4ZM24,6a17.7,17.7,0,0,1,6.4,1.2,21.3,21.3,0,0,1-1.2,6.9c-4.7-2.4-12.8-2.8-19.7-.7A18,18,0,0,1,24,6ZM6.2,26.9a25,25,0,0,1,6.6-2.5c.3,5.3,4,12.5,9.3,17.5A19.7,19.7,0,0,1,15,39.6,18.2,18.2,0,0,1,6.2,26.9ZM39.6,33a18.2,18.2,0,0,1-4.2,4.9A20.5,20.5,0,0,1,30,33.5c4.4-2.9,8.8-9.7,10.5-16.7A17.9,17.9,0,0,1,39.6,33Z"/> </g> </g> </g>
                        </svg>                             

                        <h4>Esportes</h4>
                        </a>
                        <a href="produtosBusca.php?categoria=estetica">
                            <svg width="210" height="209" viewBox="0 0 210 209" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_374_1402)">
                                <path d="M103.502 143.357C91.7414 127.293 81.0227 110.492 71.4122 93.0572C84.6663 67.1325 96.2919 36.4792 96.2919 8.70833C96.2919 6.39874 95.3745 4.18374 93.7413 2.55061C92.1082 0.917483 89.8932 0 87.5836 0C85.274 0 83.059 0.917483 81.4259 2.55061C79.7928 4.18374 78.8753 6.39874 78.8753 8.70833C77.6367 31.2506 71.7202 53.2884 61.5021 73.42C51.2565 53.294 45.3104 31.2565 44.0419 8.70833C44.0419 6.39874 43.1245 4.18374 41.4913 2.55061C39.8582 0.917483 37.6432 0 35.3336 0C33.024 0 30.809 0.917483 29.1759 2.55061C27.5427 4.18374 26.6253 6.39874 26.6253 8.70833C26.6253 36.3486 38.3119 67.0542 51.6008 93.0485C42.0242 110.526 31.2738 127.334 19.4235 143.357C14.857 145.683 10.8623 148.992 7.72638 153.046C4.59044 157.099 2.39102 161.797 1.28607 166.801C0.181122 171.806 0.198061 176.993 1.33567 181.99C2.47328 186.987 4.70333 191.671 7.86569 195.704C11.028 199.737 15.0442 203.019 19.6258 205.316C24.2074 207.613 29.2407 208.866 34.3643 208.987C39.4879 209.108 44.5746 208.092 49.2592 206.014C53.9438 203.935 58.11 200.845 61.4586 196.965C64.8093 200.835 68.9746 203.915 73.6559 205.986C78.3372 208.057 83.4186 209.066 88.5359 208.942C93.6531 208.817 98.6796 207.563 103.255 205.267C107.83 202.972 111.841 199.693 115 195.665C118.159 191.637 120.388 186.96 121.526 181.97C122.665 176.979 122.686 171.799 121.587 166.799C120.488 161.8 118.297 157.105 115.17 153.052C112.044 149 108.059 145.688 103.502 143.357ZM35.3336 191.583C31.8889 191.583 28.5216 190.562 25.6574 188.648C22.7933 186.734 20.5609 184.014 19.2427 180.832C17.9245 177.649 17.5796 174.147 18.2516 170.769C18.9236 167.39 20.5824 164.287 23.0182 161.851C25.4539 159.415 28.5573 157.757 31.9358 157.085C35.3143 156.413 38.8162 156.758 41.9987 158.076C45.1812 159.394 47.9013 161.626 49.815 164.49C51.7288 167.355 52.7503 170.722 52.7503 174.167C52.7503 178.786 50.9153 183.216 47.649 186.482C44.3828 189.748 39.9528 191.583 35.3336 191.583ZM43.0231 140.248C48.1174 132.898 54.6661 122.909 61.5021 111.144C68.3295 122.901 74.8607 132.915 79.9377 140.239C72.7519 141.891 66.2792 145.789 61.4586 151.368C56.6462 145.803 50.1909 141.909 43.0231 140.248ZM87.5836 191.583C84.1389 191.583 80.7716 190.562 77.9074 188.648C75.0433 186.734 72.8109 184.014 71.4927 180.832C70.1745 177.649 69.8296 174.147 70.5016 170.769C71.1736 167.39 72.8324 164.287 75.2682 161.851C77.7039 159.415 80.8073 157.757 84.1858 157.085C87.5643 156.413 91.0662 156.758 94.2487 158.076C97.4312 159.394 100.151 161.626 102.065 164.49C103.979 167.355 105 170.722 105 174.167C105 178.786 103.165 183.216 99.899 186.482C96.6328 189.748 92.2028 191.583 87.5836 191.583ZM157.25 52.25V69.6667H200.792C203.102 69.6667 205.317 70.5841 206.95 72.2173C208.583 73.8504 209.5 76.0654 209.5 78.375C209.5 80.6846 208.583 82.8996 206.95 84.5327C205.317 86.1658 203.102 87.0833 200.792 87.0833H157.25V104.5H200.792C203.102 104.5 205.317 105.417 206.95 107.051C208.583 108.684 209.5 110.899 209.5 113.208C209.5 115.518 208.583 117.733 206.95 119.366C205.317 120.999 203.102 121.917 200.792 121.917H157.25V139.333H200.792C203.102 139.333 205.317 140.251 206.95 141.884C208.583 143.517 209.5 145.732 209.5 148.042C209.5 150.351 208.583 152.566 206.95 154.199C205.317 155.833 203.102 156.75 200.792 156.75H157.25V200.292C157.25 202.601 156.333 204.816 154.7 206.449C153.067 208.083 150.852 209 148.542 209C146.232 209 144.017 208.083 142.384 206.449C140.751 204.816 139.834 202.601 139.834 200.292V43.5417C139.847 31.9979 144.439 20.931 152.602 12.7683C160.765 4.60568 171.832 0.0138276 183.375 0L200.792 0C203.102 0 205.317 0.917483 206.95 2.55061C208.583 4.18374 209.5 6.39874 209.5 8.70833C209.5 11.0179 208.583 13.2329 206.95 14.8661C205.317 16.4992 203.102 17.4167 200.792 17.4167H183.375C177.991 17.439 172.746 19.1242 168.356 22.2418C163.966 25.3595 160.647 29.7572 158.853 34.8333H200.792C203.102 34.8333 205.317 35.7508 206.95 37.3839C208.583 39.0171 209.5 41.2321 209.5 43.5417C209.5 45.8513 208.583 48.0663 206.95 49.6994C205.317 51.3325 203.102 52.25 200.792 52.25H157.25Z" fill="#FFBE00"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_374_1402">
                                <rect width="209" height="209" fill="white" transform="translate(0.5)"/>
                                </clipPath>
                                </defs>
                                </svg>
                                

                            <h4>Estética</h4>
                        </a>
                        <a href="produtosBusca.php?categoria=doce">
                        <svg xmlns="http://www.w3.org/2000/svg" width="210" height="209" viewBox="-2.87 0 45.211 45.211" fill="#013989">

                        <g id="SVGRepo_bgCarrier" stroke-width="0"/>

                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#013989" stroke-width="0.27126599999999995"/>

                        <g id="SVGRepo_iconCarrier"> <g id="Group_4" data-name="Group 4" transform="translate(-493.237 -119.985)"> <path id="Path_10" data-name="Path 10" d="M526.838,164.113H499.1a.878.878,0,0,1-.855-.675l-3.976-16.7a.879.879,0,0,1,.855-1.083h35.692a.879.879,0,0,1,.855,1.083l-3.976,16.7A.879.879,0,0,1,526.838,164.113Z" fill="none" stroke="#013989" stroke-linecap="round" stroke-linejoin="round" stroke-width="3.2551919999999996"/> <line id="Line_3" data-name="Line 3" x2="1.604" y2="18.458" transform="translate(503.161 145.655)" fill="none" stroke="#013989" stroke-linecap="round" stroke-linejoin="round" stroke-width="3.2551919999999996"/> <line id="Line_4" data-name="Line 4" x1="1.604" y2="18.458" transform="translate(520.503 145.655)" fill="none" stroke="#013989" stroke-linecap="round" stroke-linejoin="round" stroke-width="3.2551919999999996"/> <line id="Line_5" data-name="Line 5" y2="17" transform="translate(512.91 146.36)" fill="none" stroke="#013989" stroke-linecap="round" stroke-linejoin="round" stroke-width="3.2551919999999996"/> <path id="Path_11" data-name="Path 11" d="M530.814,145.655s2.971-4.295-1.917-7.588c0,0-2,3.494-13.355,7.588" fill="none" stroke="#013989" stroke-linecap="round" stroke-linejoin="round" stroke-width="3.2551919999999996"/> <path id="Path_12" data-name="Path 12" d="M499.91,145.655a74.452,74.452,0,0,0,18.646-15.639c4.77-5.781,10.341,8.051,10.341,8.051" fill="none" stroke="#013989" stroke-linecap="round" stroke-linejoin="round" stroke-width="3.2551919999999996"/> <path id="Path_13" data-name="Path 13" d="M496.033,145.007s-4.748-6.314,2.877-11.314,12.935-6.583,10.467-12.708c0,0,11.928,3.75,9.179,9.031" fill="none" stroke="#013989" stroke-linecap="round" stroke-linejoin="round" stroke-width="3.2551919999999996"/> </g> </g>

                        </svg>
                                
                            <h4>Doces</h4>
                        </a>
                        <a href="produtosBusca.php?categoria=movel">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#FFBE00" version="1.1" id="Layer_1" viewBox="0 0 490 490" xml:space="preserve" width="210" height="209" stroke="#FFBE00" stroke-width="6.860000000000001">

                        <g id="SVGRepo_bgCarrier" stroke-width="0"/>

                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>

                        <g id="SVGRepo_iconCarrier"> <g> <g> <g> <path d="M480,220H300V10c0-5.522-4.477-10-10-10H10C4.477,0,0,4.478,0,10v470c0,5.522,4.477,10,10,10h265c5.523,0,10-4.478,10-10 v-30h120v30c0,5.522,4.477,10,10,10h35c5.523,0,10-4.478,10-10v-30h5c5.523,0,10-4.478,10-10V275h5c5.523,0,10-4.478,10-10v-35 C490,224.478,485.523,220,480,220z M140,470H20v-85h120V470z M140,365H20V20h120V365z M230,470h-70V20h120v200h-75 c-5.523,0-10,4.478-10,10v35c0,5.522,4.477,10,10,10h10v165c0,5.522,4.477,10,10,10h5V470z M265,470h-15v-20h15V470z M440,470 h-15v-20h15V470z M455,430H235v-70h220V430z M455,340H235v-65h220V340z M470,255H215v-15h255V255z"/> <rect x="330" y="295" width="30" height="20"/> <rect x="330" y="380" width="30" height="20"/> <rect x="180" y="160" width="20" height="40"/> <rect x="100" y="160" width="20" height="40"/> <rect x="65" y="415" width="30" height="20"/> </g> </g> </g> </g>

                        </svg>
                                
                            <h4>Móveis</h4>
                        </a>
                        <a href="produtosBusca.php?categoria=papelaria">
                        <svg xmlns="http://www.w3.org/2000/svg" width="210" height="209" viewBox="0 0 24 24" fill="none">

                            <g id="SVGRepo_bgCarrier" stroke-width="0"/>

                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>

                            <g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M15.7598 1.00009C16.4369 0.995994 17.1077 1.12795 17.7334 1.388C18.359 1.64804 18.9266 2.03082 19.4037 2.51356C19.8807 2.99627 20.2576 3.56933 20.5133 4.19921C20.7689 4.82907 20.8984 5.50361 20.8943 6.18382C20.8903 6.86404 20.7529 7.53696 20.4898 8.16367C20.2275 8.78856 19.8452 9.35548 19.3644 9.83144L11.3058 17.9752L11.3004 17.9805C10.7202 18.5495 9.99625 18.9706 9.15388 18.9874C8.29904 19.0044 7.53661 18.6013 6.89877 17.9616C6.14878 17.2094 5.94279 16.3064 6.05131 15.4997C6.15401 14.7363 6.53228 14.0692 6.94729 13.6357L6.95275 13.63L13.1011 7.40812C13.4893 7.01528 14.1224 7.01152 14.5153 7.39971C14.9081 7.78791 14.9119 8.42106 14.5237 8.8139L8.38743 15.0236C8.2318 15.1883 8.07295 15.4728 8.03346 15.7663C7.99942 16.0194 8.04811 16.2817 8.31503 16.5494C8.69411 16.9296 8.96044 16.9908 9.11409 16.9878C9.27944 16.9845 9.54207 16.9019 9.89517 16.5574L17.9547 8.41268C18.2491 8.1219 18.4842 7.77426 18.6457 7.38949C18.8073 7.00469 18.8919 6.59084 18.8944 6.172C18.8968 5.75316 18.8171 5.33823 18.6601 4.95138C18.5031 4.56454 18.2721 4.21386 17.9811 3.91934C17.69 3.62485 17.3448 3.39238 16.9658 3.23482C16.5867 3.07727 16.181 2.99758 15.7719 3.00006C15.3629 3.00253 14.9582 3.08713 14.5811 3.24926C14.204 3.41139 13.8616 3.64802 13.5742 3.946L13.5658 3.95473L5.45484 12.1626L5.44968 12.1677C4.99589 12.6138 4.63362 13.1474 4.38454 13.7379C4.13544 14.3283 4.00466 14.9635 4.00012 15.6062C3.99558 16.249 4.11737 16.8861 4.35813 17.4803C4.58381 18.0372 5.12588 18.786 5.60643 19.2723C6.10021 19.772 6.94793 20.4178 7.48314 20.6399C8.06705 20.8822 8.69228 21.0044 9.32258 20.9999C9.95289 20.9953 10.5763 20.864 11.1566 20.6133C11.737 20.3626 12.2631 19.9972 12.704 19.5379L12.709 19.5327L20.2887 11.8623C20.6769 11.4695 21.31 11.4657 21.7029 11.8539C22.0957 12.2421 22.0995 12.8753 21.7113 13.2681L14.1416 20.9284C13.5182 21.5763 12.7734 22.0935 11.9498 22.4493C11.124 22.8061 10.2358 22.9933 9.33706 22.9998C8.43832 23.0063 7.54753 22.832 6.7166 22.4872C5.83696 22.1221 4.77137 21.2726 4.18383 20.678C3.58306 20.0701 2.85902 19.1062 2.50453 18.2313C2.16513 17.3937 1.99378 16.4967 2.00017 15.5921C2.00656 14.6876 2.19057 13.793 2.54181 12.9605C2.89207 12.1302 3.40184 11.3777 4.0422 10.7468L12.1391 2.55297C12.6093 2.06665 13.1707 1.67862 13.7912 1.41187C14.4136 1.14426 15.0828 1.00419 15.7598 1.00009Z" fill="#013989"/> </g>

                        </svg>
                            <h4>Papelaria</h4>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </article>

    <article class="lojasRecomendadas">
        <div class="subtitulo">
            <h2>Lojas Recomendadas</h2>
        </div>
        <div class="containerImagemTexto">
            <div>
                <a href="http://localhost/gabryel/projetoMercado/lojistaUsuario.php?id=16">
                    <img src="img/img pg inicial/fachada-1.webp" alt="">
                </a>
                <h2>Joca Artigos</h2>
                <div class="distancia">
                    <svg width="30" height="100%" viewBox="0 0 30 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.9997 8.5C13.8789 8.5 12.7833 8.83234 11.8514 9.45501C10.9196 10.0777 10.1933 10.9627 9.76436 11.9981C9.33546 13.0336 9.22324 14.173 9.44189 15.2722C9.66054 16.3714 10.2002 17.3811 10.9927 18.1736C11.7852 18.9661 12.7949 19.5058 13.8942 19.7245C14.9934 19.9431 16.1328 19.8309 17.1682 19.402C18.2037 18.9731 19.0887 18.2468 19.7113 17.3149C20.334 16.383 20.6663 15.2874 20.6663 14.1667C20.6663 12.6638 20.0693 11.2224 19.0066 10.1597C17.9439 9.09702 16.5026 8.5 14.9997 8.5ZM14.9997 17C14.4393 17 13.8915 16.8338 13.4256 16.5225C12.9596 16.2112 12.5965 15.7687 12.382 15.2509C12.1676 14.7332 12.1115 14.1635 12.2208 13.6139C12.3301 13.0643 12.6 12.5594 12.9962 12.1632C13.3925 11.7669 13.8973 11.4971 14.4469 11.3878C14.9965 11.2785 15.5662 11.3346 16.0839 11.549C16.6017 11.7635 17.0442 12.1266 17.3555 12.5926C17.6668 13.0585 17.833 13.6063 17.833 14.1667C17.833 14.9181 17.5345 15.6388 17.0031 16.1701C16.4718 16.7015 15.7511 17 14.9997 17Z" fill="#013989"/>
                        <path d="M15.0004 33.9999C13.8075 34.006 12.6305 33.7262 11.568 33.184C10.5054 32.6417 9.5882 31.8528 8.89319 30.8832C3.49428 23.4358 0.755859 17.8372 0.755859 14.2417C0.755859 10.4638 2.25662 6.84059 4.928 4.16921C7.59938 1.49783 11.2225 -0.00292969 15.0004 -0.00292969C18.7783 -0.00292969 22.4015 1.49783 25.0729 4.16921C27.7443 6.84059 29.245 10.4638 29.245 14.2417C29.245 17.8372 26.5066 23.4358 21.1077 30.8832C20.4127 31.8528 19.4955 32.6417 18.4329 33.184C17.3704 33.7262 16.1934 34.006 15.0004 33.9999ZM15.0004 3.08965C12.043 3.09303 9.20772 4.26935 7.11651 6.36056C5.02531 8.45176 3.84898 11.2871 3.84561 14.2445C3.84561 17.092 6.52736 22.3577 11.395 29.0713C11.8083 29.6405 12.3504 30.1038 12.977 30.4232C13.6037 30.7426 14.2971 30.9092 15.0004 30.9092C15.7038 30.9092 16.3972 30.7426 17.0239 30.4232C17.6505 30.1038 18.1926 29.6405 18.6059 29.0713C23.4735 22.3577 26.1553 17.092 26.1553 14.2445C26.1519 11.2871 24.9756 8.45176 22.8844 6.36056C20.7932 4.26935 17.9579 3.09303 15.0004 3.08965Z" fill="#013989"/>
                    </svg>
                    <p>35m de você</p>
                </div>
            </div>
            <div>
                <a href="http://localhost/gabryel/projetoMercado/lojistaUsuario.php?id=14">
                    <img src="img/img pg inicial/juntas.PNG" alt="">
                </a>
                <h2>Juntas Por Eles</h2>
                <div class="distancia">
                    <svg width="30" height="100%" viewBox="0 0 30 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.9997 8.5C13.8789 8.5 12.7833 8.83234 11.8514 9.45501C10.9196 10.0777 10.1933 10.9627 9.76436 11.9981C9.33546 13.0336 9.22324 14.173 9.44189 15.2722C9.66054 16.3714 10.2002 17.3811 10.9927 18.1736C11.7852 18.9661 12.7949 19.5058 13.8942 19.7245C14.9934 19.9431 16.1328 19.8309 17.1682 19.402C18.2037 18.9731 19.0887 18.2468 19.7113 17.3149C20.334 16.383 20.6663 15.2874 20.6663 14.1667C20.6663 12.6638 20.0693 11.2224 19.0066 10.1597C17.9439 9.09702 16.5026 8.5 14.9997 8.5ZM14.9997 17C14.4393 17 13.8915 16.8338 13.4256 16.5225C12.9596 16.2112 12.5965 15.7687 12.382 15.2509C12.1676 14.7332 12.1115 14.1635 12.2208 13.6139C12.3301 13.0643 12.6 12.5594 12.9962 12.1632C13.3925 11.7669 13.8973 11.4971 14.4469 11.3878C14.9965 11.2785 15.5662 11.3346 16.0839 11.549C16.6017 11.7635 17.0442 12.1266 17.3555 12.5926C17.6668 13.0585 17.833 13.6063 17.833 14.1667C17.833 14.9181 17.5345 15.6388 17.0031 16.1701C16.4718 16.7015 15.7511 17 14.9997 17Z" fill="#013989"/>
                        <path d="M15.0004 33.9999C13.8075 34.006 12.6305 33.7262 11.568 33.184C10.5054 32.6417 9.5882 31.8528 8.89319 30.8832C3.49428 23.4358 0.755859 17.8372 0.755859 14.2417C0.755859 10.4638 2.25662 6.84059 4.928 4.16921C7.59938 1.49783 11.2225 -0.00292969 15.0004 -0.00292969C18.7783 -0.00292969 22.4015 1.49783 25.0729 4.16921C27.7443 6.84059 29.245 10.4638 29.245 14.2417C29.245 17.8372 26.5066 23.4358 21.1077 30.8832C20.4127 31.8528 19.4955 32.6417 18.4329 33.184C17.3704 33.7262 16.1934 34.006 15.0004 33.9999ZM15.0004 3.08965C12.043 3.09303 9.20772 4.26935 7.11651 6.36056C5.02531 8.45176 3.84898 11.2871 3.84561 14.2445C3.84561 17.092 6.52736 22.3577 11.395 29.0713C11.8083 29.6405 12.3504 30.1038 12.977 30.4232C13.6037 30.7426 14.2971 30.9092 15.0004 30.9092C15.7038 30.9092 16.3972 30.7426 17.0239 30.4232C17.6505 30.1038 18.1926 29.6405 18.6059 29.0713C23.4735 22.3577 26.1553 17.092 26.1553 14.2445C26.1519 11.2871 24.9756 8.45176 22.8844 6.36056C20.7932 4.26935 17.9579 3.09303 15.0004 3.08965Z" fill="#013989"/>
                    </svg>
                    <p>110m de você</p>
                </div>
            </div>
            <div>
                <a href="http://localhost/gabryel/projetoMercado/lojistaUsuario.php?id=15">
                    <img src="img/img pg inicial/28c7248d60b526968a1c2b80f7fe1ee5.jpg" alt="Bazar LTDA">
                </a>
                <h2>Bazar LTDA</h2>
                <div class="distancia">
                    <svg width="30" height="100%" viewBox="0 0 30 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.9997 8.5C13.8789 8.5 12.7833 8.83234 11.8514 9.45501C10.9196 10.0777 10.1933 10.9627 9.76436 11.9981C9.33546 13.0336 9.22324 14.173 9.44189 15.2722C9.66054 16.3714 10.2002 17.3811 10.9927 18.1736C11.7852 18.9661 12.7949 19.5058 13.8942 19.7245C14.9934 19.9431 16.1328 19.8309 17.1682 19.402C18.2037 18.9731 19.0887 18.2468 19.7113 17.3149C20.334 16.383 20.6663 15.2874 20.6663 14.1667C20.6663 12.6638 20.0693 11.2224 19.0066 10.1597C17.9439 9.09702 16.5026 8.5 14.9997 8.5ZM14.9997 17C14.4393 17 13.8915 16.8338 13.4256 16.5225C12.9596 16.2112 12.5965 15.7687 12.382 15.2509C12.1676 14.7332 12.1115 14.1635 12.2208 13.6139C12.3301 13.0643 12.6 12.5594 12.9962 12.1632C13.3925 11.7669 13.8973 11.4971 14.4469 11.3878C14.9965 11.2785 15.5662 11.3346 16.0839 11.549C16.6017 11.7635 17.0442 12.1266 17.3555 12.5926C17.6668 13.0585 17.833 13.6063 17.833 14.1667C17.833 14.9181 17.5345 15.6388 17.0031 16.1701C16.4718 16.7015 15.7511 17 14.9997 17Z" fill="#013989"/>
                        <path d="M15.0004 33.9999C13.8075 34.006 12.6305 33.7262 11.568 33.184C10.5054 32.6417 9.5882 31.8528 8.89319 30.8832C3.49428 23.4358 0.755859 17.8372 0.755859 14.2417C0.755859 10.4638 2.25662 6.84059 4.928 4.16921C7.59938 1.49783 11.2225 -0.00292969 15.0004 -0.00292969C18.7783 -0.00292969 22.4015 1.49783 25.0729 4.16921C27.7443 6.84059 29.245 10.4638 29.245 14.2417C29.245 17.8372 26.5066 23.4358 21.1077 30.8832C20.4127 31.8528 19.4955 32.6417 18.4329 33.184C17.3704 33.7262 16.1934 34.006 15.0004 33.9999ZM15.0004 3.08965C12.043 3.09303 9.20772 4.26935 7.11651 6.36056C5.02531 8.45176 3.84898 11.2871 3.84561 14.2445C3.84561 17.092 6.52736 22.3577 11.395 29.0713C11.8083 29.6405 12.3504 30.1038 12.977 30.4232C13.6037 30.7426 14.2971 30.9092 15.0004 30.9092C15.7038 30.9092 16.3972 30.7426 17.0239 30.4232C17.6505 30.1038 18.1926 29.6405 18.6059 29.0713C23.4735 22.3577 26.1553 17.092 26.1553 14.2445C26.1519 11.2871 24.9756 8.45176 22.8844 6.36056C20.7932 4.26935 17.9579 3.09303 15.0004 3.08965Z" fill="#013989"/>
                    </svg>
                    <p>75m de você</p>
                </div>
            </div>
        </div>
    </article>

        <!-- Cadastre-se com imagem de fundo -->
        <?php
        /*Isso aqui diz que: se o usuário estiver logado, vai escrever 'User Logado' (na versão final não vai escrever nada), do contrário, vai ter a estrutura do cadastroMaisImg.
        if (isset($_SESSION['idUser'])) {

        } else {
            echo '<div class="cadastroMaisimg" style="padding-left: 7%">
                        <p>Quer receber os melhores descontos da lojinha da esquina?</p>
                        <button id="btn-p2" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Cadastre-se</button>
                        </div>';
        }*/
        ?> 

    <article class="banner">
    <img class="bannerDesconto" width="100%" height="100%" viewBox="0 0 100% 100%" fill="none"
    src="img/img pg inicial/image 24.png" alt="Bazar LTDA">       
    </article>


    <article class="produtosBuscados">
        <div class="subtitulo">
            <h2>Produtos Mais Buscados</h2>
            <a href="">Ver todos</a>
        </div>
        <div class="containerCards produtosMaisBuscados">

            <?php

            include_once "restrito/conexao.php";
            include_once "scripts.php";

            $sql = "SELECT p.id, p.contador_cliques, p.nome, p.preco, p.imagem, l.nome_estabelecimento
            FROM produtos AS p JOIN lojistas as l ON p.id_lojista = l.id ORDER BY contador_cliques DESC LIMIT 8;";
            gerarCard($sql, 'usuario')



            ?>
            <iframe name="hiddenFrame" style="display:none;"></iframe> <!-- Iframe invisível -->
        </div>
    </article>

    <footer>
        <div class="footerSuperior">
        </div>

        <div class="footerInferior">
            <div class="linksPaginasFooter">
                <div>
                    <h5>Comprar</h5>
                    <a href="produtosBusca.php">Todos Produtos</a>
                    <a href="guiaDoLojista.php">Anuncie Aqui</a>
                </div>

                <div>
                    <h5>Ajuda</h5>
                    <a href="Contato.php">Contate-nos</a>
                    <a href="guiaDoLojista.php">Guia do Lojista</a>
                </div>


                <div>
                    <h5>Nossas redes sociais</h5>
                    <div id="redesSociais">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="#ffffff"
                            class="bi bi-facebook" viewBox="0 0 16 16">
                            <path
                                d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951" />
                        </svg>

                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="#ffffff"
                            class="bi bi-instagram" viewBox="0 0 16 16">
                            <path
                                d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="frasesFinaisFooter">
                <p>2024 All Rights Reserved</p>
                <a href="">Termos de uso</a>
            </div>
        </div>

    </footer>

    <iframe name="hiddenFrame" style="display:none;"></iframe> <!-- Iframe invisível -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        AOS.init();
    </script>
    <script src="script.js"></script>
</body>

</html>
