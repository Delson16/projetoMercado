<?php
include_once 'restrito/conexao.php';
// include_once 'validarUsuario.php';
session_start();
if (isset($_SESSION['idUser'])) {
    $user = $_SESSION['idUser'];
} else if (isset($_SESSION['idLojista'])) {
    $user = $_SESSION['idLojista'];
} else {
    $user = " ";
}
$_SESSION['sql'];
?>

<?php

// Configuração da paginação
$num_items_por_pagina = 16; // Número de itens por página
$pagina_atual = isset($_GET['pagina']) ? $_GET['pagina'] : 1; // Página atual, padrão é 1
$offset = ($pagina_atual - 1) * $num_items_por_pagina; // Calcular o offset

// Consulta SQL com LIMIT e OFFSET para implementar a paginação
// $sql = "SELECT * FROM produtos WHERE id_lojista = $user LIMIT $offset, $num_items_por_pagina";
// $result = $conn->query($sql);

// função que compara a string de uma variavel com um argumento fornecido, se for true ela retorna checked, senão retorna uma string vazia. 
function checarFiltro($dado, $argumento)
{
    if (isset($dado)) {
        if ($dado == $argumento) {
            return 'checked';
        } else {
            return ' ';
        }
    } else {
        return ' ';
    }
}
function checarOrdenm($dado, $argumento)
{
    if (isset($dado)) {
        if ($dado == $argumento) {
            return 'ordemMarcada';
        } else {
            return ' ';
        }
    } else {
        return ' ';
    }
}

$categoria = "";
$marca = "";
$genero = "";
$cor = "";
$precoMaior = 0;
$precoMenor = 90000;
$ordem = 'preco';
$nome = isset($_POST['nome']) ? $_POST['nome'] : " ";

if (isset($_POST['filtro'])) {
    $precoMaior = isset($_POST['precoMaior']) ? $_POST['precoMaior'] : 0;
    $precoMenor = isset($_POST['precoMenor']) ? $_POST['precoMenor'] : 90000;
    $nome = $_POST['nome'];
    $ordem = $_POST['filtro'];

    $sql = "SELECT p.id, p.nome, p.preco, p.imagem, l.nome_estabelecimento
                FROM produtos AS p JOIN lojistas as l ON p.id_lojista = l.id
                WHERE ";
    $sqlContadora = "SELECT COUNT(id) AS total FROM produtos AS p WHERE ";

    if (isset($_POST['categoria']) && $_POST['categoria']) {
        $categoria = $_POST['categoria'];
        $sql .= "p.categoria = '$categoria' AND ";
        $sqlContadora .= "p.categoria = '$categoria' AND ";
    }
    if (isset($_POST['marca']) && $_POST['marca']) {
        $marca = $_POST['marca'];
        $sql .= "p.marca like '%$marca%' AND ";
        $sqlContadora .= "p.marca = '$marca' AND ";
    }
    if (isset($_POST['genero']) && $_POST['genero']) {
        $genero = $_POST['genero'];
        $sql .= "p.genero = '$genero' AND ";
        $sqlContadora .= "p.genero = '$genero' AND ";
    }
    if (isset($_POST['cor']) && $_POST['cor']) {
        $cor = $_POST['cor'];
        $sql .= "p.cor = '$cor' AND ";
        $sqlContadora .= "p.cor = '$cor' AND ";
    }
    $pesquisarNome = $nome ? "p.nome LIKE '%$nome%' AND" : "";
    $sql = $sql . "$pesquisarNome p.preco BETWEEN $precoMaior AND $precoMenor ORDER BY $ordem LIMIT $offset, $num_items_por_pagina;";
    $sqlContadora = $sqlContadora . "$pesquisarNome preco BETWEEN $precoMaior AND $precoMenor;";
    $_SESSION['sql'] = $sql;
    $_SESSION['sqlContadora'] = $sqlContadora;
} else if (isset($_GET['categoria'])) {
    $categoria = $_GET['categoria'];
    $sql = "SELECT p.id, p.nome, p.preco, p.imagem, l.nome_estabelecimento FROM produtos AS p JOIN lojistas as l ON p.id_lojista = l.id WHERE categoria = '$categoria' ORDER BY 'contador_cliques' LIMIT $offset, $num_items_por_pagina;";
    $sqlContadora = "SELECT COUNT(id) AS total FROM produtos WHERE categoria = '$categoria' ORDER BY 'contador_cliques' LIMIT $offset, $num_items_por_pagina;";
    $_SESSION['sql'] = $sql;
    $_SESSION['sqlContadora'] = $sqlContadora;
}

