<?php
session_start();
include_once '../validarLojista.php';
include_once 'conexao.php';
include '../scripts.php';

    ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = pegarConexao('lojista');
    if (
        isset($_POST['nome']) && 
        isset($_POST['categoria']) && 
        isset($_POST['preco']) && 
        isset($_FILES['imagem']) && 
        isset($_FILES['imagem2']) && 
        isset($_FILES['imagem3'])
    ) {
        $nome = clear($conn, $_POST['nome']);
        $categoria = clear($conn, $_POST['categoria']);
        $preco = clear($conn, $_POST['preco']);
        $descricao = clear($conn, $_POST['descricao']);
        $imagemEstabelecimento = clear($conn, $_POST['imagemEmpresa']);

        $imagens = ['imagem', 'imagem2', 'imagem3'];
        $nomesFotos = [];

        foreach ($imagens as $imagem) {
            $nomeFoto = salvarFoto($_FILES[$imagem], '../img/');
            if ($nomeFoto == 1) {
                echo "arquivo no formato incorreto ou muito grande ($imagem)";
                exit;
            } elseif ($nomeFoto == 0) {
                echo "erro no upload de arquivo ($imagem)";
                exit;
            }
            $nomesFotos[] = $nomeFoto;
        }

        // Insere os dados na tabela de produtos
        $SQL = "INSERT INTO produtos (nome, preco, categoria, imagem, imagem2, imagem3, id_lojista, descricao) 
                VALUES ('$nome', '$preco', '$categoria', '{$nomesFotos[0]}', '{$nomesFotos[1]}', '{$nomesFotos[2]}', '$user', '$descricao')";
        mysqli_query($conn, $SQL);
    } else {
        echo "Dados incompletos!";
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

$tipo_usuario = 'lojista';
$conn = pegarConexao($tipo_usuario);

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                    <form action="atualizaLojista.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="idUsuario" value=" <?php echo "$user" ?>">
                        <div class="mb-3">
                            <label for="nomeUsuario" class="form-label">Nome:</label>
                            <input type="text" class="form-control" id="nomeUsuario" name="nomeUsuario"
                                value="<?php echo "$nomeUsuario" ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="emailUsuario" class="form-label">Email:</label>
                            <input type="text" class="form-control" id="emailUsuario"
                                value="<?php echo "$emailUsuario" ?>" name="emailUsuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="enderecoUsuario" class="form-label">Endereço:</label>
                            <input type="text" class="form-control" id="enderecoUsuario"
                                value="<?php echo "$enderecoUsuario" ?>" name="enderecoUsuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone:</label>
                            <input type="text" class="form-control" id="telefone" value="<?php echo "$telefone" ?>"
                                name="telefone" required>
                        </div>
                        <div class="mb-3">
                            <label for="nomeEstabelecimento" class="form-label">Nome Estabelecimento:</label>
                            <input type="text" class="form-control" id="nomeEstabelecimento"
                                value="<?php echo "$nomeEstabelecimento" ?>" name="nomeEstabelecimento" required>
                        </div>
                        <div class="mb-3 d-flex" id="selecionaImagem">
                            <label for="imageminput" class="form-label">Selecione sua foto:</label>
                            <input type="file" id="imagemInput" class="form-control" name="imagem" accept="image/*"
                                style="display: none;">
                            <img src="../img/<?php echo "$imagemUsuario" ?>" alt="" id="imagemProduto"
                                onclick="document.getElementById('imagemInput').click();">
                            <br>
                            <br>
                        </div>
                        <div class="mb-3 d-flex" id="selecionaImagem">
                            <label for="imagemInput2" class="form-label">Selecione a imagem do seu
                                estabelecimento:</label>
                            <input type="file" id="imagemInput2" class="form-control" name="imagem_estabelecimento"
                                accept="image/*" style="display: none;">
                            <img src="../img/<?php echo "$imagemEstabelecimento" ?>" alt="" id="imagemProduto2"
                                onclick="document.getElementById('imagemInput2').click();">
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


    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                            <label for="imagem" class="form-label">Imagem Principal:</label>
                            <input type="file" class="form-control" class="imagem" name="imagem" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label for="imagem" class="form-label">Imagem Complementar:</label>
                            <input type="file" class="form-control" class="imagem" name="imagem2" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label for="imagem" class="form-label">Imagem Complementar:</label>
                            <input type="file" class="form-control" class="imagem" name="imagem3" accept="image/*" required>
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

    <!-- Modal de edição de produto -->

    <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edicão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form action="editaProduto.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="idProduto" value="">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Produto ou Serviço:</label>
                            <input type="text" class="form-control" id="nomeProdutoEdicao" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoria:</label>
                            <select class="form-select" id="categoria" name="categoria" required>
                                <option id="categoriaProduto"></option>
                                <option value="Eletronicos">Eletrônicos</option>
                                <option value="Roupas">Roupas</option>
                                <option value="Eletrodomesticos">Eletrodomésticos</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="preco" class="form-label">Preço:</label>
                            <input value="" type="number" class="form-control" id="precoProduto" name="preco" required>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição:</label>
                            <input value="" type="text" class="form-control" id="descricaoProduto" name="descricao"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="imagem" class="form-label">Imagem:</label>
                            <input value="" type="file" class="form-control" id="imagem" name="imagem" accept="image/*"
                                required>
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

    <!-- Modal de edição de produto -->

    <!-- Modal exclui Produto-->

    <form action="excluirScript.php" method="post" id="formExcluir">
        <input type="hidden" name="id" id="idProduto" value="1"> <!-- Use um valor fixo para testar -->
        <input type="submit" value="Excluir">
    </form>

    
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
                        <h3 ><?php echo "$nomeUsuario" ?></h3>
                </div>

                <div class="botoesLojista">
                    <a  data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <svg height="100%" width="40%" version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 64 64"
                            enable-background="new 0 0 64 64" xml:space="preserve" fill="#000000"
                            data-darkreader-inline-fill="" style="--darkreader-inline-fill: #000000;">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g>
                                    <path fill="#F9EBB2"
                                        d="M3.001,61.999c-0.553,0-1.001-0.446-1-0.999l0.001-13.141L16.143,62L3.001,61.999z"
                                        data-darkreader-inline-fill="" style="--darkreader-inline-fill: #433605;">
                                    </path>
                                    <path fill="#F76D57"
                                        d="M61.414,16.729l-4.259,4.259L43.013,6.845l4.258-4.257c0.782-0.782,2.049-0.782,2.829-0.002L61.414,13.9 C62.195,14.682,62.194,15.947,61.414,16.729z"
                                        data-darkreader-inline-fill="" style="--darkreader-inline-fill: #8e1a07;">
                                    </path>
                                    <g>
                                        <rect x="37.256" y="14.744"
                                            transform="matrix(0.7071 0.7071 -0.7071 0.7071 25.6812 -28.5106)"
                                            fill="#F9EBB2" width="20.001" height="4" data-darkreader-inline-fill=""
                                            style="--darkreader-inline-fill: #433605;"></rect>
                                    </g>
                                    <g>
                                        <rect x="-1.848" y="28.74"
                                            transform="matrix(0.7071 -0.7071 0.7071 0.7071 -15.6016 24.8148)"
                                            fill="#45AAB8" width="48.002" height="5.001" data-darkreader-inline-fill=""
                                            style="--darkreader-inline-fill: #34818c;"></rect>
                                        <rect x="8.76" y="39.348"
                                            transform="matrix(0.7071 -0.7071 0.7071 0.7071 -19.9956 35.4215)"
                                            fill="#45AAB8" width="48" height="4.999" data-darkreader-inline-fill=""
                                            style="--darkreader-inline-fill: #34818c;"></rect>
                                        <rect x="3.456" y="33.544"
                                            transform="matrix(0.7071 -0.7071 0.7071 0.7071 -17.7985 30.1183)"
                                            fill="#45AAB8" width="48.001" height="5.999" data-darkreader-inline-fill=""
                                            style="--darkreader-inline-fill: #34818c;"></rect>
                                    </g>
                                    <rect x="-1.847" y="28.74"
                                        transform="matrix(-0.7071 0.7071 -0.7071 -0.7071 59.9084 37.6651)" opacity="0.2"
                                        fill="#231F20" width="48.001" height="5" data-darkreader-inline-fill=""
                                        style="--darkreader-inline-fill: #181a1b;"></rect>
                                    <rect x="30.26" y="17.847"
                                        transform="matrix(0.7071 0.7071 -0.7071 0.7071 39.1859 -10.9078)" opacity="0.2"
                                        fill="#231F20" width="4.999" height="48" data-darkreader-inline-fill=""
                                        style="--darkreader-inline-fill: #181a1b;"></rect>
                                    <path fill="#394240"
                                        d="M62.828,12.486L51.514,1.172c-1.562-1.562-4.093-1.562-5.657,0.001c0,0-44.646,44.646-45.255,45.255 C-0.006,47.035,0,48,0,48l0.001,13.999c0,1.105,0.896,2,1.999,2.001h14c0,0,0.963,0.008,1.572-0.602s45.256-45.257,45.256-45.257 C64.392,16.579,64.392,14.05,62.828,12.486z M2.001,61v-1.583l2.582,2.582H3.001C2.448,61.999,2,61.553,2.001,61z M7.411,62 l-5.41-5.41l0.001-8.73L16.143,62H7.411z M52.912,25.23L38.771,11.088l-1.414,1.414l3.535,3.535L6.951,49.979l1.414,1.414 l33.94-33.941l4.243,4.243l-33.941,33.94l1.414,1.415l33.941-33.94l3.535,3.535L17.557,60.586L3.414,46.443L41.599,8.259 l14.143,14.143L52.912,25.23z M61.414,16.729l-4.259,4.259L43.013,6.845l4.258-4.257c0.782-0.782,2.049-0.782,2.829-0.002 L61.414,13.9C62.195,14.682,62.194,15.947,61.414,16.729z"
                                        data-darkreader-inline-fill="" style="--darkreader-inline-fill: #2c2f31;">
                                    </path>
                                </g>
                            </g>
                        </svg>
                    </a>
                    
                    <a  href="../logout.php">
                        <svg fill="#000000" height="100%" width="40%" version="1.1" id="Capa_1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            viewBox="0 0 384.971 384.971" xml:space="preserve" data-darkreader-inline-fill=""
                            style="--darkreader-inline-fill: #000000;" transform="matrix(-1, 0, 0, 1, 0, 0)">
                            <g id="SVGRepo_bgCa#ff0000#ff0000ie#ff0000" st#ff0000oke-width="0"></g>
                            <g id="SVGRepo_t#ff0000ace#ff0000Ca#ff0000#ff0000ie#ff0000"
                                st#ff0000oke-linecap="#ff0000ound" st#ff0000oke-linejoin="#ff0000ound"></g>
                            <g id="SVGRepo_iconCa#ff0000#ff0000ie#ff0000">
                                <g>
                                    <g id="Sign_Out">
                                        <path
                                            d="M180.455,360.91H24.061V24.061h156.394c6.641,0,12.03-5.39,12.03-12.03s-5.39-12.03-12.03-12.03H12.03 C5.39,0.001,0,5.39,0,12.031V372.94c0,6.641,5.39,12.03,12.03,12.03h168.424c6.641,0,12.03-5.39,12.03-12.03 C192.485,366.299,187.095,360.91,180.455,360.91z">
                                        </path>
                                        <path
                                            d="M381.481,184.088l-83.009-84.2c-4.704-4.752-12.319-4.74-17.011,0c-4.704,4.74-4.704,12.439,0,17.179l62.558,63.46H96.279 c-6.641,0-12.03,5.438-12.03,12.151c0,6.713,5.39,12.151,12.03,12.151h247.74l-62.558,63.46c-4.704,4.752-4.704,12.439,0,17.179 c4.704,4.752,12.319,4.752,17.011,0l82.997-84.2C386.113,196.588,386.161,188.756,381.481,184.088z">
                                        </path>
                                    </g>
                        </svg>
                    </a>
                </div>



            </div>
        </div>

        <div class="localizacaoContato">
            <div class="contatoInfo">
                <h2>Contatos</h2>
                <h5><?php echo "$emailUsuario" ?></h5>
                <a class="contatoWhats"
                    href="https://api.whatsapp.com/send/?phone=55<?php echo "$telefone" ?>&text&type=phone_number&app_absent=0">
                    <svg xmlns="http://www.w3.org/2000/svg"  fill="#4693ce" class="bi bi-whatsapp"
                        viewBox="0 0 16 16">
                        <path
                            d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232" />
                    </svg>
                    <h3><?php echo "$telefone" ?></h3>
                </a>
            </div>

            <div>
                <h2>Endereço:</h2>
                <h5><?php echo "$enderecoUsuario" ?></h5>
            </div>

            <div>
                <!--https://www.google.com/maps/dir//Av.+Adelino+Ferreira+Jardim+                 Sem endereco do usuario-->
                <!--https://www.google.com/maps/dir/Avenida+Farroupilha,+4545/Av.+Adelino+Ferreira+Jardim             Com endereco do usuario-->
                <a href="<?php echo "https://www.google.com/maps/dir//$enderecoUsuario" ?>">
                    <img src="../img/imgPgLojista/aa.png" alt="">
                </a>
            </div>

        </div>

        <h1 class="produtos">Seus Produtos</h1>
    </main>

    <div class="containerCards">
        <button type="button" class="adicionarItem" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            <svg width="200px" height="200px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 12H18M12 6V18" stroke="#f4cb00" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            <h2>Adicionar Produto</h2>
        </button>
        <?php
        gerarCard($result, $tipo_usuario)
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
        // Script para aparecer as ionformações durante a exclusão
        var buttonsExcluir = document.querySelectorAll('.excluir');
        buttonsExcluir.forEach(function (button) {
            button.addEventListener('click', function () {
                var id = button.getAttribute('data-id');
                var nome = button.getAttribute('data-nome');
                document.getElementById('idProduto').value = id;
                document.getElementById('nomeProduto').innerText = nome;
            });
});


        // Script para aparecer as informacoes na edição
        var buttonsEditar = document.querySelectorAll('.editar');
        buttonsEditar.forEach(function (button) {
            button.addEventListener('click', function () {
                var id = button.getAttribute('data-id');
                var nome = button.getAttribute('data-nome');
                var categoria = button.getAttribute('data-categoria');
                var preco = button.getAttribute('data-preco');
                var descricao = button.getAttribute('data-descricao');
                document.getElementById('idProduto').value = id;
                document.getElementById('nomeProdutoEdicao').value = nome;
                document.getElementById('categoriaProduto').textContent = categoria;
                document.getElementById('precoProduto').value = preco;
                document.getElementById('descricaoProduto').value = descricao;
            });
        });
    </script>

    <iframe name="hiddenFrame" style="display:none;"></iframe> <!-- Iframe invisível -->

    <script src="scriptLojista.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>