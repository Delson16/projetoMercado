<?php
include 'restrito/conexao.php';


$idProduto = $_GET['id'];
$sql = "SELECT * FROM produtos  WHERE id = '$idProduto'";
$resultado = $conn->query($sql); 

if ($resultado->num_rows > 0) {
    $linha = $resultado->fetch_assoc();
    $nome = $linha['nome'];
    $imagem = $linha['imagem'];
    $preco = $linha['preco'];
    $categoria = $linha['categoria'];
    $local = $linha['localizacao'];
    $descricao = $linha['descricao'];
    $telefone = $linha['telefone'];
    $id_lojista = $linha['id_lojista'];

    $lojista = "SELECT * FROM lojistas  WHERE id = '$id_lojista'";
    $resultadoLojista = $conn->query($lojista);

    if ($resultadoLojista->num_rows > 0) {
        $linha = $resultadoLojista->fetch_assoc();
        $nomeLojista = $linha['nome'];
        $emailLojista = $linha['email'];
        $enderecoLojista = $linha['endereco'];
        $imgLojista = $linha['imagem_lojista'];
        $telefone = $linha['telefone'];
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
  <link rel="shortcut icon" href="../img/img pg inicial/logoAmareloEscuro.png" type="image/x-icon">
    <link rel="stylesheet" href="produtos.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="pgPadrao.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="shortcut icon" href="../img/img pg inicial/logoAmareloEscuro.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produto Individual</title>
</head>

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
                <img src="img/img pg inicial/logoAmareloEscuro.png" alt="Logo Mercazon" data-aos="zoom-in">
            </a>

            <form action="produtosBusca.php" class="pesquisaCentral" method="POST">
                <input type="text" placeholder="Busque Seus Produtos" name="nome">
                <button type="submit" name="filtro" value="preco"><img src="img/img pg padrao/lupa.png" alt="Lupa de pesquisa"></button>
            </form>


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
                    echo "<img src='imgs/profile.png' class='loginButton' data-bs-toggle='modal' alt='Icon de usuario padrao'
                    data-bs-target='#exampleModal' style='filter: invert(1);'>";

                } ?>

                <div class="dropdown">
                    <div aria-label="Adicionar aos favoritos" role="button" src="" alt="Coração de favoritos" class="naoClicado" id="favoritos" data-bs-toggle="dropdown"
                        aria-expanded="false"></div>
                    <ul class="dropdown-menu">

                        <?php

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
                    <div>
                        
                    </div>

                    <div class="imagensLoginCadastro">

                    </div>

                    <form action="index.php" method="post">
                        <label for="emailL">E-mail</label><br>
                        <input type="email" name="email" id="emailL"><br>

                        <label for="senhaL">Senha</label><br>
                        <div>
                            <img class="olho" src="img/img pg padrao/olho.png" alt="icone de olho aberto">
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
                    <div>

                    </div>

                    <div class="imagensLoginCadastro">
                    </div>

                    <form action="index.php" method="post">
                        <label for="emailL">E-mail</label><br>
                        <input type="email" name="email" id="emailL"><br>

                        <label for="senhaL">Senha</label><br>
                        <div>
                            <img class="olho" src="img/img pg padrao/olho.png" alt="icone de olho aberto">
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
                    <div>
                    </div>

                    <div class="imagensLoginCadastro">
                    </div>

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
                            <img class="olho" src="img/img pg padrao/olho.png" alt="icone de olho aberto">
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

   
    <main>
        <div class="conteudoPrincipalSuperior">
            <div class="galeria">
                <div class="imagensLaterais">
                    <img src="img/<?php echo "$imagem" ?>" alt="Produto 1" onclick="changeImage(this)">
                    <img src="img/<?php echo "$imagem" ?>" alt="Produto 2" onclick="changeImage(this)">
                    <img src="img/<?php echo "$imagem" ?>" alt="Produto 3" onclick="changeImage(this)">
                </div>
                <div>
                    <img id="imagemPrincipal" src="img/<?php echo "$imagem" ?>" alt="Produto">
                </div>
            </div>
            <div class="informacoesProduto">
                <h1><?php echo "$nome" ?></h1>
                <p>Categoria: <?php echo "$categoria" ?>
                    <br><br>
                    Marca: Arno
                    <br><br>
                    Condição: <?php echo "$descricao" ?>
                    <br><br>
                    Tipo: Ferros de Passar
                    <br><br>
                    Voltagem:
                    127v
                    <br><br>
                    <h2><?php echo "R$ $preco" ?></h2>
                </p>
                <p></p>
                <div class="botoesInteresse">
                    <a href="">
                        Eu quero!
                    </a>
                    <a href="">
                        <img src="img/img pg padrao/heart2Protótipo.png" alt="">
                        Favoritar
                    </a>
                </div>
            </div>
        </div>

        <h3 style="margin-top: 3.5%;">Informações do vendedor</h3>
        <div class="informacoesVendedor">
            <div class="containerInfoVendedor">
                <div class="borderBottom">
                    <a href="https://testemercazon.free.nf/lojistaUsuario.php?id=<?php echo "$id_lojista" ?>"><img src="img/<?php echo $imgLojista ?>" alt="" height="30px"></a>
                    <div>
                        <h4><?php echo "$nomeLojista" ?></h4>
                        <a style="font-size: 1.15rem;" href="https://testemercazon.free.nf/lojistaUsuario.php?id=<?php echo "$id_lojista" ?>">Ver Perfil</a>
                    </div>
                </div>

                <div class="borderBottom">
                    <img src="img/img pg padrao/localizacao.png" alt="">
                    <div>
                        <h4>Endereço</h4>
                        <h5><?php echo "$enderecoLojista" ?></h5>
                    </div>
                </div>
                
                <div style="margin-top: 2.5%;">
                    <img src="img/img pg padrao/telefone.png" alt="">
                    <div>
                        <h4>Contato</h4>
                        <h5 href="">51 98634-5174</h5>
                    </div>
                    <a class="whats d-flex" href="https://api.whatsapp.com/send/?phone=5551986278288&text&type=phone_number&app_absent=0">
                        <img src="img/img pg padrao/whatsapp.png" alt="">
                        <h5>Fale comigo</h5>
                    </a>
                </div>
            </div>
            <div class="mapa" href="">
                <img src="img/img pg padrao/mapa.webp" alt="" onclick="location.href='https:/www.google.com/maps/dir/Avenida%20Adelino%20Ferreira%20Jardim%20208'">
                <a href="https://www.google.com/maps/dir//Avenida%20Adelino%20Ferreira%20Jardim%20208">Como Chegar</a>
            </div>
        </div>
    </main>

    <article>
        <h2 style="margin-bottom: 3%;">Você também pode gostar</h2>
        <div class="containerCards" style="padding: 0%;">

                <div class='card' onclick="location.href='produto.html'">
                    <div class='parteSuperiorCard'>
                        <img src='img/img pg padrao/camisa.webp' alt='$nome'>
                        <form target='hiddenFrame' id='favoritar' action='restrito/favoritar.php' method='POST' onsubmit='event.stopPropagation()'>

                                    <button aria-label='Adicionar aos favoritos' type='submit' value='Favoritar' name='favoritoSubmit' onclick='event.stopPropagation()'>
                                <svg class='favoritaCoracao coracaoDesfavoritado' xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#004F90'  viewBox='0 0 16 16'>
                                    <path d='m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15' />
                                </svg>
                            </button></form>
                    </div>
                    <div class='parteInferiorCard'>
                        <h4>Camisa Suiça Home 2008 M</h4>
                        <h6>Usados da Leila</h6>
                        <h4>R$ 150</h6>
                        <a class='btn-p4' href=''>Ver Produto</a>
                    </div>
                </div>

                <div class='card' onclick="location.href='produto.html'">
                    <div class='parteSuperiorCard'>
                        <img src='img/img pg padrao/play.webp' alt='$nome'>
                        <form target='hiddenFrame' id='favoritar' action='restrito/favoritar.php' method='POST' onsubmit='event.stopPropagation()'>

                                    <button aria-label='Adicionar aos favoritos' type='submit' value='Favoritar' name='favoritoSubmit' onclick='event.stopPropagation()'>
                                <svg class='favoritaCoracao coracaoDesfavoritado' xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#004F90'  viewBox='0 0 16 16'>
                                    <path d='m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15' />
                                </svg>
                            </button></form>
                    </div>
                    <div class='parteInferiorCard'>
                        <h4>PS4 com defeito + 1 controle</h4>
                        <h6>Semi-novos do Luís</h6>
                        <h4>R$ 800</h6>
                        <a class='btn-p4' href=''>Ver Produto</a>
                    </div>
                </div>

                <div class='card' onclick="location.href='produto.html'">
                    <div class='parteSuperiorCard'>
                        <img src='img/img pg padrao/tenis.webp' alt='$nome'>
                        <form target='hiddenFrame' id='favoritar' action='restrito/favoritar.php' method='POST' onsubmit='event.stopPropagation()'>

                                    <button aria-label='Adicionar aos favoritos' type='submit' value='Favoritar' name='favoritoSubmit' onclick='event.stopPropagation()'>
                                <svg class='favoritaCoracao coracaoDesfavoritado' xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#004F90'  viewBox='0 0 16 16'>
                                    <path d='m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15' />
                                </svg>
                            </button></form>
                    </div>
                    <div class='parteInferiorCard'>
                        <h4>Tênis Asics americano 47</h4>
                        <h6>Brecho Exclusivo</h6>
                        <h4>R$ 119,99</h6>
                        <a class='btn-p4' href=''>Ver Produto</a>
                    </div>
                </div>

                <div class='card' onclick="location.href='produto.html'">
                    <div class='parteSuperiorCard'>
                        <img src='img/img pg padrao/guitarra.webp' alt='$nome'>
                        <form target='hiddenFrame' id='favoritar' action='restrito/favoritar.php' method='POST' onsubmit='event.stopPropagation()'>

                                    <button aria-label='Adicionar aos favoritos' type='submit' value='Favoritar' name='favoritoSubmit' onclick='event.stopPropagation()'>
                                <svg class='favoritaCoracao coracaoDesfavoritado' xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#004F90'  viewBox='0 0 16 16'>
                                    <path d='m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15' />
                                </svg>
                            </button></form>
                    </div>
                    <div class='parteInferiorCard'>
                        <h4>Guitarra Music Maker Evo Pro</h4>
                        <h6>Sound Music</h6>
                        <h4>R$ 13.500</h6>
                        <a class='btn-p4' href=''>Ver Produto</a>
                    </div>
                </div>

                <div class='card' onclick="location.href='produto.html'">
                    <div class='parteSuperiorCard'>
                        <img src='img/img pg padrao/byke.webp' alt='$nome'>
                        <form target='hiddenFrame' id='favoritar' action='restrito/favoritar.php' method='POST' onsubmit='event.stopPropagation()'>

                                    <button aria-label='Adicionar aos favoritos' type='submit' value='Favoritar' name='favoritoSubmit' onclick='event.stopPropagation()'>
                                <svg class='favoritaCoracao coracaoDesfavoritado' xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#004F90'  viewBox='0 0 16 16'>
                                    <path d='m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15' />
                                </svg>
                            </button></form>
                    </div>
                    <div class='parteInferiorCard'>
                        <h4>Caloi Strada 56</h4>
                        <h6>Nine Byke</h6>
                        <h4>R$ 3000</h6>
                        <a class='btn-p4' href=''>Ver Produto</a>
                    </div>
                </div>

            </div>


    </div>
    </article>

        <!-----------------------------CARROSSEL INICIO  --------------------------------- -->
        <h2 class="interesses titulos" style="margin-bottom: 5vh;">Também pode te interessar:</h2>
        <div id='carouselExampleInterval' class='carousel slide' data-bs-ride='carousel'>
            <div class='carousel-inner'>
                <?php
                $buscaProxima = "SELECT * FROM produtos WHERE id <> '$idProduto' AND categoria = '$categoria'";
                $result = $conn->query($buscaProxima);
                $firstItem = true; // Variável para controlar o primeiro item ativo no carrossel
                
                while ($linha1 = mysqli_fetch_assoc($result)) {
                    $nomeBusca1 = $linha1['nome'];
                    $imagemBusca1 = $linha1['imagem'];
                    $precoBusca1 = $linha1['preco'];
                    $idBusca1 = $linha1['id'];

                    // Pegue o próximo resultado para o segundo item
                    if ($linha2 = mysqli_fetch_assoc($result)) {
                        $nomeBusca2 = $linha2['nome'];
                        $imagemBusca2 = $linha2['imagem'];
                        $precoBusca2 = $linha2['preco'];
                        $idBusca2 = $linha2['id'];
                    } else {
                        // Se não houver mais resultados, defina valores vazios para o segundo item
                        $nomeBusca2 = '';
                        $imagemBusca2 = '';
                        $precoBusca2 = '';
                        $idBusca2 = '';
                    }

                    // Defina a classe 'active' apenas para o primeiro item
                    $activeClass = $firstItem ? 'active' : '';
                    ?>

                    <div class='carousel-item <?php echo $activeClass; ?>'>
                        <div class='row'>
                            <div class='col'>
                                <img src='img/<?php echo $imagemBusca1; ?>' class='d-block w-100 legenda' alt='Imagem 1'>
                                <div class='legenda'>
                                    <h5 class='legenda'><?php echo $nomeBusca1; ?></h5>
                                    <p class='legenda'>R$ <?php echo $precoBusca2; ?></p>
                                    <a class="btn-p4" href='produto.php?id=<?php echo $idBusca1; ?>'>Ver Produto</a>
                                </div>
                            </div>
                            <div class='col'>
                                <?php if (!empty($nomeBusca2)): ?>
                                    <img src='img/<?php echo $imagemBusca2; ?>' class='d-block w-100 legenda' alt='Imagem 2'>
                                    <div class='legenda'>
                                        <h5 class='legenda'><?php echo $nomeBusca2; ?></h5>
                                        <p class='legenda'>R$ <?php echo $precoBusca2; ?></p>
                                        <a class="btn-p4" href='produto.php?id=<?php echo $idBusca2; ?>'>Ver Produto</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php
                    $firstItem = false; // Após o primeiro item, definimos como false para não repetir 'active'
                }
                ?>

                <!-- Adicione mais itens conforme necessário -->

            </div>

            <!-- Botões de controle do carrossel -->
            <button class='carousel-control-prev' type='button' data-bs-target='#carouselExampleInterval'
                data-bs-slide='prev'>
                <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                <span class='visually-hidden'>Anterior</span>
            </button>
            <button class='carousel-control-next' type='button' data-bs-target='#carouselExampleInterval'
                data-bs-slide='next'>
                <span class='carousel-control-next-icon' aria-hidden='true'></span>
                <span class='visually-hidden'>Próximo</span>
            </button>
        </div>
        <h2 class="titulos">Sobre o anunciante:</h2>
        <div id="antes-anunciante">
            <div class="sobre-anunciante" id="anunciante">
                <div id="conjunto">
                    <img src="img/<?php echo "$imgLojista" ?>" alt="Foto do anunciante" class="Img-anunciante">
                    <div class="descricao-anunciante">
                        <h3 id="nome-anunciante"><?php echo "$nomeLojista" ?></h3>
                    </div>
                </div>
                <div>
                    <p><?php echo $descricaoLojista ?></p>
                </div>
            </div>
        </div>

        <div id="contatoLojista" class="localizacaoContato">
            <div class="contatoInfo">
                <h2>Contatos</h2>
                <h5><?php echo $emailLojista ?></h5>
                <a class="contatoWhats btn-p3" 
                    href="https://api.whatsapp.com/send/?phone=55<?php echo "$telefone" ?>&text&type=phone_number&app_absent=0" >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#4693ce" class="bi bi-whatsapp"
                        viewBox="0 0 16 16">
                        <path
                            d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232" />
                    </svg>
                    <h3 id="h3w"><?php echo "$telefone" ?></h3>
                </a>
            </div>

         


                   



        </div>
        </div>

         <div>
                <h2>Endereço:</h2>
                <h5><?php echo "$enderecoLojista" ?></h5>
                <a style=" white-space: nowrap; word-wrap: normal; overflow-wrap: normal;" href="<?php
    if (isset($_SESSION['idUser'])) {
        $id = $conn->real_escape_string($_SESSION['idUser']);
        $sql = "SELECT endereco FROM usuarios WHERE id = $id";
        if ($resultado = $conn->query($sql)) {
            $linha = mysqli_fetch_assoc($resultado);
            $enderecoPartida = $linha['endereco'];
            echo "https://www.google.com/maps/dir/$enderecoPartida/$enderecoLojista";
        } else {
            echo "Erro na consulta: " . $conn->error;
        }
    } else if (isset($_SESSION['idLojista'])) {
        $id = $conn->real_escape_string($_SESSION['idLojista']);
        $sql = "SELECT endereco FROM lojistas WHERE id = $id";
        if ($resultado = $conn->query($sql)) {
            $linha = mysqli_fetch_assoc($resultado);
            $enderecoPartida = $linha['endereco'];
            echo "https://www.google.com/maps/dir/$enderecoPartida/$enderecoLojista";
        } else {
            echo "Erro na consulta: " . $conn->error;
        }
    } else {
        echo "https://www.google.com/maps/dir//$enderecoLojista";
    }
    ?>" class="btn-p3">
                    Como Chegar
                </a>
            </div>
        <a href="<?php
    if (isset($_SESSION['idUser'])) {
        $id = $conn->real_escape_string($_SESSION['idUser']);
        $sql = "SELECT endereco FROM usuarios WHERE id = $id";
        if ($resultado = $conn->query($sql)) {
            $linha = mysqli_fetch_assoc($resultado);
            $enderecoPartida = $linha['endereco'];
            echo "https://www.google.com/maps/dir/$enderecoPartida/$enderecoLojista";
        } else {
            echo "Erro na consulta: " . $conn->error;
        }
    } else if (isset($_SESSION['idLojista'])) {
        $id = $conn->real_escape_string($_SESSION['idLojista']);
        $sql = "SELECT endereco FROM lojistas WHERE id = $id";
        if ($resultado = $conn->query($sql)) {
            $linha = mysqli_fetch_assoc($resultado);
            $enderecoPartida = $linha['endereco'];
            echo "https://www.google.com/maps/dir/$enderecoPartida/$enderecoLojista";
        } else {
            echo "Erro na consulta: " . $conn->error;
        }
    } else {
        echo "https://www.google.com/maps/dir//$enderecoLojista";
    }
    ?>">
        <img style="width: 340px; height: 240px; margin-bottom: 20%; margin-top: 5%" src="img/imgPgLojista/aa.png" alt="">
    </a>

    </main>

      

    <footer style="width: 120%">
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

    <script>
        function changeImage(element) {
            const mainImage = document.getElementById('imagemPrincipal');
            mainImage.src = element.src;
        }
    </script>

    <script src="script.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>