if (isset($_POST['removeFiltro'])) {

    $categoria = "";
    $marca = "";
    $genero = "";
    $cor = "";
    $precoMaior = 0;
    $precoMenor = 90000;
    $ordem = 'preco';
    $nome = $_POST['nome'];
    $pesquisarNome = $nome ? "p.nome LIKE '%$nome%' AND" : "";

    $sql = "SELECT p.id, p.nome, p.preco, p.imagem, l.nome_estabelecimento FROM produtos AS p JOIN lojistas as l ON p.id_lojista = l.id WHERE $pesquisarNome p.preco BETWEEN 0 AND 90000 ORDER BY preco LIMIT $offset, $num_items_por_pagina;";
    $sqlContadora = "SELECT COUNT(id) AS total FROM produtos WHERE $pesquisarNome preco BETWEEN $precoMaior AND $precoMenor;";
    $_SESSION['sql'] = $sql;
    $_SESSION['sqlContadora'] = $sqlContadora;
}
?>

<!--PHP Login e cadastro-->
<?php
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

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="shortcut icon" href="../img/img pg inicial/logoAmareloEscuro.png" type="image/x-icon">
    <link rel="stylesheet" href="pgPadrao.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="produtos.css">
    <link rel="stylesheet" href="pgPadrao.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mercazon</title>

    <script>
                document.addEventListener("DOMContentLoaded", () => {
            // Realiza a pesquisa inicial em branco
            searchCentral();
        });
    </script>
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
            <a href="index.php">
                <img src="img/img pg inicial/logoAmareloEscuro.png" alt="" data-aos="zoom-in">
            </a>



            <div class=" navbar-brand container-autocomplete">
                <div class="d-flex">
                    <input type="text" id="centralSearch" class="form-control" placeholder="Pesquisar...">
                    <button class="btn btn-primary" onclick="searchCentral()">Buscar</button>
                </div>
                <div id="resultadosBusca"></div>
            </div>


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
                        <p>Entre Com</p>
                    </div>

                    <div class="imagensLoginCadastro">
                        <img src="img/img pg padrao/face.png" alt="">
                        <img src="img/img pg padrao/google.png" alt="">
                    </div>

                    <form action="" method="post">
                        <label for="emailL">E-mail</label><br>
                        <input type="email" name="emailL" id="email"><br>

                        <label for="senhaL">Senha</label><br>
                        <div>
                            <img class="olho" src="img/img pg padrao/olho.png" alt="">
                            <input type="password" name="senhaL" id="senha">
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
                    <p>Não Possui Conta?</p>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        <u>Cadastre-Se</u>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de login -->

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
                        <input type="text" name="nomeC" id="nomeCa"><br>

                        <label for="emailC">E-mail</label><br>
                        <input type="email" name="emailC" id="emailC"><br>

                        <label for="senhaC">Senha</label><br>
                        <input type="number" name="senhaC" id="senhaC"><br>

                        <label for="enderecoC">Endereço</label><br>
                        <input type="text" name="enderecoC" id="enderecoC"><br>

                        <label for="dataNcC">Data de Nascimento</label><br>
                        <input type="date" name="data_nascimentoC" id="dataNcC"><br>

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

    <!---------------------- Conteudo principal - MAIN ---------------------->

    <main class="container">
        <!-- nome, nome da loja e preço -->
        
        <!----------------------------- Menu de botões de filtro ----------------------------->
        <div class="menu">
            <!-- Botões de aplicar filtro e de ordenar produtos -->
            <div class="botoesMenu">
                <button class="buttonBusca" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight2"
                aria-controls="offcanvasRight2">Ordenar</button>
                <button class="buttonBusca" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                aria-controls="offcanvasRight" id="bFiltros">Filtros</button>
            </div>
            
        </div>
        <!----------------------------- Menu de botões de filtro ----------------------------->
        
        <!----------------------------- Telas laterias ----------------------------->
        <form action="" method="POST">
            
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
            aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasRightLabel">Filtrar por</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <!-- DADOS OCULTOS DO FORM -->
                <input type="hidden" name="nome" id="nomeProduto">
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
                        
                                    <input type="radio" name="categoria" id="roupa" value="roupa" class="radioFiltro"
                                    <?php echo checarFiltro($categoria, 'roupa'); ?>>
                                    <label for="roupa" class="buttonFiltroSelecao button">Roupas</label>
                                    
                                    <input type="radio" name="categoria" id="cosmético" value="cosmetico"
                                    class="radioFiltro" <?php echo checarFiltro($categoria, 'cosmético'); ?>>
                                    <label for="cosmético" class="buttonFiltroSelecao button">Cosméticos</label>
                                    
                                    <input type="radio" name="categoria" id="eletrônico" value="eletronico"
                                    class="radioFiltro" <?php echo checarFiltro($categoria, 'eletrônico'); ?>>
                                    <label for="eletrônico" class="buttonFiltroSelecao button">Eletrônicos</label>
                                    
                                    <input type="radio" name="categoria" id="lanche" value="lanche" class="radioFiltro"
                                    <?php echo checarFiltro($categoria, 'lanche'); ?>>
                                    <label for="lanche" class="buttonFiltroSelecao button">Lanches</label>
                                    
                                    <input type="radio" name="categoria" id="doce" value="doce" class="radioFiltro"
                                    <?php echo checarFiltro($categoria, 'doce'); ?>>
                                    <label for="doce" class="buttonFiltroSelecao button">Doces</label>
                                    
                                    <input type="radio" name="categoria" id="brinquedo" value="brinquedo"
                                    class="radioFiltro" <?php echo checarFiltro($categoria, 'brinquedo'); ?>>
                                    <label for="brinquedo" class="buttonFiltroSelecao button">Brinquedos</label>
                                    
                                    <input type="radio" name="categoria" id="eletrodoméstico" value="eletrodomestico"
                                    class="radioFiltro" <?php echo checarFiltro($categoria, 'eletrodoméstico'); ?>>
                                    <label for="eletrodoméstico"
                                    class="buttonFiltroSelecao button">Eletrodomésticos</label>
                                    
                                    <input type="radio" name="categoria" id="servico" value="servico"
                                    class="radioFiltro" <?php echo checarFiltro($categoria, 'servico'); ?>>
                                    <label for="servico" class="buttonFiltroSelecao button">Serviços</label>
                                    
                                    <input type="radio" name="categoria" id="jogo" value="jogo" class="radioFiltro"
                                    <?php echo checarFiltro($categoria, 'jogo'); ?>>
                                    <label for="jogo" class="buttonFiltroSelecao button">Jogos</label>
                                    
                                    <input type="radio" name="categoria" id="utensílio" value="utensilio"
                                    class="radioFiltro" <?php echo checarFiltro($categoria, 'utensílio'); ?>>
                                    <label for="utensílio" class="buttonFiltroSelecao button">Utensílios</label>
                                    
                                    <input type="radio" name="categoria" id="acessório" value="acessório"
                                    class="radioFiltro" <?php echo checarFiltro($categoria, 'acessório'); ?>>
                                    <label for="acessório" class="buttonFiltroSelecao button">Acessórios</label>
                                    
                                    <input type="radio" name="categoria" id="calçado" value="calcado"
                                    class="radioFiltro" <?php echo checarFiltro($categoria, 'calçado'); ?>>
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
                            
                            <input type="text" name="marca" class="texoMarca" value="<?php $marca ?>">
                            
                            <!-- <input type="radio" name="marca" id="havaianas" value="havaianas"
                            class="radioFiltro" <?php // echo checarFiltro($marca, 'havaianas'); ?>>
                            <label for="havaianas" class="buttonFiltroSelecao button">Havaianas</label> -->
                            
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
                    class="radioFiltro" <?php echo checarFiltro($genero, 'masculino'); ?>>
                    <label for="masculino" class="buttonFiltroSelecao button">Masculino</label>
                    
                    <input type="radio" name="genero" id="feminino" value="feminino" class="radioFiltro"
                    <?php echo checarFiltro($genero, 'feminino'); ?>>
                    <label for="feminino" class="buttonFiltroSelecao button">Feminino</label>
                    
                    <input type="radio" name="genero" id="unissex" value="unissex" class="radioFiltro"
                    <?php echo checarFiltro($genero, 'unissex'); ?>>
                    <label for="unissex" class="buttonFiltroSelecao button">Unissex</label>
                    
                    <input type="radio" name="genero" id="infantil" value="infantil" class="radioFiltro"
                    <?php echo checarFiltro($genero, 'infantil'); ?>>
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
            <input type="radio" name="cor" id="preto" value="preto" class="radioFiltro" <?php
                                    echo checarFiltro($cor, 'preto'); ?>>
                                    <label for="preto" class="buttonFiltroSelecao button">Preto</label>
                                    
                                    <input type="radio" name="cor" id="branco" value="branco" class="radioFiltro" <?php
                                    echo checarFiltro($cor, 'branco'); ?>>
                                    <label for="branco" class="buttonFiltroSelecao button">Branco</label>
                                    
                                    <input type="radio" name="cor" id="vermelho" value="vermelho" class="radioFiltro"
                                    <?php echo checarFiltro($cor, 'vermelho'); ?>>
                                    <label for="vermelho" class="buttonFiltroSelecao button">Vermelho</label>
                                    
                                    <input type="radio" name="cor" id="azul" value="azul" class="radioFiltro" <?php echo
                                        checarFiltro($cor, 'azul'); ?>>
                                    <label for="azul" class="buttonFiltroSelecao button">Azul</label>
                                    
                                    <input type="radio" name="cor" id="verde" value="verde" class="radioFiltro" <?php
                                    echo checarFiltro($cor, 'verde'); ?>>
                                    <label for="verde" class="buttonFiltroSelecao button">Verde</label>
                                    
                                    <input type="radio" name="cor" id="amarelo" value="amarelo" class="radioFiltro"
                                    <?php echo checarFiltro($cor, 'amarelo'); ?>>
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
                            <input type="number" id="minPrice" value="<?php echo $precoMaior; ?>" min="0"
                            max="90000" name="precoMaior">
                            <input type="number" id="maxPrice" value="<?php echo $precoMenor; ?>" min="0"
                            max="90000" name="precoMenor">
                        </div>
                    </div>
                </div>
            </div>
            <label for="aplicarFiltro" class="labelSubmitFiltro">Aplicar filtros</label>
            <input id="aplicarFiltro" type="submit" value="<?php echo $ordem ?>" class="submitFiltro"
            name="filtro" data-bs-dismiss="offcanvas" aria-label="Close">
            
            <label for="removerFiltro" class="labelSubmitFiltro">Remover filtros</label>
            <input id="removerFiltro" type="submit" class="submitFiltro" name="removeFiltro"
            data-bs-dismiss="offcanvas" aria-label="Close">
        </div>
    </div>
    
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight2"
    aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">Ordenar por</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body ordenarCorpo">
        <label for="maisBuscados"
        class="ordenarButton button <?php echo checarOrdenm($ordem, 'contador_cliques DESC'); ?> ">Mais
        buscados</label>
        <input id="maisBuscados" type="submit" value="contador_cliques DESC" name="filtro"
        class="ordenarInput">
        
        <label for="maiorPreco"
        class="ordenarButton button <?php echo checarOrdenm($ordem, 'preco DESC'); ?> ">Maior
        preço</label>
        <input id="maiorPreco" type="submit" value="preco DESC" placeholder="Maior preço" name="filtro"
        class="ordenarInput">
        
        <label for="menorPreco"
        class="ordenarButton button <?php echo checarOrdenm($ordem, 'preco ASC'); ?>">Menor
        preço</label>
        <input id="menorPreco" type="submit" value="preco ASC" placeholder="Menor preço " name="filtro"
        class="ordenarInput">
        
    </div>
