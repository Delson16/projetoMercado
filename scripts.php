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

            session_start();
            $_SESSION['idLojista'] = $linha['id'];
            $_SESSION['nome'] = $linha['nome'];
            $resposta = ['status' => true, 'endereco' => 'restrito/lojista.php'];
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
                    session_start();
                    $_SESSION['idLojista'] = $linha['id'];
                    $_SESSION['nome'] = $linha['nome'];

                    $resposta = [
                        'status' => true,
                        'endereco' => 'restrito/lojista.php'
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

function gerarCard($querrySql)
{
    include_once "restrito/conexao.php";
    $conn = pegarConexao('usuario');
    $sql = $querrySql;
    try {

        $resultado = $conn->query($sql);

            while ($linha = mysqli_fetch_assoc($resultado)) {
                $nome = $linha['nome'];
                $imagem = $linha['imagem'];
                $imagem = "produtosExemplo.img";
                $preco = $linha['preco'];
                $nomeLoja = $linha['nome_estabelecimento'];
                $id = $linha['id'];


                echo "
                <div class='card' onclick=\"location.href='produto.php?id=$id'\">
                    <div class='parteSuperiorCard'>
                        <img src='img/produtos/$imagem' alt='$nome'>
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
    } catch (\Throwable $th) {
        echo "ocorreu um erro no sistema. Não foi possivel executar a sql!";
    }
}
