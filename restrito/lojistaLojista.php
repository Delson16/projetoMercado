<?php
session_start();
include_once '../validarLojista.php';
include_once 'conexao.php';
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['nome']) && isset($_POST['categoria']) && isset($_POST['preco']) && isset($_FILES['imagem'])) {
        $nome = clear($conn, $_POST['nome']);
        $categoria = clear($conn, $_POST['categoria']);
        $preco = clear($conn, $_POST['preco']);
        $descricao = clear($conn, $_POST['descricao']);
        $imagemEstabelecimento = clear($conn, $_POST['imagemEmpresa']);

        $nomeFoto = salvarFoto($_FILES['imagem'], '../img/');
        if ($nomeFoto == 1) {
            echo "arquivo no formato incorreto ou muito grande";
        }
        if ($nomeFoto == 0) {
            echo "erro no upload de arquivo";
        } else {
            $SQL = "INSERT INTO produtos (nome, preco,categoria , imagem, id_lojista, descricao) VALUES ('$nome', '$preco','$categoria' , '$nomeFoto', '$user', '$descricao')";
            mysqli_query($conn, $SQL);
        }
    }
}
?>

<?php
// Configuração da paginação
$num_items_por_pagina = 16; // Número de itens por página
$pagina_atual = isset($_GET['pagina']) ? $_GET['pagina'] : 1; // Página atual, padrão é 1
$offset = ($pagina_atual - 1) * $num_items_por_pagina; // Calcular o offset

// Consulta SQL com LIMIT e OFFSET para implementar a paginação
$sql = "SELECT *
        FROM produtos
        WHERE id_lojista = $user
        ORDER BY id DESC
        LIMIT $offset, $num_items_por_pagina;
";

$result = $conn->query($sql);

// Consulta SQl para aparecer os elementos favoritos no header
$sqlElementosFavoritosHeader = "SELECT id, nome, preco, categoria, imagem 
        FROM produtos 
        WHERE id_lojista = $user
        ORDER BY id DESC;
";
$resultado = $conn->query($sqlElementosFavoritosHeader);
?>

<?php

$sqlInfoUsuario = "SELECT * FROM lojistas WHERE id = $user";
$infoUsuarioResultado = $conn->query($sqlInfoUsuario);