</div>
</form>
<!----------------------------- Telas laterias ----------------------------->

<!----------------------------- Produtos exibidos na página ----------------------------->
<div class="containerCards">
    <div id="results">
        
        </div>
    
    <?php

if (isset($_POST['filtro']) || isset($_GET['pagina']) || isset($_POST['removeFiltro']) || isset($_GET['categoria'])) {
    
    $querryDividida = explode('LIMIT', $_SESSION['sql']);
    $querryDividida[0] .= "LIMIT $offset, $num_items_por_pagina;";
    $_SESSION['sql'] = $querryDividida[0];
                // LIMIT $offset, $num_items_por_pagina;
            
                $resultado = mysqli_query($conn, $_SESSION['sql']);
                while ($linha = mysqli_fetch_assoc($resultado)) {
                    $nome = $linha['nome'];
                    $imagem = $linha['imagem'];
                    $preco = $linha['preco'];
                    $nomeLoja = $linha['nome_estabelecimento'];
                    $id = $linha['id'];


                    echo "
                <div class='card' onclick=\"location.href='produto.php?id=$id'\">
                    <div class='parteSuperiorCard'>
                        <img src='img/$imagem' alt='$nome'>
                        <form target='hiddenFrame' id='favoritar' action='restrito/favoritar.php' method='POST' onsubmit='event.stopPropagation()'>
                            <input type='hidden' name='idFavorito' value='$id'>
                            <input type='hidden' name='user' value='$user'>";

                    if (isset($_SESSION['idUser'])) {
                        $id_usuario = $_SESSION['idUser'];
                        $sql = "SELECT id_produto FROM usuario_favorita_produto WHERE id_usuario = $id_usuario AND id_produto = $id;";
                        $resultado2 = $conn->query($sql);
                        $numLinha = mysqli_num_rows($resultado2);
                        if ($numLinha === 1) {
                            echo "<button type='submit' value='Favoritar' name='favoritoSubmit'>
                                            <svg class='favoritaCoracao coracaoFavoritado' xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='red' class='bi bi-heart-fill' viewBox='0 0 16 16'>
                                            <path fill-rule='evenodd' d='M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314' />
                                            </svg>
                                        </button>";
                        } else {
                            echo "<button type='submit' value='Favoritar' name='favoritoSubmit' onclick='event.stopPropagation()'>
                                        <svg class='favoritaCoracao coracaoDesfavoritado' xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#004F90' class'' bi bi-heart' viewBox='0 0 16 16'>
                                            <path d='m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15' />
                                        </svg>
                                             <path fill-rule='evenodd' d='M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314' />
                                        </svg>
                                    </button>";
                        }

                    } else {
                        echo "<button type='submit' value='Favoritar' name='favoritoSubmit' onclick='event.stopPropagation()'>
                                <svg class='favoritaCoracao coracaoDesfavoritado' xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#004F90' class'' bi bi-heart' viewBox='0 0 16 16'>
                                    <path d='m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15' />
                                </svg>
                                     <path fill-rule='evenodd' d='M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314' />
                                </svg>
                            </button>";
                    }
                    echo "
                        </form>
                    </div>
                    <div class='parteInferiorCard'>
                        <h4>$nome</h4>
                        <h6>$nomeLoja</h6>
                        <h6>R$ $preco</h6>
                        <a class='btn-p4' href=''>Ver Produto</a>
                    </div>
                </div>";
                }
            } else {
                $_SESSION['sql'] = "SELECT p.id, p.nome, p.preco, p.imagem, l.nome_estabelecimento FROM produtos AS p JOIN lojistas as l ON p.id_lojista = l.id ORDER BY 'contador_cliques' LIMIT 0, 16;";


                $querryDividida = explode('LIMIT', $_SESSION['sql']);
                $querryDividida[0] .= "LIMIT $offset, $num_items_por_pagina;";
                $_SESSION['sql'] = $querryDividida[0];
                // LIMIT $offset, $num_items_por_pagina;
            
                $resultado = mysqli_query($conn, $_SESSION['sql']);
                while ($linha = mysqli_fetch_assoc($resultado)) {
                    $nome = $linha['nome'];
                    $imagem = $linha['imagem'];
                    $preco = $linha['preco'];
                    $nomeLoja = $linha['nome_estabelecimento'];
                    $id = $linha['id'];

                    echo "
                <div class='card' onclick=\"location.href='produto.php?id=$id'\">
                    <div class='parteSuperiorCard'>
                        <img src='img/$imagem' alt='$nome'>
                        <form target='hiddenFrame' id='favoritar' action='restrito/favoritar.php' method='POST' onsubmit='event.stopPropagation()'>
                            <input type='hidden' name='idFavorito' value='$id'>
                            <input type='hidden' name='user' value='$user'>";

                    if (isset($_SESSION['idUser'])) {
                        $id_usuario = $_SESSION['idUser'];
                        $sql = "SELECT id_produto FROM usuario_favorita_produto WHERE id_usuario = $id_usuario AND id_produto = $id;";
                        $resultado2 = $conn->query($sql);
                        $numLinha = mysqli_num_rows($resultado2);
                        if ($numLinha === 1) {
                            echo "<button type='submit' value='Favoritar' name='favoritoSubmit'>
                                            <svg class='favoritaCoracao coracaoFavoritado' xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='red' class='bi bi-heart-fill' viewBox='0 0 16 16'>
                                            <path fill-rule='evenodd' d='M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314' />
                                            </svg>
                                        </button>";
                        } else {
                            echo "<button type='submit' value='Favoritar' name='favoritoSubmit' onclick='event.stopPropagation()'>
                                        <svg class='favoritaCoracao coracaoDesfavoritado' xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#004F90' class'' bi bi-heart' viewBox='0 0 16 16'>
                                            <path d='m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15' />
                                        </svg>
                                             <path fill-rule='evenodd' d='M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314' />
                                        </svg>
                                    </button>";
                        }

                    } else {
                        echo "<button type='submit' value='Favoritar' name='favoritoSubmit' onclick='event.stopPropagation()'>
                                <svg class='favoritaCoracao coracaoDesfavoritado' xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#004F90' class'' bi bi-heart' viewBox='0 0 16 16'>
                                    <path d='m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15' />
                                </svg>
                                     <path fill-rule='evenodd' d='M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314' />
                                </svg>
                            </button>";
                    }
                    echo "
                        </form>
                    </div>
                    <div class='parteInferiorCard'>
                        <h4>$nome</h4>
                        <h6>Nome da Loja</h6>
                        <h6>R$ $preco</h6>
                        <a class='btn-p4' href='produto.php?id=$id'>Ver Produto</a>
                    </div>
                </div>";
                }
            }

            // Favoritando os produtos 
            if (isset($_POST['favoritoSubmit'])) {
                $idProduto = $_POST['idFavorito'];
                $sql = "SELECT id FROM usuario_favorita_produto WHERE id_usuario = $user AND id_produto = $idProduto";
                $resultado = $conn->query($sql);
                $numLinha = mysqli_num_rows($resultado);

                echo $sql;

                if ($numLinha > 0) {
                    $sql = "DELETE FROM usuario_favorita_produto WHERE id_usuario = $user AND id_produto = $idProduto;";
                    if ($conn->query($sql)) {
                        echo "<script>
                             alert('O produto não está mais na sua lista de favoritos');
                            </script>";
                    } else {
                        echo " <script>
                             alert('houve um problema para favoritar o produto. Tente novamente mais tarde');
                            </script>";
                    }
                } else {
                    $sql = "INSERT INTO usuario_favorita_produto (id_usuario, id_produto) VALUES ('$user', '$idProduto');";
                    if ($conn->query($sql)) {
                        echo "  <script>
                             alert('produto favoritado');
                            </script>";
                    } else {
                        echo " <script>
                             alert('houve um problema para favoritar o produto. Tente novamente mais tarde');
                            </script>";
                    }
                }
            }


            ?>

