function favorita() {
    let coracoes = document.querySelectorAll('.favoritaCoracao');

    coracoes.forEach(coracao => {
        coracao.addEventListener('click', () => {
            let isFavorited = coracao.classList.contains('coracaoFavoritado');
            if (isFavorited) {
                //.outerHTML --> Substitui o conteúdo atual pelo novo HTML fornecido.
                coracao.outerHTML = `
                    <svg class="favoritaCoracao coracaoDesfavoritado" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#004F90" class="bi bi-heart" viewBox="0 0 16 16">
                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15" />
                    </svg>
                `;
            } else {
                coracao.outerHTML = `
                    <svg class="favoritaCoracao coracaoFavoritado" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-heart-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314" />
                    </svg>
                `;
            }
            favorita();
        });
    });
}
favorita();

function carrinhoFavoritosClicado() {
    let coracaoCarrinhoFavorito = document.querySelector('#favoritos');
    
    coracaoCarrinhoFavorito.addEventListener('click', (event) => {
        event.stopPropagation(); // Evita que o clique se propague para o documento
        if (coracaoCarrinhoFavorito.classList.contains('naoClicado')) {
            coracaoCarrinhoFavorito.classList.add('clicado');
            coracaoCarrinhoFavorito.classList.remove('naoClicado');
        } else {
            coracaoCarrinhoFavorito.classList.add('naoClicado');
            coracaoCarrinhoFavorito.classList.remove('clicado');
        }
    });

    document.addEventListener('click', () => {
        coracaoCarrinhoFavorito.classList.add('naoClicado');
        coracaoCarrinhoFavorito.classList.remove('clicado');
    });
}
carrinhoFavoritosClicado()

function reduzTexto() {
    let nomeProduto = document.querySelectorAll('.cartao h5')
    let nomeLoja = document.querySelectorAll('.cartao h6')
    nomeProduto.forEach(nome => {
        if (nome.textContent.length > 26) {
            nome.style.fontSize = '0.8rem';
        }
    })

    nomeLoja.forEach(nome => {
        if (nome.textContent.length > 26) {
            nome.style.fontSize = '0.8rem';
        }
    })

}
reduzTexto()

// Função para realizar requisições em php, executando funções pertinentes a formulários de login e cadastro. Este código recebe como parâmetro o id do formulário que será utilizado na requisição.
// Em caso de sucesso o usuário é enviado a uma página endereçada no código php e em caso de falha um classe aviso receberá um texto que deverá ser o feedback do usuário.
async function RequisicaoPhpLogin(formularioDesejado, event) {
    const formulario = document.getElementById(formularioDesejado);

    event.preventDefault();

    var resposta = {};

    try {
        const formDados = new FormData(formulario);

        const dados = await fetch('scripts.php', {
            method: 'POST',
            body: formDados
        });
        var resposta = await dados.json();

    } catch (error) {

        var resposta = {
            status: false,
            msg: "Houve um erro na sua requisição. Tente novamente mais tarde."
        };

    } finally {
        if (resposta['status']) {
            window.location.href = resposta['endereco'];
        } else {
            const aviso = formulario.getElementsByClassName('aviso')[0];
            aviso.innerHTML = "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#FF0000' class='bi bi-exclamation-circle' viewBox='0 0 16 16'> <path d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16'/> <path d='M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z'/> </svg>" + resposta['msg'];

            const inputs = formulario.querySelectorAll('input');
            inputs.forEach((input, index) => {
                if (index < inputs.length - 1) {
                    input.style.borderColor = 'red';
                }
            })
        }
    }
}

