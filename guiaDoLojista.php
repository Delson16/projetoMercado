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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="guia_lojista.css">
    <link rel="stylesheet" href="pgPadrao.css">
    <link rel="shortcut icon" href="../img/img pg inicial/logoAmareloEscuro.png" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mercazon</title>
</head>


<!--PHP Login e cadastro-->
<?php
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
            echo "
                    <script>
            alert('senha ou email errados. Tente novamente!');
                    </script>";
        }
    } else {
        echo "<script>
            alert('senha ou email errados. Tente novamente!');
                    </script>";
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
        }
    }
    // fechar a conexão, ira abrir novamente quando outro usuário entrar
    $conn->close();
}

// Código referente ao login do lojista
if (isset($_POST['loginLojista'])) {

    include_once "restrito/conexao.php";

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
        }
    } else {
        echo "<script>
            alert('senha ou email errados. Tente novamente!');
                </script>";
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
            <a href="index.php">
                <img src="img/img pg inicial/logoAmareloEscuro.png" alt="" data-aos="zoom-in">
            </a>

            <form action="produtosBusca.php" class="pesquisaCentral" method="POST">
                <input type="text" placeholder="Busque Seus Produtos" name="nome">
                <button type="submit" name="filtro" value="preco"><img src="img/img pg padrao/lupa.png" alt=""></button>
            </form>


             <!-- O código em php a seguir valida se o usuário está logado. Caso esteja logado como usuário ou como lojista a sua foto será colocada no header e o link irá direcionar o usuário para a sua página e não mais para o modal de login -->
            <div class="d-flex">
                <?php if (isset($_SESSION['idUser'])) {
                    include_once "restrito/conexao.php";
                    $id = $_SESSION['idUser'];
                    $sql = "SELECT imagem_usuario FROM usuarios WHERE id = $id;";
                    $resultado = $conn->query($sql);
                    $linha = mysqli_fetch_assoc($resultado);
                    $imagemLogin = $linha['imagem_usuario'] ? ('img/' . $linha['imagem_usuario']) : "imgs/profile.png";

                    echo "<a href='restrito/usuario.php'> <img src='$imagemLogin' class='loginButton' data-bs-toggle='modal'> </a>";
                } else if (isset($_SESSION['idLojista'])) {
                    include_once "restrito/conexao.php";
                    $id = $_SESSION['idLojista'];
                    $sql = "SELECT imagem_lojista FROM lojistas WHERE id = $id;";
                    $resultado = $conn->query($sql);
                    $linha = mysqli_fetch_assoc($resultado);
                    $imagemLogin = $linha['imagem_lojista'] ? ('img/' . $linha['imagem_lojista']) : "imgs/profile.png";

                    echo "<a href='restrito/lojistaLojista.php'> <img src='$imagemLogin' class='loginButton' data-bs-toggle='modal'> </a>";

                } else {
                    echo "<img src='imgs/profile.png' class='loginButton' data-bs-toggle='modal'
                    data-bs-target='#exampleModal' style='filter: invert(1);'>";

                } ?>

                <div class="dropdown">
                    <div src="" alt="" class="naoClicado" id="favoritos" data-bs-toggle="dropdown"
                        aria-expanded="false"></div>
                    <ul class="dropdown-menu">

                        <?php

                        // Realiza a validação para ver se o usuário esta logado. Caso o usuário esteja logado o dropdown será montado com os ultimos 3 produtos favoritados pelo usuário. Também acresceta uma opção 'ver todos' que direciona o usuário para a sua página onde pode ver todos os seus produtos favoritados
                        if (isset($_SESSION['idUser'])) {
                            // Consulta SQl para aparecer os elementos favoritos no header
                            $sqlElementosFavoritosHeader = "SELECT p.id, p.nome, p.preco, p.categoria, p.imagem 
                                                        FROM produtos AS p
                                                        JOIN usuario_favorita_produto AS ufp ON p.id = ufp.id_produto
                                                        WHERE ufp.id_usuario = $user
                                                        ORDER BY ufp.id DESC
                                                        LIMIT 3;
                                                        ";
                            $resultado = $conn->query($sqlElementosFavoritosHeader);

                            while ($linha = mysqli_fetch_assoc($resultado)) {
                                $nome = $linha['nome'];
                                $imagem = $linha['imagem'];
                                $preco = $linha['preco'];
                                $categoria = $linha['categoria'];
                                $id = $linha['id'];

                                echo "
                                    <li class='produtosNoHeader'><a href='produto.php?id=$id' class='dropdown-item d-flex'> 
                                    <img src='img/$imagem' alt='$nome'>
                                    <div class= 'd-flex flex-column justify-content-center'>
                                    <h6>$nome</h6>
                                    <h6>R$ $preco</h6>
                                    </div>
                                    </a></li>
                                    ";
                            }
                            echo "<li><a class='dropdown-item' href='restrito/usuario.php'>Ver Todos</a></li>";
                        } else {
                            echo "<li data-bs-toggle='modal'
                    data-bs-target='#exampleModal'><a class='dropdown-item' style='cursor: pointer !important;'>Logue-se para ver os favoritos</a></li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Modal de login -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Login</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    

                    <form action="index.php" method="post">
                        <label for="emailL">E-mail</label><br>
                        <input type="email" name="email" id="emailL"><br>

                        <label for="senhaL">Senha</label><br>
                        <div>
                            <img class="olho" src="img/img pg padrao/olho.png" alt="">
                            <input type="password" name="senha" id="senhaL">
                        </div>


                        <a href="">Esqueceu Sua Senha?</a>

                        <br>
                        <br>
                        <div id="loginBotao">
                            <input name="loginSubmit" type="submit" value="Login">
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <!--Botão pro modal de cadastro-->
                    <button type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        <u>Cadastre-Se</u>
                    </button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modalLojistaLogin">
                        <u>Entrar como lojista</u>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de login -->
    <!-- Modal de login lojista -->
    <div class="modal fade" id="modalLojistaLogin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Entrar como lojista</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    

                    <form action="index.php" method="post">
                        <label for="emailL">E-mail</label><br>
                        <input type="email" name="email" id="emailL"><br>

                        <label for="senhaL">Senha</label><br>
                        <div>
                            <img class="olho" src="img/img pg padrao/olho.png" alt="">
                            <input type="password" name="senha" id="senhaL">
                        </div>


                        <a href="">Esqueceu Sua Senha?</a>

                        <br>
                        <br>
                        <div id="loginBotao">
                            <input name="loginLojista" type="submit" value="Login">
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <!--Botão pro modal de cadastro-->
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modalLojistaCadastro">
                        <u>Cadastre-Se como lojista</u>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de login lojista -->
    <!-- Modal de cadastro lojista -->
    <div class="modal fade" id="modalLojistaCadastro" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Cadastre-se como lojista!</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    

                    <form action="index.php" method="post" enctype="multipart/form-data">

                        <label for="nomeCadastroLojista">Nome</label>
                        <input type="text" name="nome" id="nomeCadastroLojista">
                        <label for="nomeEstabelecimento">Nome do seu estabelecimento</label>
                        <input type="text" name="nomeEstabelecimento" id="nomeEstabelecimento">
                        <label for="enderecoLojista">Endereço do seu estabelecimento</label>
                        <input type="text" name="endereco" id="enderecoLojista">
                        <label for="emailLojista">E-mail</label>
                        <input type="email" name="email" id="emailLojista">
                        <label for="telefone">Telefone</label>
                        <input type="text" name="telefone" id="telefone">

                        <label for="imagemLojista">Sua foto</label>
                        <input type="file" name="imagemUsuario" placeholder="imagem_usuario" accept="image/*"
                            id="imagemLojista">
                        <label for="imagemEstabelecimentoLojista">Foto do seu estabelecimento</label>
                        <input type="file" name="imagemEmpresa" placeholder="imagem_empresa" accept="image/*"
                            id="imagemEstabelecimentoLojista">

                        <label for="senhaL">Senha</label><br>
                        <div>
                            <img class="olho" src="img/img pg padrao/olho.png" alt="">
                            <input type="password" name="senha" id="senhaL">
                        </div>
                        <br>
                        <br>
                        <div id="loginBotao">
                            <input name="cadastroLojistaSubmit" type="submit" value="Cadastre-se">
                        </div>
                    </form>

                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
    <!-- Modal de cadastro lojista -->
    <!-- Modal de cadastro-->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Cadastro</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="index.php" method="POST">
                        <label for="nomeCa">Nome Completo</label><br>
                        <input type="text" name="nome" id="nomeCa"><br>

                        <label for="emailC">E-mail</label><br>
                        <input type="email" name="email" id="emailC"><br>

                        <label for="senhaC">Senha</label><br>
                        <input type="password" name="senha" id="senhaC"><br>

                        <label for="enderecoC">Endereço</label><br>
                        <input type="text" name="endereco" id="enderecoC"><br>

                        <label for="dataNcC">Data de Nascimento</label><br>
                        <input type="date" name="data_nascimento" id="dataNcC"><br>
                        <br>
                        <div id="loginBotao">
                            <input name="cadastroSubmit" type="submit" value="Cadastre-se">
                        </div>

                    </form>

                    <div class="modal-footer">
                        <!--Botão pro modal de login-->
                        <p>Já Possui Conta?</p>

                        <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <u>Logue-se</u>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Modal de cadastro-->



    <!-- Começo Do Conteúdo INICIO (COLOQUE O SEU CÓDIGO AQUI) -->
    <main style="padding: 0% 7%">

        <div class="containerPrincipal">
            <h1>Guia do Lojista</h1>
            <h3>Como eu me torno um lojista? </h3>
            <h6>Nessa página você encontrará informações sobre perguntas frequentes no nosso website. Dê uma olhada!
            </h6>
        </div>

        <!-- Accordion do Bootstrap -->

        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" id="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Como eu me registro no site?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="accordion-body" id="accordion-body">
                        <p>Você pode se registrar no site clicando no ícone de usuário no canto superior esquerdo e
                            preenchendo os dados necessários.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" id="accordion-button" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                        aria-controls="collapseTwo">
                        Eu não consigo entrar em tal página!
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" id="accordion-button"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body" id="accordion-body">
                        <p>Caso a página não carregue, recarregue apertando f5 ou ctrl+r no seu teclado. No celular você
                            pode apertar no botão de recarregar a página (que geralmente fica em cima). Também a página
                            que você está procurando pode estar indisponível.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" id="accordion-button"
                        data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false"
                        aria-controls="collapseThree">
                        Como eu posso favoritar um produto?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" id="accordion-button"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body" id="accordion-body">
                        <p>Entre na página de um produto e clique no ícone de coração que fica próximo ao produto. Aí
                            pronto! O produto selecionado vai estar na página de favoritos quando você se registrar no
                            site.</p>
                    </div>
                </div>
            </div>
        </div>


    </main>
    <!-- Começo Do Conteúdo FIM -->



    <!------------------------------------------------FOOTER---------------------------------------------------->

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

    <!------------------------------------------------FOOTER---------------------------------------------------->


    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>