<iframe name="hiddenFrame" style="display:none;"></iframe> <!-- Iframe invisível -->

        </div>
        <!----------------------------- Produtos exibidos na página ----------------------------->

        <nav aria-label="Paginação">
            <ul class="pagination justify-content-center">
                <?php

                if (isset($_POST['filtro']) || isset($_POST['removeFiltro']) || isset($_GET['pagina']) || isset($_GET['categoria'])) {
                    // echo $sqlContadora;
                    $resultadoContador = mysqli_query($conn, $_SESSION['sqlContadora']);
                    $numeroLinhas = mysqli_fetch_assoc($resultadoContador);
                    $total_paginas = ceil($numeroLinhas['total'] / $num_items_por_pagina);

                    // Exibe links de páginação
                    for ($i = 1; $i <= $total_paginas; $i++) {
                        echo "<li class='page-item " . ($pagina_atual == $i ? 'active' : '') . "'><a class='page-link' href='produtosBusca.php?pagina=$i'>$i</a></li>";
                    }
                } else {
                    // echo $sqlContadora;
                    $_SESSION['sqlContadora'] = "SELECT COUNT(id) AS total FROM produtos AS p WHERE preco BETWEEN 0 AND 90000;";
                    $resultadoContador = mysqli_query($conn, $_SESSION['sqlContadora']);
                    $numeroLinhas = mysqli_fetch_assoc($resultadoContador);
                    $total_paginas = ceil($numeroLinhas['total'] / $num_items_por_pagina);

                    // Exibe links de páginação
                    for ($i = 1; $i <= $total_paginas; $i++) {
                        echo "<li class='page-item " . ($pagina_atual == $i ? 'active' : '') . "'><a class='page-link' href='produtosBusca.php?pagina=$i'>$i</a></li>";
                    }
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

    <!---------------------- Conteudo principal - MAIN ---------------------->
    <!-------------------------------AUTOCOMPLETE-------------------->
    <script>
        function searchCentral() {
            var query = document.getElementById('centralSearch').value;
            fetch('search.php?query=' + query)
                .then(response => response.json())
                .then(data => {
                    var results = document.getElementById('results');
                    results.innerHTML = '';

                    /*--------Criando os cards------------*/
                    data.forEach(item => {
                        var a = document.createElement('a');
                        a.classList.add('cartao');
                        a.href = 'produto.php?id=' + item.id; // Modificação para incluir o ID do produto na URL
                        a.innerHTML =
                            `<h6>${item.nome}</h6>
                        <img src='img/${item.foto}' class='list'>
                        <h6>R$ ${item.preco}</h6>`

                        results.appendChild(a);
                    });
                    /*--------Criando os cards------------*/
                });
        }

        // Função para autocomplete
        var entradaBusca = document.getElementById("centralSearch");
        var resultadosBusca = document.getElementById("resultadosBusca");

        entradaBusca.addEventListener("input", function () {
            var consulta = this.value;
            if (consulta.length > 0) {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "autocomplete.php?consulta=" + consulta, true);
                xhr.onload = function () {
                    if (xhr.status >= 200 && xhr.status < 400) {
                        var resposta = xhr.responseText;
                        if (resposta.trim() !== "") {
                            resultadosBusca.innerHTML = resposta;
                            resultadosBusca.style.display = "block";
                            resultadosBusca.style.padding = "1%";
                        } else {
                            resultadosBusca.style.display = "none";
                        }
                    }
                };
                xhr.send();
            } else {
                resultadosBusca.style.display = "none";
            }
        });

        resultadosBusca.addEventListener("click", function (event) {
            if (event.target.tagName === "P") {
                entradaBusca.value = event.target.textContent.trim();
                resultadosBusca.style.display = "none";
                searchWithFilters();
            }
        });

        entradaBusca.addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
                event.preventDefault();
                searchWithFilters();
            }
        });
    </script>
<!-------------------------------AUTOCOMPLETE-------------------->


<script>
    

        // Este código pega o valor da barra de pesquisa e o aplica em um input do tipo hidden no formulário
        var nomeInput = document.getElementById("nomeProduto");
        var nomeDigitado = document.getElementById("buscarInput");
        // nomeInput.value = nomeDigitado.value;
        function pegandoPesquisa() {
            // console.log(nomeDigitado.value);
            nomeInput.value = nomeDigitado.value;
        }


    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

</html>
<script src="script.js"></script>