// Fução semelhante a anterior, porém com um pouco mais de trabalho no retono de erros (devido a pré validação dos dados) e com códigos novos para auxiliar no upload de imagems.
async function RequisicaoPhpCadastro(formularioDesejado, event) {
    const formulario = formularioDesejado;

    event.preventDefault();

    // Fazer validações com o js e depois inserir o try cath no momento da requisição para o doc php. 

    const formDados = new FormData(formulario);
    var validacao = {
        status: false,
        campos: []
    };

    if (formDados.has('cadastroSubmit')) {
        validacao = validacaoCadastroUsuario(formDados);
    } else {
        validacao = validacaoCadastroLojista(formDados);
    }

    try {

        if (validacao.status) {

            const dados = await fetch('scripts.php', {
                method: 'POST',
                body: formDados
            });
            var resposta = await dados.json();

        } else {
            imprimirAvisosFormulario(formulario, validacao);
        }

    } catch (error) {

        validacao.campos.push([5, "Houve um problema na sua solicitação. Por favor, tente novamente mais tarde"]);

    } finally {

        if (resposta && resposta['status']) {
            window.location.href = resposta['endereco'];
        } else if (resposta) {
            imprimirAvisosFormulario(formulario, resposta);
        }

    }

}

// Fuções que validam os campos de cadastro para usuário de lojista. Estas funções retornam um objeto contendo o indice dos campos invalidados e uma mensagem de erro.
function validacaoCadastroUsuario(formDados) {
    const dadosFormulario = formDados;
    const nome = dadosFormulario.get('nome');
    const senha = dadosFormulario.get('senha');
    const endereco = dadosFormulario.get('endereco');
    const dataNascimento = new Date(dadosFormulario.get('data_nascimento'));

    var validacao = {
        status: false,
        campos: []
    };

    // Variavel criada para validacao a existência de caracteres especiais ou números
    const regexNome = /^[a-zA-Z\s]{3,}$/;
    if (!(nome && nome.length >= 3 && regexNome.test(nome))) {
        validacao.campos.push([0, "Nome inválido. Seu nome deve ter ao menos três caracteres e não deve possuir números ou caracteres especiais"]);
    }

    // Regex retorna true na existência de caracteres maiúsculos, minúsculos e números
    const regexSenha = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/;
    if (!(senha && senha.length >= 8 && regexSenha.test(senha))) {
        validacao.campos.push([3, "A senha deve ter pelo menos 8 caracteres, incluindo pelo menos uma letra minúscula, uma letra maiúscula e um número. Símbolos são opcionais."]);
    }

    // Regex retorna true na existência de ao menos um caractere e um numero
    const regexEndereco = /^(?=.*[0-9])(?=.*[a-zA-Z]).*$/;
    if (!(endereco && endereco.length >= 8 && endereco.length <= 100 && regexEndereco.test(endereco))) {
        validacao.campos.push([4, "O endereço deve ter entre 8 e 100 caracteres, contendo pelo menos uma letra e um número. Verifique se você inseriu um endereço válido."])
    }

    // Pegando a data atual
    const hoje = new Date();

    // Pegando a idade da pessoas com base na data atual
    var idade = hoje.getFullYear() - dataNascimento.getFullYear();
    if (hoje.getMonth() < dataNascimento.getMonth() ||
        (hoje.getMonth() === dataNascimento.getMonth() && hoje.getUTCDate() < dataNascimento.getUTCDate())
    ) {
        idade--;
    }
    if (idade < 18) {
        validacao.campos.push([5, "Você deve ser maior de 18 anos para se cadastrar."])
    }

    validacao.status = validacao.campos.length === 0;

    return validacao;
}
function validacaoCadastroLojista(formDados) {
    const dadosFormulario = formDados;
    const nome = dadosFormulario.get('nome');
    const nomeEstabelecimento = dadosFormulario.get('nomeEstabelecimento');
    const senha = dadosFormulario.get('senha');
    const endereco = dadosFormulario.get('endereco');
    const telefone = dadosFormulario.get('telefone');

    var validacao = {
        status: false,
        campos: []
    };

    // Variavel criada para validacao a existência de caracteres especiais ou números
    const regexNome = /^[a-zA-Z\s]{3,}$/;
    if (!(nome && nome.length >= 3 && regexNome.test(nome))) {
        validacao.campos.push([0, "Nome inválido. Seu nome deve ter ao menos três caracteres e não deve possuir números ou caracteres especiais."]);
    }

    if (!(nomeEstabelecimento.length >= 3)) {
        validacao.campos.push([1, "Nome inválido. O nome do seu estabelecimento deve conter ao menos 3 caracteres."]);
    }

    // Regex retorna true na existência de ao menos um caractere e um numero
    const regexEndereco = /^(?=.*[0-9])(?=.*[a-zA-Z]).*$/;
    if (!(endereco && endereco.length >= 8 && endereco.length <= 100 && regexEndereco.test(endereco))) {
        validacao.campos.push([2, "O endereço deve ter entre 8 e 100 caracteres, contendo pelo menos uma letra e um número. Verifique se você inseriu um endereço válido."])
    }

    // Validação do telefone
    const regexTelefone = /^\(\d{2}\) \d{5}-\d{4}$/;
    if (!(regexTelefone.test(telefone))) {
        validacao.campos.push([4, "Número de telefone inválido. O formato correto é (51) 99999-9999."]);
    }

    // Regex retorna true na existência de caracteres maiúsculos, minúsculos e números
    const regexSenha = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/;
    if (!(senha && senha.length >= 8 && regexSenha.test(senha))) {
        validacao.campos.push([7, "A senha deve ter pelo menos 8 caracteres, incluindo pelo menos uma letra minúscula, uma letra maiúscula e um número. Símbolos são opcionais."]);
    }

    validacao.status = validacao.campos.length === 0;

    return validacao;
}

