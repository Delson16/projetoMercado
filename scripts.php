<?php

// Código referente ao login do usuário no sistema
if (isset($_POST['loginUsuario'])) {

    include_once "restrito/conexao.php";
    $conn = pegarConexao('usuario');

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $sql = "SELECT id, email, senha, nome FROM usuarios WHERE email = '$email'";
    $resultado = $conn->query($sql);
    $numLinha = mysqli_num_rows($resultado);

    if ($numLinha > 0) {
        $linha = mysqli_fetch_array($resultado);
        $senha = mysqli_real_escape_string($conn, $_POST['senha']);

        if (password_verify($senha, $linha['senha'])) {
            if(isset($_SESSION['idLojista'])){ session_unset(); }
            session_start();
            $_SESSION['idUser'] = $linha['id'];
            $_SESSION['nome'] = $linha['nome'];
            $resposta = ['status' => true, 'endereco' => 'restrito/usuario.php'];
        } else {
            $resposta = ['status' => false, 'msg' => 'E-mail ou senha incorreto. Tente novamente!'];
        }
    } else {
        $resposta = ['status' => false, 'msg' => 'E-mail ou senha incorreto. Tente novamente!'];
    }
    $conn->close();
    echo json_encode($resposta);
    exit();
}

// Código referente ao login do lojista no sistema
if (isset($_POST['loginLojista'])) {
    include_once "restrito/conexao.php";
    $conn = pegarConexao('lojista');

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $sql = "SELECT id, senha, nome FROM lojistas WHERE email = '$email'";
    $resultado = $conn->query($sql);
    $numLinha = mysqli_num_rows($resultado);

    if ($numLinha > 0) {
        $linha = mysqli_fetch_array($resultado);
        $senha = mysqli_real_escape_string($conn, $_POST['senha']);

        if (password_verify($senha, $linha['senha'])) {

            if(isset($_SESSION['idUser'])){ session_unset(); }
            session_start();
            $_SESSION['idLojista'] = $linha['id'];
            $_SESSION['nome'] = $linha['nome'];
            $resposta = ['status' => true, 'endereco' => 'restrito/lojistaLojista.php'];
        } else {
            $resposta = ['status' => false, 'msg' => 'E-mail ou senha incorreto. Tente novamente!'];
        }
    } else {
        $resposta = ['status' => false, 'msg' => 'E-mail ou senha incorreto. Tente novamente!'];
    }

    $conn->close();
    echo json_encode($resposta);
    exit();
}

// Código referente ao cadastro de usuário
if (isset($_POST['cadastroSubmit'])) {

    include_once "restrito/conexao.php";
    $conn = pegarConexao('usuario');

    $email = clear($conn, $_POST['email']);

    $sql = "SELECT id FROM usuarios WHERE email = '$email'";
    $resultado = mysqli_query($conn, $sql);
    $numLinha = mysqli_num_rows($resultado);

    if ($numLinha > 0) {
        $resposta = [
            'status' => false,
            'campos' => [[2, 'Este e-mail ja está em uso. Tente com outro!']]
        ];
    } else {

        $imagem = " ";
        $enderecoImagem = " ";

        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
            $fotoUsuario = salvarFoto($_FILES['imagem'], 'img/');
            if ($fotoUsuario == 0) {
                $resposta = [
                    'status' => false,
                    'campos' => [[1, 'Houve um erro no upload da imagem. Tente novamente mais tarde.']]
                ];
                $conn->close();
                echo json_encode($resposta);
                exit();
            } else if ($fotoUsuario == 1) {
                $resposta = [
                    'status' => false,
                    'campos' => [[1, 'O arquivo enviado é muito grande ou está em um formato incorreto. Por favor, envie uma imagem com até 2MB.']]
                ];
                $conn->close();
                echo json_encode($resposta);
                exit();
            } else {
                $imagem = ", imagem_usuario";
                $enderecoImagem = " , '" . $fotoUsuario . "'";
            }
        }

        $nome = clear($conn, $_POST['nome']);
        $endereco = clear($conn, $_POST['endereco']);
        $data = clear($conn, $_POST['data_nascimento']);
        $senha = clear($conn, $_POST['senha']);
        $senha = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nome, email, endereco, data_nascimento, senha $imagem) VALUES ('$nome', '$email', '$endereco', '$data', '$senha' $enderecoImagem)";

        try {
            $conn->query($sql);

            $sql = "SELECT id, nome FROM usuarios WHERE email = '$email'";
            $resultado = mysqli_query($conn, $sql);
            $linha = mysqli_fetch_array($resultado);
            if(isset($_SESSION['idLojista'])){ session_unset(); }

            session_start();
            $_SESSION['idUser'] = $linha['id'];
            $_SESSION['nome'] = $linha['nome'];
            $resposta = [
                'status' => true,
                'endereco' => 'restrito/usuario.php'
            ];
        } catch (\Throwable $th) {
            $resposta = [
                'status' => false,
                'campos' => [[5, 'Houve um problema no cadastro. Por favor, tente mais tarde.']]
            ];
        }
    }

    $conn->close();
    echo json_encode($resposta);
    exit();
}