while ($linhaUsuario = mysqli_fetch_assoc($infoUsuarioResultado)) {
    $nomeUsuario = $linhaUsuario['nome'];
    $emailUsuario = $linhaUsuario['email'];
    $enderecoUsuario = $linhaUsuario['endereco'];
    $imagemUsuario = $linhaUsuario['imagem_lojista'];
    $nomeEstabelecimento = $linhaUsuario['nome_estabelecimento'];
    $imagemEstabelecimento = $linhaUsuario['imagem_empresa'];
    $telefone = $linhaUsuario['telefone'];
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
        <link rel="shortcut icon" href="logoAmareloEscuro.png">
    <link rel="stylesheet" href="../pgPadrao.css">
    <link rel="stylesheet" href="pgLojista.css">
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="../img/img pg inicial/logoAmareloEscuro.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lojista Logado</title>
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
                <a href="../guiaDoLojista.php">Guia do lojista</a>
                <h9>|</h9>
                <a href="../Contato.php">Suporte</a>
            </div>
        </nav>

        <nav class="cabecalhoInferior">
            <a href="../index.php">
                <img src="../img/img pg inicial/logoAmareloEscuro.png" alt="" data-aos="zoom-in">
            </a>

            <form action="../produtosBusca.php" class="pesquisaCentral" method="POST">
                <input type="text" placeholder="Busque Seus Produtos" name="nome">
                <button type="submit" name="filtro" value="preco"><img src="../img/img pg padrao/lupa.png"
                        alt=""></button>
            </form>


            <div class="d-flex">
                <?php if (isset($_SESSION['idUser'])) {
                    include_once "conexao.php";
                    $id = $_SESSION['idUser'];
                    $sql = "SELECT imagem_usuario FROM usuarios WHERE id = $id;";
                    $resultado = $conn->query($sql);
                    $linha = mysqli_fetch_assoc($resultado);
                    $imagemLogin = $linha['imagem_usuario'] ? ('../img/' . $linha['imagem_usuario']) : "../imgs/profile.png";

                    echo "<a href='usuario.php'> <img src='$imagemLogin' class='loginButton' data-bs-toggle='modal'> </a>";
                } else if (isset($_SESSION['idLojista'])) {
                    include_once "conexao.php";
                    $id = $_SESSION['idLojista'];
                    $sql = "SELECT imagem_lojista FROM lojistas WHERE id = $id;";
                    $resultado = $conn->query($sql);
                    $linha = mysqli_fetch_assoc($resultado);
                    $imagemLogin = $linha['imagem_lojista'] ? ('../img/' . $linha['imagem_lojista']) : "../imgs/profile.png";

                    echo "<a href='lojistaLojista.php'> <img src='$imagemLogin' class='loginButton' data-bs-toggle='modal'> </a>";

                } else {
                    echo "<img src='../imgs/profile.png' class='loginButton' data-bs-toggle='modal'
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
                                    <li class='produtosNoHeader'><a href='../produto.php?id=$id' class='dropdown-item d-flex'> 
                                    <img src='../img/$imagem' alt='$nome'>
                                    <div class= 'd-flex flex-column justify-content-center'>
                                    <h6>$nome</h6>
                                    <h6>R$ $preco</h6>
                                    </div>
                                    </a></li>
                                    ";
                            }
                            echo "<li><a class='dropdown-item' href='usuario.php'>Ver Todos</a></li>";
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

    <!-- Modal de Edita lojista -->


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edição das Informações</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="idUsuario" value=" <?php echo "$user" ?>">
                        <div class="mb-3">
                            <label for="nomeUsuario" class="form-label">Nome:</label>
                            <input type="text" class="form-control" id="nomeUsuario" name="nomeUsuario" value="<?php echo "$nomeUsuario" ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="emailUsuario" class="form-label">Email:</label>
                            <input type="text" class="form-control" id="emailUsuario" value="<?php echo "$emailUsuario" ?>" name="emailUsuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="enderecoUsuario" class="form-label">Endereço:</label>
                            <input type="text" class="form-control" id="enderecoUsuario" value="<?php echo "$enderecoUsuario" ?>" name="enderecoUsuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone:</label>
                            <input type="text" class="form-control" id="telefone" value="<?php echo "$telefone" ?>" name="telefone" required>
                        </div>
                        <div class="mb-3">
                            <label for="nomeEstabelecimento" class="form-label">Nome Estabelecimento:</label>
                            <input type="text" class="form-control" id="nomeEstabelecimento" value="<?php echo "$nomeEstabelecimento" ?>" name="nomeEstabelecimento" required>
                        </div>
                        <div class="mb-3 d-flex" id="selecionaImagem">
                            <label for="imageminput" class="form-label">Selecione sua foto:</label>
                            <input type="file" id="imagemInput" class="form-control" name="imagem" accept="image/*" style="display: none;">
                            <img src="../img/<?php echo "$imagemUsuario" ?>" alt="" id="imagemProduto" onclick="document.getElementById('imagemInput').click();">
                            <br>
                            <br>
                        </div>
                        <div class="mb-3 d-flex" id="selecionaImagem">
                            <label for="imagemInput2" class="form-label">Selecione a imagem do seu estabelecimento:</label>
                            <input type="file" id="imagemInput2" class="form-control" name="imagem_estabelecimento" accept="image/*" style="display: none;">
                            <img src="../img/<?php echo "$imagemEstabelecimento" ?>" alt="" id="imagemProduto2" onclick="document.getElementById('imagemInput2').click();">
                            <br>
                            <br>
                        </div>
                        <div id="loginBotao">
                            <input type="submit" value="Salvar">
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


    <!-- Modal de Edita lojista -->

    <!-- Modal de cadastro de produto -->


    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastro de Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form action="lojistaLojista.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Produto ou Serviço:</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoria:</label>
                            <select class="form-select" id="categoria" name="categoria" required>
                                <option value="eletrônico">Eletrônicos</option>
                                <option value="roupa">Roupas</option>
                                <option value="eletrodoméstico">Eletrodomésticos</option>
                                <option value="cosmético">Cosméticos</option>
                                <option value="lanche">Lanches</option>
                                <option value="doce">Doces</option>
                                <option value="brinquedo">Brinquedos</option>
                                <option value="servico">Serviço</option>
                                <option value="jogo">Jogos</option>
                                <option value="utensílio">Utensílios</option>
                                <option value="acessório">Acessórios</option>
                                <option value="calçado">Calçados</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="preco" class="form-label">Preço:</label>
                            <input type="text" class="form-control" id="preco" name="preco" required>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição:</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" required>
                        </div>
                        <div class="mb-3">
                            <label for="imagem" class="form-label">Imagem:</label>
                            <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de cadastro de produto -->

    <!-- Modal de edição de produto -->

    <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form action="editaProduto.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Produto ou Serviço:</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoria:</label>
                            <select class="form-select" id="categoria" name="categoria" required>
                                <option value="Eletronicos">Eletrônicos</option>
                                <option value="Roupas">Roupas</option>
                                <option value="Eletrodomesticos">Eletrodomésticos</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="preco" class="form-label">Preço:</label>
                            <input type="number" class="form-control" id="preco" name="preco" required>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição:</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" required>
                        </div>
                        <div class="mb-3">
                            <label for="imagem" class="form-label">Imagem:</label>
                            <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de edição de produto -->

    <!-- Modal exclui Produto-->

    <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Excluir Produto</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="excluirScript.php" method="post" id="formExcluir" class="">
                        <h2>Deseja Excluir <b id="nomeProduto"></b>?</h2>
                        <div class="d-flex">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                            <input type="hidden" name="id" id="idProduto" value="">
                            <input style="width: 40%;" type="submit" class="btn btn-danger" value="Sim">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal exclui Produto-->

    <!-- Começo Do Conteúdo-->
    <main>

    <div class="fachadaLoja">
        <img src="../img/<?php echo "$imagemEstabelecimento" ?>" alt="">
    </div>
        
        <div>
            <h1>Informações e Configurações</h1>
            <div class="containerDasInfoIniciais">
            <?php $imagemEstabelecimento ?>

                <div class="d-flex" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <?php
                    if (!empty($imagemUsuario)) {
                        echo "<img src='../img/$imagemUsuario'";
                    } else {
                        echo "<img  src='../img/img pg padrao/profile1.png' alt=''>";
                    }
                    ?>
                    <br>
                    <div>
                        <h3 style='width:120%; padding-left: 20%'><?php echo "$nomeUsuario" ?></h3>
                    </div>
                </div>

                <a style="margin-top: -10%" class="btn btn-danger" href="../logout.php">Deslogar</a>


            </div>
        </div>
        
        <div class="localizacaoContato">
            <div class="contatoInfo">
                <h2>Contatos</h2>
                <h5><?php echo "$emailUsuario" ?></h5>
                <a class="contatoWhats btn-p3" href="https://api.whatsapp.com/send/?phone=55<?php echo "$telefone" ?>&text&type=phone_number&app_absent=0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#4693ce" class="bi bi-whatsapp" viewBox="0 0 16 16">
                    <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                    </svg>
                    <h3><?php echo "$telefone" ?></h3>
                </a>
            </div>

            <div>
                <h2>Endereço:</h2>
                <h5><?php echo "$enderecoUsuario" ?></h5>
            </div>

            <div> <!--https://www.google.com/maps/dir//Av.+Adelino+Ferreira+Jardim+                 Sem endereco do usuario-->
                <!--https://www.google.com/maps/dir/Avenida+Farroupilha,+4545/Av.+Adelino+Ferreira+Jardim             Com endereco do usuario-->
                <a href="<?php echo "https://www.google.com/maps/dir//$enderecoUsuario"  ?>">
                    <img src="../img/imgPgLojista/aa.png" alt="">
                </a>
            </div>

        </div>

        
        <h1 class="produtos">Seus Produtos</h1>
    </main>

    <div class="containerCards">
        <button type="button" class="adicionarItem" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            <svg width="200px" height="200px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 12H18M12 6V18" stroke="#f4cb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <h2>Adicionar Produto</h2>
        </button>
        <?php

        while ($linha = mysqli_fetch_assoc($result)) {
            $nome = $linha['nome'];
            $imagem = $linha['imagem'];
            $preco = $linha['preco'];
            $categoria = $linha['categoria'];
            $id = $linha['id'];
            echo "
    <div class='card' onclick=\"location.href='../produto.php?id=$id'\">
            <div class='parteSuperiorCard'>
            <img src='../img/$imagem' alt='$nome'>
                <input type='hidden' name='idFavorito' value='$id'>
                <input type='hidden' name='nomeProduto' data-nome='$nome'>
                <input type='hidden' name='nomeCategoria' data-nome='$nome'>
                <input type='hidden' name='nomeProduto' data-nome='$nome'>
                <input type='hidden' name='nomeProduto' data-nome='$nome'>

                <input type='hidden' name='user' value='$user'>
                <button data-bs-toggle='modal' data-bs-target='#exampleModal3' >
                    <svg data-id='$id' data-nome='$nome' class='favoritaCoracao excluir coracaoFavoritado' xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='red' class='bi bi-trash3-fill' viewBox='0 0 16 16'>
                    <path d='M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5'/>
                    </svg>
                </button>

                
                </div>
                <div class='parteInferiorCard'>
                <h4>$nome</h4>
                <h6>R$ $preco</h6>
                <a class='btn-p4' href=''>Ver Produto</a>
                </div>
                </div>";
        }
        ?>
    </div>

    <!-- Paginação -->
    <nav aria-label="Paginação">
        <ul class="pagination justify-content-center">
            <?php
            // Consulta para contar o total de produtos
            $sql_count = "SELECT COUNT(*) AS total FROM usuario_favorita_produto WHERE id_usuario = $user";
            $result_count = $conn->query($sql_count);
            $row_count = $result_count->fetch_assoc();
            $total_items = $row_count['total'];

            // Calcula o número total de páginas
            $total_paginas = ceil($total_items / $num_items_por_pagina);

            // Exibe links de páginação
            for ($i = 1; $i <= $total_paginas; $i++) {
                echo "<li class='page-item " . ($pagina_atual == $i ? 'active' : '') . "'><a class='page-link' href='usuario.php?pagina=$i'>$i</a></li>";
            }
            ?>
    </nav>

    <!------------------------------------------------FOOTER---------------------------------------------------->
    <footer>
        <div class="footerSuperior">
        </div>

        <div class="footerInferior">
            <div class="linksPaginasFooter">
                <div>
                    <h5>Comprar</h5>
                    <a href="../produtosBusca.php">Todos Produtos</a>
                    <a href="../guiaDoLojista.php">Anuncie Aqui</a>
                </div>

                <div>
                    <h5>Ajuda</h5>
                    <a href="../Contato.php">Contate-nos</a>
                    <a href="../guiaDoLojista.php">Guia do Lojista</a>
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


    <script>
        // Script para manipulação de botões de exclusão
        var buttonsExcluir = document.querySelectorAll('.excluir');
        buttonsExcluir.forEach(function(button) {
            button.addEventListener('click', function() {
                var id = button.getAttribute('data-id');
                var nome = button.getAttribute('data-nome');
                document.getElementById('idProduto').value = id;
                document.getElementById('nomeProduto').innerText = nome;
            });
        });
    </script>

    <iframe name="hiddenFrame" style="display:none;"></iframe> <!-- Iframe invisível -->

    <script src="scriptLojista.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>