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
                <button type="submit" name="filtro" value="preco"><img src="img/img pg padrao/lupa.png"
                        alt="Lupa de pesquisa"></button>
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
                    <div aria-label="Adicionar aos favoritos" role="button" src="" alt="Coração de favoritos"
                        class="naoClicado" id="favoritos" data-bs-toggle="dropdown" aria-expanded="false"></div>
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

        <h3 class="tituloInfo">Informações do vendedor</h3>
        <div class="informacoesVendedor">
            <div class="containerInfoVendedor">
                <div class="borderBottom">
                    <a href="https://testemercazon.free.nf/lojistaUsuario.php?id=<?php echo "$id_lojista" ?>"><img
                            src="img/<?php echo $imgLojista ?>" alt="" height="30px"></a>
                    <div>
                        <h4><?php echo "$nomeLojista" ?></h4>
                        <a style="font-size: 1.15rem;"
                            href="https://testemercazon.free.nf/lojistaUsuario.php?id=<?php echo "$id_lojista" ?>">Ver
                            Perfil</a>
                    </div>
                </div>

                <div class="borderBottom">
                    <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">

                    <!-- Uploaded to: SVG Repo, www.svgrepo.com, Transformed by: SVG Repo Mixer Tools -->
                    <svg fill="#004F90" width="100%" height="100%" viewBox="0 0 100 100"
                        xmlns="http://www.w3.org/2000/svg">

                        <g id="SVGRepo_bgCarrier" stroke-width="0" />

                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />

                        <g id="SVGRepo_iconCarrier">

                            <path
                                d="M49,18.92A23.74,23.74,0,0,0,25.27,42.77c0,16.48,17,31.59,22.23,35.59a2.45,2.45,0,0,0,3.12,0c5.24-4.12,22.1-19.11,22.1-35.59A23.74,23.74,0,0,0,49,18.92Zm0,33.71a10,10,0,1,1,10-10A10,10,0,0,1,49,52.63Z" />

                        </g>

                    </svg>
                    <div>
                        <h4>Endereço</h4>
                        <h5><?php echo "$enderecoLojista" ?></h5>
                    </div>
                </div>

                <div style="margin-top: 2.5%;">
                    <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">

                    <!-- Uploaded to: SVG Repo, www.svgrepo.com, Transformed by: SVG Repo Mixer Tools -->
                    <svg fill="#004F90" width="100%" height="90%" viewBox="-4 0 19 19"
                        xmlns="http://www.w3.org/2000/svg" class="cf-icon-svg">

                        <g id="SVGRepo_bgCarrier" stroke-width="0" />

                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />

                        <g id="SVGRepo_iconCarrier">

                            <path
                                d="M10.114 2.69v13.76a1.123 1.123 0 0 1-1.12 1.12H2.102a1.123 1.123 0 0 1-1.12-1.12V2.69a1.123 1.123 0 0 1 1.12-1.12h6.892a1.123 1.123 0 0 1 1.12 1.12zm-1.12 1.844H2.102V14.78h6.892zm-5.31-1.418a.56.56 0 0 0 .56.56h2.61a.56.56 0 0 0 0-1.12h-2.61a.56.56 0 0 0-.56.56zm2.423 13.059a.558.558 0 1 0-.559.558.558.558 0 0 0 .559-.558z" />

                        </g>

                    </svg>
                    <div>
                        <h4>Contato</h4>
                        <h5 href="">51 98634-5174</h5>
                    </div>
                    <a class="whats d-flex"
                        href="https://api.whatsapp.com/send/?phone=<?php echo"$telefone" ?>&text&type=phone_number&app_absent=0">
                        <!DOCTYPE svg
                            PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
                        <svg width="100%" height="100%" viewBox="0 0 48 48" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            fill="#000000">

                            <g id="SVGRepo_bgCarrier" stroke-width="0" />

                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />

                            <g id="SVGRepo_iconCarrier">
                                <title>Whatsapp-color</title>
                                <desc>Created with Sketch.</desc>
                                <defs> </defs>
                                <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Color-" transform="translate(-700.000000, -360.000000)" fill="#67C15E">
                                        <path
                                            d="M723.993033,360 C710.762252,360 700,370.765287 700,383.999801 C700,389.248451 701.692661,394.116025 704.570026,398.066947 L701.579605,406.983798 L710.804449,404.035539 C714.598605,406.546975 719.126434,408 724.006967,408 C737.237748,408 748,397.234315 748,384.000199 C748,370.765685 737.237748,360.000398 724.006967,360.000398 L723.993033,360.000398 L723.993033,360 Z M717.29285,372.190836 C716.827488,371.07628 716.474784,371.034071 715.769774,371.005401 C715.529728,370.991464 715.262214,370.977527 714.96564,370.977527 C714.04845,370.977527 713.089462,371.245514 712.511043,371.838033 C711.806033,372.557577 710.056843,374.23638 710.056843,377.679202 C710.056843,381.122023 712.567571,384.451756 712.905944,384.917648 C713.258648,385.382743 717.800808,392.55031 724.853297,395.471492 C730.368379,397.757149 732.00491,397.545307 733.260074,397.27732 C735.093658,396.882308 737.393002,395.527239 737.971421,393.891043 C738.54984,392.25405 738.54984,390.857171 738.380255,390.560912 C738.211068,390.264652 737.745308,390.095816 737.040298,389.742615 C736.335288,389.389811 732.90737,387.696673 732.25849,387.470894 C731.623543,387.231179 731.017259,387.315995 730.537963,387.99333 C729.860819,388.938653 729.198006,389.89831 728.661785,390.476494 C728.238619,390.928051 727.547144,390.984595 726.969123,390.744481 C726.193254,390.420348 724.021298,389.657798 721.340985,387.273388 C719.267356,385.42535 717.856938,383.125756 717.448104,382.434484 C717.038871,381.729275 717.405907,381.319529 717.729948,380.938852 C718.082653,380.501232 718.421026,380.191036 718.77373,379.781688 C719.126434,379.372738 719.323884,379.160897 719.549599,378.681068 C719.789645,378.215575 719.62006,377.735746 719.450874,377.382942 C719.281687,377.030139 717.871269,373.587317 717.29285,372.190836 Z"
                                            id="Whatsapp"> </path>
                                    </g>
                                </g>
                            </g>

                        </svg>
                        <h5>Fale comigo</h5>
                    </a>
                </div>
            </div>
            <div class="mapa" href="">
                <img src="img/img pg padrao/mapa.PNG" alt=""
                    onclick="location.href='https:/www.google.com/maps/dir<?php echo $enderecoLojista ?>'">
                <a href="https://www.google.com/maps/dir//<?php echo $enderecoLojista ?>">Como Chegar</a>
            </div>
        </div>
    </main>

    <article>
        <h2>Você também pode gostar</h2>
        <div class="containerCards" style="padding: 0%;">

            <div class='card' onclick="location.href='produto.html'">
                <div class='parteSuperiorCard'>
                    <img src='img/img pg padrao/camisa.webp' alt='$nome'>
                    <form target='hiddenFrame' id='favoritar' action='restrito/favoritar.php' method='POST'
                        onsubmit='event.stopPropagation()'>

                        <button aria-label='Adicionar aos favoritos' type='submit' value='Favoritar'
                            name='favoritoSubmit' onclick='event.stopPropagation()'>
                            <svg class='favoritaCoracao coracaoDesfavoritado' xmlns='http://www.w3.org/2000/svg'
                                width='16' height='16' fill='#004F90' viewBox='0 0 16 16'>
                                <path
                                    d='m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15' />
                            </svg>
                        </button>
                    </form>
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
                    <form target='hiddenFrame' id='favoritar' action='restrito/favoritar.php' method='POST'
                        onsubmit='event.stopPropagation()'>

                        <button aria-label='Adicionar aos favoritos' type='submit' value='Favoritar'
                            name='favoritoSubmit' onclick='event.stopPropagation()'>
                            <svg class='favoritaCoracao coracaoDesfavoritado' xmlns='http://www.w3.org/2000/svg'
                                width='16' height='16' fill='#004F90' viewBox='0 0 16 16'>
                                <path
                                    d='m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15' />
                            </svg>
                        </button>
                    </form>
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
                    <form target='hiddenFrame' id='favoritar' action='restrito/favoritar.php' method='POST'
                        onsubmit='event.stopPropagation()'>

                        <button aria-label='Adicionar aos favoritos' type='submit' value='Favoritar'
                            name='favoritoSubmit' onclick='event.stopPropagation()'>
                            <svg class='favoritaCoracao coracaoDesfavoritado' xmlns='http://www.w3.org/2000/svg'
                                width='16' height='16' fill='#004F90' viewBox='0 0 16 16'>
                                <path
                                    d='m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15' />
                            </svg>
                        </button>
                    </form>
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
                    <form target='hiddenFrame' id='favoritar' action='restrito/favoritar.php' method='POST'
                        onsubmit='event.stopPropagation()'>

                        <button aria-label='Adicionar aos favoritos' type='submit' value='Favoritar'
                            name='favoritoSubmit' onclick='event.stopPropagation()'>
                            <svg class='favoritaCoracao coracaoDesfavoritado' xmlns='http://www.w3.org/2000/svg'
                                width='16' height='16' fill='#004F90' viewBox='0 0 16 16'>
                                <path
                                    d='m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15' />
                            </svg>
                        </button>
                    </form>
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
                    <form target='hiddenFrame' id='favoritar' action='restrito/favoritar.php' method='POST'
                        onsubmit='event.stopPropagation()'>

                        <button aria-label='Adicionar aos favoritos' type='submit' value='Favoritar'
                            name='favoritoSubmit' onclick='event.stopPropagation()'>
                            <svg class='favoritaCoracao coracaoDesfavoritado' xmlns='http://www.w3.org/2000/svg'
                                width='16' height='16' fill='#004F90' viewBox='0 0 16 16'>
                                <path
                                    d='m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15' />
                            </svg>
                        </button>
                    </form>
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