// codigo referente ao cadastro do lojista
if (isset($_POST['cadastroLojistaSubmit'])) {

    include_once "restrito/conexao.php";
    $conn = pegarConexao('lojista');

    $email = clear($conn, $_POST['email']);
    $sql = "SELECT id FROM lojistas WHERE email = '$email'";
    $resultado = mysqli_query($conn, $sql);
    $numLinha = mysqli_num_rows($resultado);

    if ($numLinha > 0) {
        $resposta = [
            'status' => false,
            'campos' => [[3, 'Este e-mail ja está em uso. Tente com outro!']]
        ];
    } else {
        $fotoUsuario = salvarFoto($_FILES['imagemUsuario'], "img/");

        if ($fotoUsuario == 0) {
            $resposta = [
                'status' => false,
                'campos' => [[5, 'Houve um erro no upload da imagem. Tente novamente mais tarde.']]
            ];
        } else if ($fotoUsuario == 1) {
            $resposta = [
                'status' => false,
                'campos' => [[5, 'O arquivo enviado é muito grande ou está em um formato incorreto. Por favor, envie uma imagem com até 2MB.']]
            ];
        } else {

            $fotoEmpresa = salvarFoto($_FILES['imagemEmpresa'], "img/");

            if ($fotoEmpresa == 0) {
                $resposta = [
                    'status' => false,
                    'campos' => [[6, 'Houve um erro no upload da imagem. Tente novamente mais tarde.']]
                ];
            } else if ($fotoEmpresa == 1) {
                $resposta = [
                    'status' => false,
                    'campos' => [[6, 'O arquivo enviado é muito grande ou está em um formato incorreto. Por favor, envie uma imagem com até 2MB.']]
                ];
            } else {
                $endereco = CLEAR($conn, $_POST['endereco']);
                $senha = password_hash(CLEAR($conn, $_POST['senha']), PASSWORD_DEFAULT);
                $telefone = CLEAR($conn, $_POST['telefone']);
                $nome = CLEAR($conn, $_POST['nome']);
                $nomeEstabelecimento = CLEAR($conn, $_POST['nomeEstabelecimento']);

                $sql = "INSERT INTO lojistas (nome, nome_estabelecimento, endereco, email, senha, telefone, imagem_empresa, imagem_lojista) VALUES ('$nome', '$nomeEstabelecimento', '$endereco', '$email', '$senha', '$telefone', '$fotoEmpresa', '$fotoUsuario')";

                try {
                    $conn->query($sql);
                    $sql = "SELECT id, nome FROM lojistas WHERE email  = '$email'";
                    $resultado = mysqli_query($conn, $sql);
                    $linha = mysqli_fetch_array($resultado);
                    if(isset($_SESSION['idUser'])){ session_unset(); }
                    session_start();
                    $_SESSION['idLojista'] = $linha['id'];
                    $_SESSION['nome'] = $linha['nome'];

                    $resposta = [
                        'status' => true,
                        'endereco' => 'restrito/lojistaLojista.php'
                    ];
                } catch (\Throwable $th) {
                    $resposta = [
                        'status' => false,
                        'campos' => [[7, 'Houve um problema no cadastro. Por favor, tente mais tarde.']]
                    ];
                }
            }
        }
    }
    $conn->close();
    echo json_encode($resposta);
    exit();
}