// Função que deixa o valor do input de telefone no formato correto para envio
function numeroTelefoneMascara(elemento) {
    const celular = elemento;
    var limparValor = celular.value.replace(/\D/g, "").substring(0, 11);

    var numerosArray = limparValor.split("");

    var numeroFormatado = "";

    if (numerosArray.length > 0) {
        numeroFormatado += `(${numerosArray.slice(0, 2).join("")})`;
    }
    if (numerosArray.length > 2) {
        numeroFormatado += ` ${numerosArray.slice(2, 7).join("")}`;
    }
    if (numerosArray.length > 7) {
        numeroFormatado += `-${numerosArray.slice(7, 11).join("")}`;
    }

    celular.value = numeroFormatado;
}

// Função que imprime os avisos de campos não validados no formulario
function imprimirAvisosFormulario(formulario, validacao) {
    var removerAvisos = formulario.querySelectorAll('.aviso');
    removerAvisos.forEach(removerAvisos => {
        if (removerAvisos) {
            removerAvisos.remove();
        }
    })

    var inputs = formulario.querySelectorAll('input');
    inputs.forEach(inputs => {
        inputs.style.borderColor = 'black';
    })

    for (let i = 0; i < validacao.campos.length; i++) {

        var inputFormulario = formulario.getElementsByTagName('input')[validacao.campos[i][0]];
        inputFormulario.style.borderColor = 'red';

        var campoAviso = formulario.getElementsByClassName('campoAviso')[validacao.campos[i][0]];

        var aviso = document.createElement('span');
        aviso.className = 'aviso';
        aviso.innerHTML = "<svg xmlns='http://www.w3.org/2000/svg' fill='#FF0000' class='bi bi-exclamation-circle' viewBox='0 0 16 16'> <path d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16'/> <path d='M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z'/> </svg>" + validacao.campos[i][1];

        campoAviso.insertAdjacentElement('afterend', aviso);
    }
}

// Função que exibe ou oculta a senha nos formulário
function mostrarSenha(elemento) {
    const olho = elemento;
    const senha = olho.nextElementSibling;

    if (senha.type == 'password') {
        senha.type = 'text';
        olho.setAttribute('src', 'img/icons/olhoaberto.png');
    } else {
        senha.type = 'password';
        olho.setAttribute('src', 'img/icons/olhofechado.png');
    }
}

// Função que permite que o botão opere como uma label para o input de imagem nos modais
function adicionarFoto(elemento) {
    const button = elemento;
    const imagemSeletor = button.previousElementSibling;
    imagemSeletor.click();
}

// Função que permite um previwe da foto que o usuário colocou no formulário.
function PreviewFoto(inputFile, imgPreview) {
    const inputImagem = inputFile;
    const imagemPreview = document.getElementById(imgPreview);

    const reader = new FileReader;
    reader.onload = function (event) {
        imagemPreview.src = event.target.result;
    }
    reader.readAsDataURL(inputImagem.files[0]);
}