<?php include_once "scripts.php";
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br"> 
<head>
    <script>
        // Este script ficou na parte superiro, pois precisa ser executado antes da construção da página.
        // funções que mantém os campos ativos com base na url
        function filtroAtivoRadio(valorInput) {
            const inputAtivado = document.querySelector(`input[value='${valorInput}']`);

            if (inputAtivado && inputAtivado.type == 'radio') {
                inputAtivado.checked = true;
            }
        }

        function filtroAtivoTexto(nameInput, valor) {
            const inputAtivado = document.querySelector(`input[name='${nameInput}']`);
            if (inputAtivado) {
                inputAtivado.value = valor;
            }
        }
    </script>
    <link rel="stylesheet" href="produtos.css">
    <link rel="stylesheet" href="pgPadrao.css">
    <link rel="shortcut icon" href="img/icons/logoAmareloEscuro.png" type="image/x-icon">
    <!-- Link do bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- link do bootstrap -->
    <!-- Fontes inter exportada do google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <!-- Fontes inter exportada do google -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mercazon</title>
</head>

<body>

    <header style="width: 100%">
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

            <form action="#" class="filtroNome pesquisaCentral" id="formNome">
                <input type="text" placeholder="Busque Seus Produtos" name="nome" id="buscarInput">
                <button type="button" onclick="filtrar()"><img src="img/icons/lupa.png" alt="Lupa de pesquisa"></button>
            </form>


            <div class="d-flex">
                <?php imagemPerfilHeader() ?>
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

    <main class="container">

        <!----------------------------- Menu de botões de filtro ----------------------------->
        <div class="menu">
            <div class="botoesMenu">
                <button class="buttonBusca" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#barraLateralOrdenar" aria-controls="offcanvasRight2">Ordenar</button>
                <button class="buttonBusca" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#barraLateralFiltrar" aria-controls="offcanvasRight" id="bFiltros">Filtros</button>
            </div>

        </div>
        <!----------------------------- Menu de botões de filtro ----------------------------->


        <!----------------------------- Telas laterias ----------------------------->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="barraLateralFiltrar"
            aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasRightLabel">Filtrar por</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">

                <form action="produtosBusca.php" method="POST" id="formFiltro">
                    <!-- DADOS OCULTOS DO FORM -->
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseOne" aria-expanded="false"
                                    aria-controls="flush-collapseOne">
                                    <h6>Categorias</h6>
                                </button>
                            </h2>
                            <div id="flush-collapseOne" class="accordion-collapse collapse"
                                data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body filtrosCorpo">

                                    <input type="radio" name="categoria" id="roupa" value="roupa" class="radioFiltro">
                                    <label for="roupa" class="buttonFiltroSelecao button">Roupas</label>

                                    <input type="radio" name="categoria" id="cosmético" value="cosmetico"
                                        class="radioFiltro">
                                    <label for="cosmético" class="buttonFiltroSelecao button">Cosméticos</label>

                                    <input type="radio" name="categoria" id="eletrônico" value="eletronico"
                                        class="radioFiltro">
                                    <label for="eletrônico" class="buttonFiltroSelecao button">Eletrônicos</label>

                                    <input type="radio" name="categoria" id="lanche" value="lanche" class="radioFiltro">
                                    <label for="lanche" class="buttonFiltroSelecao button">Lanches</label>

                                    <input type="radio" name="categoria" id="doce" value="doce" class="radioFiltro">
                                    <label for="doce" class="buttonFiltroSelecao button">Doces</label>

                                    <input type="radio" name="categoria" id="brinquedo" value="brinquedo"
                                        class="radioFiltro">
                                    <label for="brinquedo" class="buttonFiltroSelecao button">Brinquedos</label>

                                    <input type="radio" name="categoria" id="eletrodoméstico" value="eletrodomestico"
                                        class="radioFiltro">
                                    <label for="eletrodoméstico"
                                        class="buttonFiltroSelecao button">Eletrodomésticos</label>

                                    <input type="radio" name="categoria" id="servico" value="servico"
                                        class="radioFiltro">
                                    <label for="servico" class="buttonFiltroSelecao button">Serviços</label>

                                    <input type="radio" name="categoria" id="jogo" value="jogo" class="radioFiltro">
                                    <label for="jogo" class="buttonFiltroSelecao button">Jogos</label>

                                    <input type="radio" name="categoria" id="utensílio" value="utensilio"
                                        class="radioFiltro">
                                    <label for="utensílio" class="buttonFiltroSelecao button">Utensílios</label>

                                    <input type="radio" name="categoria" id="acessório" value="acessório"
                                        class="radioFiltro">
                                    <label for="acessório" class="buttonFiltroSelecao button">Acessórios</label>

                                    <input type="radio" name="categoria" id="calçado" value="calcado"
                                        class="radioFiltro">
                                    <label for="calçado" class="buttonFiltroSelecao button">Calçados</label>


                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseTwo" aria-expanded="false"
                                    aria-controls="flush-collapseTwo">
                                    <h6>Marca</h6>
                                </button>
                            </h2>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body filtrosCorpo">
                                    <input type="text" name="marca" class="texoMarca">
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseThree" aria-expanded="false"
                                    aria-controls="flush-collapseThree">
                                    <h6>Gênero</h6>
                                </button>
                            </h2>
                            <div id="flush-collapseThree" class="accordion-collapse collapse"
                                data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body filtrosCorpo">
                                    <input type="radio" name="genero" id="masculino" value="masculino"
                                        class="radioFiltro">
                                    <label for="masculino" class="buttonFiltroSelecao button">Masculino</label>

                                    <input type="radio" name="genero" id="feminino" value="feminino"
                                        class="radioFiltro">
                                    <label for="feminino" class="buttonFiltroSelecao button">Feminino</label>

                                    <input type="radio" name="genero" id="unissex" value="unissex" class="radioFiltro">
                                    <label for="unissex" class="buttonFiltroSelecao button">Unissex</label>

                                    <input type="radio" name="genero" id="infantil" value="infantil"
                                        class="radioFiltro">
                                    <label for="infantil" class="buttonFiltroSelecao button">Infantil</label>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseFour" aria-expanded="false"
                                    aria-controls="flush-collapseFour">
                                    <h6>Cor</h6>
                                </button>
                            </h2>
                            <div id="flush-collapseFour" class="accordion-collapse collapse"
                                data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body filtrosCorpo">
                                    <input type="radio" name="cor" id="preto" value="preto" class="radioFiltro">
                                    <label for="preto" class="buttonFiltroSelecao button">Preto</label>

                                    <input type="radio" name="cor" id="branco" value="branco" class="radioFiltro">
                                    <label for="branco" class="buttonFiltroSelecao button">Branco</label>

                                    <input type="radio" name="cor" id="vermelho" value="vermelho" class="radioFiltro">
                                    <label for="vermelho" class="buttonFiltroSelecao button">Vermelho</label>

                                    <input type="radio" name="cor" id="azul" value="azul" class="radioFiltro">
                                    <label for="azul" class="buttonFiltroSelecao button">Azul</label>

                                    <input type="radio" name="cor" id="verde" value="verde" class="radioFiltro">
                                    <label for="verde" class="buttonFiltroSelecao button">Verde</label>

                                    <input type="radio" name="cor" id="amarelo" value="amarelo" class="radioFiltro">
                                    <label for="amarelo" class="buttonFiltroSelecao button">Amarelo</label>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseFive" aria-expanded="false"
                                    aria-controls="flush-collapseFive">
                                    <h6>Preço</h6>
                                </button>
                            </h2>
                            <div id="flush-collapseFive" class="accordion-collapse collapse"
                                data-bs-parent="#accordionFlushExample">
                                <div class="preco">
                                    <span>Menor preço</span>
                                    <span>Maior preço</span>
                                    <input type="number" id="maxPrice" min="0" max="90000" name="precoMenor" value="0">
                                    <input type="number" id="minPrice" min="0" max="90000" name="precoMaior"
                                        value="90000">
                                </div>
                            </div>
                        </div>
                    </div>
                    <label for="aplicarFiltro" class="labelSubmitFiltro">Aplicar filtros</label>
                    <input id="aplicarFiltro" type="button" class="submitFiltro" name="filtro"
                        data-bs-dismiss="offcanvas" aria-label="Close" onclick="filtrar()">

                    <label for="removerFiltro" class="labelSubmitFiltro">Remover filtros</label>
                    <input id="removerFiltro" type="button" class="submitFiltro" name="remover_filtro" data-bs-dismiss="offcanvas" aria-label="Close" onclick="removerFiltros()">
                </form>
            </div>

        </div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="barraLateralOrdenar"
            aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasRightLabel">Ordenar por</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body ordenarCorpo">
                <form action="#" id="formOrdem">
                    <input type="radio" name="ordem" id="maisBuscados" value="contador_cliques DESC" class="radioFiltro" checked>
                    <label for="maisBuscados" class="button buttonFiltroSelecao" onclick="filtrar()">Mais buscados</label>

                    <input type="radio" name="ordem" id="maiorPreco" value="preco DESC" class="radioFiltro">
                    <label for="maiorPreco" class="button buttonFiltroSelecao" onclick="filtrar()">Maior preço</label>

                    <input type="radio" id="menorPreco" value="preco ASC" name="ordem" class="radioFiltro">
                    <label for="menorPreco" class="button buttonFiltroSelecao" onclick="filtrar()">Menor preço</label>
                </form>

            </div>
        </div>
        <!----------------------------- Telas laterias ----------------------------->

        <div class="containerCards">

            <?php
            include_once "scripts.php";
            include_once "restrito/conexao.php";

            $conn = pegarConexao('usuario');

            $num_items_por_pagina = 16;
            $pagina_atual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
            $offset = ($pagina_atual - 1) * $num_items_por_pagina;

            $precoMaior = isset($_GET['precoMaior']) ? clear($conn, $_GET['precoMaior']) : 90000;
            $precoMenor = isset($_GET['precoMenor']) ? clear($conn, $_GET['precoMenor']) : 0;
            $nome = isset($_GET['nome']) ? clear($conn, $_GET['nome']) : "";
            $ordem = isset($_GET['ordem']) ? clear($conn, $_GET['ordem']) : "contador_cliques DESC";

            $sql = "SELECT p.id, p.nome, p.preco, p.imagem, l.nome_estabelecimento
                FROM produtos AS p JOIN lojistas as l ON p.id_lojista = l.id
                WHERE ";
            $sqlContadora = "SELECT COUNT(id) AS total FROM produtos AS p WHERE ";

            if (isset($_GET['categoria']) && $_GET['categoria']) {
                $categoria = clear($conn, $_GET['categoria']);
                $sql .= "p.categoria = '$categoria' AND ";
                $sqlContadora .= "p.categoria = '$categoria' AND ";
                echo "<script> filtroAtivoRadio('$categoria'); </script>";
            }
            if (isset($_GET['marca']) && $_GET['marca']) {
                $marca = clear($conn, $_GET['marca']);
                $sql .= "p.marca like '%$marca%' AND ";
                $sqlContadora .= "p.marca = '$marca' AND ";
                echo "<script> filtroAtivoTexto('marca', '$marca') </script>";
            }
            if (isset($_GET['genero']) && $_GET['genero']) {
                $genero = clear($conn, $_GET['genero']);
                $sql .= "p.genero = '$genero' AND ";
                $sqlContadora .= "p.genero = '$genero' AND ";
                echo "<script> filtroAtivoRadio('$genero'); </script>";
            }
            if (isset($_GET['cor']) && $_GET['cor']) {
                $cor = clear($conn, $_GET['cor']);
                $sql .= "p.cor = '$cor' AND ";
                $sqlContadora .= "p.cor = '$cor' AND ";
                echo "<script> filtroAtivoRadio('$cor'); </script>";
            }
            if ($nome) {
                echo "<script> filtroAtivoTexto('nome', '$nome') </script>";
            }
            if ($ordem != 'contador_cliques DESC') {
                echo "<script> filtroAtivoRadio('$ordem'); </script>";
            }
            echo "<script> filtroAtivoTexto('precoMaior', '$precoMaior') </script>";
            echo "<script> filtroAtivoTexto('precoMenor', '$precoMenor') </script>";

            $pesquisarNome = $nome ? "p.nome LIKE '%$nome%' AND" : "";
            $sql = $sql . "$pesquisarNome p.preco BETWEEN $precoMenor AND $precoMaior ORDER BY $ordem LIMIT $offset, $num_items_por_pagina;";
            $sqlContadora = $sqlContadora . "$pesquisarNome preco BETWEEN $precoMenor AND $precoMaior;";

            // Trabalhar mais na função. fazer um parâmetro para que opere dentro e fora do restrito. Também rever outras questões como favorito e funções específicas para usuários específicos (usuario/lojista)
            gerarCard($sql, 'usuario');
            ?>
        </div>

        <nav aria-label="Paginação">
            <ul class="pagination justify-content-center">

                <?php
                $resultadoContador = mysqli_query($conn, $sqlContadora);
                $numeroLinhas = mysqli_fetch_assoc($resultadoContador);
                $total_paginas = ceil($numeroLinhas['total'] / $num_items_por_pagina);

                for ($i = 1; $i <= $total_paginas; $i++) {
                    echo "<li class='page-item " . ($pagina_atual == $i ? 'active' : '') . "'>
                        <button class='page-link' onclick='paginacao($i)'>$i</button>
                        </li>";
                }
                ?>
            </ul>
        </nav>
    </main>

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

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
<script src="script.js"></script>
<script>
    // função de filtro. Pegas as informações necessárias para carregar o filtro de produtos e faz uma requisição php via url.
    function filtrar() {

        setTimeout(() => {

            const form = document.getElementById('formFiltro');
            const formDados = new FormData(form);

            const formularioFiltrosOrdem = document.getElementById("formOrdem");
            const ordem = formularioFiltrosOrdem.querySelector('input[name="ordem"]:checked');
            const nome = document.getElementById('buscarInput');
            formDados.append('ordem', ordem.value);
            formDados.append('nome', nome.value);

            const formDadosUrl = new URLSearchParams(formDados).toString();
            const url = form.action + '?' + formDadosUrl;
            window.location.href = url;

        }, 200);
    }

    // Função que remove os filtros da página de filtros. Ela recarrega a página com a url, mas sem elemntos da requisição get em php
    function removerFiltros() {
        const url = window.location.href;
        const novoUrl = url.split("?");
        window.location.href = novoUrl[0];
    }

    // Função que adiciona o parâmetro de paginação a url e recarrega a página
    function paginacao(paginaAtual) {
        const url = new URL(window.location.href);
        url.searchParams.set('pagina', paginaAtual);
        window.location.href = url.toString();

    }
</script>

</html>