// Função que elimina injeções sql e de script
function clear($conexao, $texto)
{
    $textoLimpo = mysqli_real_escape_string($conexao, $texto);
    $textoLimpo = htmlspecialchars($texto);
    return $textoLimpo;
}

// fução que valida e faz upload da imagem 
function salvarFoto($foto, $local)
{

    if (!($foto['error'])) {

        $nomeExtensao = explode('/', $foto['type']);

        // tamanho limite do arquivo 1.5mb. Deve ter a extensão image
        if ($foto['size'] <= 10000000) {

            $nomeFoto =  $foto['name'] . date('Y-m-d H:i:s');
            $nomeFoto = md5($nomeFoto) . "." . $nomeExtensao[1];

            move_uploaded_file($foto['tmp_name'], $local . $nomeFoto);

            return $nomeFoto;
        } else {
            // arquivo no formato incorreto ou muito grande
            return 1;
        }
    } else {
        // erro no upload de arquivo
        return 0;
    }
}

// Função responsavel por gerar os cards na página
function gerarCard($querrySql, $tipoUsuario)
{
    include_once "restrito/conexao.php";
    $conn = pegarConexao('usuario');
    $sql = $querrySql;
    try {

        if ($tipoUsuario === 'lojista') {
            while ($linha = mysqli_fetch_assoc($querrySql)) {
                $nome = $linha['nome'];
                $imagem = $linha['imagem'];
                $preco = $linha['preco'];
                $categoria = $linha['categoria'];
                $descricao = $linha['descricao'];
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
    
                    <button data-bs-toggle='modal' data-bs-target='#exampleModal3' >
                        <svg data-id='$id' data-nome='$nome' class='favoritaCoracao excluir coracaoFavoritado' xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='red' class='bi bi-trash3-fill' viewBox='0 0 16 16'>
                        <path d='M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5'/>
                        </svg>
                    </button>
    
                    <button data-bs-toggle='modal' data-bs-target='#staticBackdrop1' >
                        <svg data-id='$id' data-nome='$nome' data-categoria='$categoria' data-preco='$preco' data-descricao='$descricao' onclick='event.stopPropagation();' class='editaProduto editar' xmlns='http://www.w3.org/2000/svg' xml:space='preserve' width='100%' height='100%' version='1.1' style='shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd'
viewBox='0 0 500 500'
 xmlns:xlink='http://www.w3.org/1999/xlink'>
 <defs>
  <style type='text/css'>
   <![CDATA[
    .fil0 {fill:#303030}
    .fil2 {fill:#A4A4A4}
    .fil3 {fill:#BEBEBE}
    .fil6 {fill:#C82800}
    .fil1 {fill:#D8D8D8}
    .fil11 {fill:url(#id0)}
    .fil7 {fill:url(#id1)}
    .fil5 {fill:url(#id2)}
    .fil4 {fill:url(#id3)}
    .fil8 {fill:url(#id4)}
    .fil9 {fill:url(#id5)}
    .fil10 {fill:url(#id6)}
   ]]>
  </style>
  <linearGradient id='id0' gradientUnits='userSpaceOnUse' x1='290.314' y1='115.217' x2='387.79' y2='212.695'>
   <stop offset='0' style='stop-color:#383838'/>
   <stop offset='0.658824' style='stop-color:#606060'/>
   <stop offset='1' style='stop-color:#303030'/>
  </linearGradient>
  <linearGradient id='id1' gradientUnits='userSpaceOnUse' x1='362.589' y1='36.481' x2='463.521' y2='137.413'>
   <stop offset='0' style='stop-color:#ED2F00'/>
   <stop offset='0.25098' style='stop-color:#FFA28B'/>
   <stop offset='0.701961' style='stop-color:#C82800'/>
   <stop offset='1' style='stop-color:#FF3300'/>
  </linearGradient>
  <linearGradient id='id2' gradientUnits='userSpaceOnUse' x1='312.636' y1='85.812' x2='414.458' y2='187.634'>
   <stop offset='0' style='stop-color:#A4A4A4'/>
   <stop offset='0.4' style='stop-color:#686868'/>
   <stop offset='0.639216' style='stop-color:#F2F2F2'/>
   <stop offset='1' style='stop-color:#B1B1B1'/>
  </linearGradient>
  <linearGradient id='id3' gradientUnits='userSpaceOnUse' x1='342.33' y1='57.533' x2='442.736' y2='157.939'>
   <stop offset='0' style='stop-color:#D8D8D8'/>
   <stop offset='0.258824' style='stop-color:#E5E5E5'/>
   <stop offset='0.658824' style='stop-color:#8A8A8A'/>
   <stop offset='1' style='stop-color:#CBCBCB'/>
  </linearGradient>
  <linearGradient id='id4' gradientUnits='userSpaceOnUse' x1='248.229' y1='293.985' x2='205.915' y2='251.671'>
   <stop offset='0' style='stop-color:#17A2FF'/>
   <stop offset='1' style='stop-color:#008EED'/>
  </linearGradient>
  <linearGradient id='id5' gradientUnits='userSpaceOnUse' x1='245.038' y1='300.804' x2='274.258' y2='330.024'>
   <stop offset='0' style='stop-color:#0062A4'/>
   <stop offset='1' style='stop-color:#008EED'/>
  </linearGradient>
  <linearGradient id='id6' gradientUnits='userSpaceOnUse' x1='199.563' y1='249.336' x2='175.005' y2='224.778'>
   <stop offset='0' style='stop-color:#5DBEFF'/>
   <stop offset='1' style='stop-color:#17A2FF'/>
  </linearGradient>
 </defs>
 <g id='Layer_x0020_1'>
  <metadata id='CorelCorpID_0Corel-Layer'/>
  <path class='fil0' d='M28 450l-8 25c0,2 0,3 1,4 1,1 2,1 4,1l25 -8 -22 -22z'/>
  <path class='fil1' d='M67 339l37 7 -71 112c-1,2 -3,2 -5,1 -2,-1 -2,-2 -2,-4l41 -116z'/>
  <path class='fil2' d='M161 433l-7 -37 -112 71c-2,1 -2,3 -1,5 1,2 2,2 4,2l116 -41z'/>
  <path class='fil3' d='M42 467c37,-23 75,-47 112,-71l-50 -50c-24,38 -48,75 -71,112 -1,1 -3,4 0,7 3,3 5,5 9,2z'/>
  <polygon class='fil4' points='292,108 392,208 448,152 348,52 '/>
  <path class='fil5' d='M310 90l100 100 4 -4 -100 -100 -4 4zm8 -8l100 100 4 -4 -100 -100 -4 4zm8 -8l100 100 4 -5 -99 -99 -5 4z'/>
  <path class='fil6' d='M407 27l66 66c9,10 9,25 0,34l-8 8 -100 -100 8 -8c9,-9 24,-9 34,0z'/>
  <path class='fil7' d='M399 36l66 66c9,9 9,24 0,33l-17 17 -100 -100 16 -16c10,-10 25,-10 35,0z'/>
  <path class='fil8' d='M356 194l-50 -50 -201 201c-14,14 -13,36 0,50l0 0c14,13 36,14 50,0l201 -201z'/>
  <path class='fil9' d='M356 194l25 25 -201 201c-14,14 -30,19 -37,12l0 0c-7,-7 -2,-23 12,-37l201 -201z'/>
  <path class='fil10' d='M281 119l25 25 -201 201c-14,14 -30,19 -37,12l0 0c-7,-7 -2,-23 12,-37l201 -201z'/>
  <polygon class='fil11' points='292,108 392,208 381,219 281,119 '/>
 </g>
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
        } else {

            $resultado = $conn->query($sql);

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
                        <input type='hidden' name='idFavorito' value='$id'>";

                if (isset($_SESSION['idUser'])) {
                    $id_usuario = $_SESSION['idUser'];
                    echo "<input type='hidden' name='user' value='$id_usuario'>";
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
                    echo "<button class='buttonCoracao' type='submit' value='Favoritar' name='favoritoSubmit' onclick='event.stopPropagation()'>
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
        }
    } catch (\Throwable $th) {
        echo "ocorreu um erro no sistema. Não foi possivel executar a sql!";
    }
}


// Função responsável por aplicar a imagem de perfil do usuário no header
function imagemPerfilHeader()
{
    if (isset($_SESSION['idUser'])) {
        include_once "restrito/conexao.php";
        $conn = pegarConexao('usuario');

        $id = $_SESSION['idUser'];
        $sql = "SELECT imagem_usuario FROM usuarios WHERE id = $id;";
        $resultado = $conn->query($sql);
        $linha = mysqli_fetch_assoc($resultado);
        $imagemLogin = $linha['imagem_usuario'] ? ('img/' . $linha['imagem_usuario']) : "img/icons/profile.png";

        echo "<a href='restrito/usuario.php'> <img src='$imagemLogin' class='loginButton'> </a>";
        $conn->close();
    } else if (isset($_SESSION['idLojista'])) {
        include_once "restrito/conexao.php";
        $conn = pegarConexao('lojista');

        $id = $_SESSION['idLojista'];
        $sql = "SELECT imagem_lojista FROM lojistas WHERE id = $id;";
        $resultado = $conn->query($sql);
        $linha = mysqli_fetch_assoc($resultado);
        $imagemLogin = $linha['imagem_lojista'] ? ('img/' . $linha['imagem_lojista']) : "img/icons/profile.png";

        echo "<a href='restrito/lojistaLojista.php'> <img src='$imagemLogin' class='loginButton'> </a>";
        $conn->close();
    } else {
        echo "<img src='img/icons/profile.png' class='loginButton' data-bs-toggle='dropdown' aria-expanded='false'> <ul class='dropdown-menu'> <li> <a class='dropdown-item' style='cursor: pointer !important;' data-bs-toggle='modal' data-bs-target='#modalLoginUsuario'>Entrar como usuário</a> </li> <li> <a class='dropdown-item' style='cursor: pointer !important;' data-bs-toggle='modal' data-bs-target='#modalLoginLojista'>Entrar como Lojista</a> </li> </ul>";
    }
}

// Função responsavel por montar o dropdown com os produtos
function dropdownHeader()
{
    if (isset($_SESSION['idUser'])) {
        include_once "restrito/conexao.php";
        $conn = pegarConexao('usuario');
        $user = $_SESSION['idUser'];

        $sqlElementosFavoritosHeader = "SELECT p.id, p.nome, p.preco, p.imagem FROM produtos AS p JOIN usuario_favorita_produto AS ufp ON p.id = ufp.id_produto WHERE ufp.id_usuario = $user ORDER BY ufp.id DESC LIMIT 3; ";
        $resultado = $conn->query($sqlElementosFavoritosHeader);

        while ($linha = mysqli_fetch_assoc($resultado)) {
            $nome = $linha['nome'];
            $imagem = $linha['imagem'];
            $preco = $linha['preco'];
            $id = $linha['id'];
            echo " <li class='produtosNoHeader'><a href='produto.php?id=$id' class='dropdown-item d-flex'> <img src='img/$imagem' alt='$nome'> <div class= 'd-flex flex-column justify-content-center'> <h6>$nome</h6> <h6>R$ $preco</h6> </div> </a></li> ";
        }
        echo "<li><a class='dropdown-item' href='restrito/usuario.php'>Ver Todos</a></li>";
    } else {
        echo "<li data-bs-toggle='modal' data-bs-target='#modalLoginUsuario'><a class='dropdown-item' style='cursor: pointer !important;'>Logue-se como usuário para ver os favoritos</a></li>";
    }
}
