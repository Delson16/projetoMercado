function favorita() {
    let coracoes = document.querySelectorAll('.favoritaCoracao');

    coracoes.forEach(coracao => {
        coracao.addEventListener('click', (event) => {
            event.stopPropagation(); // Evita que o clique no coração ative o card
            
            let isFavorited = coracao.classList.contains('coracaoFavoritado');
            if (isFavorited) {
                //.outerHTML --> Substitui o conteúdo atual pelo novo HTML fornecido.
                coracao.outerHTML = `
                <svg data-id='$id' data-nome='$nome' class='favoritaCoracao excluir coracaoFavoritado' xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='red' class='bi bi-trash3-fill' viewBox='0 0 16 16'>
                    <path d='M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5'/>
                    </svg>
                `;
            } else {
                coracao.outerHTML = `
                <svg data-id='$id' data-nome='$nome' class='favoritaCoracao excluir coracaoFavoritado' xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='red' class='bi bi-trash3-fill' viewBox='0 0 16 16'>
                    <path d='M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5'/>
                    </svg>
                `;
            }
            favorita(); // Reaplica os eventos aos novos elementos
        });
    });
}

favorita();




function mostraSenha() {
    let olho = document.querySelector('.olho');
    let inputSenha = document.querySelector('#senhaL')

    olho.addEventListener('click', () => {
        if (olho.src.includes('olho.png')) {
            olho.setAttribute('src', 'img/img pg padrao/olho (1).png');
            inputSenha.setAttribute('type', 'text')
        }
        else {
            olho.setAttribute('src', 'img/img pg padrao/olho.png');
            inputSenha.setAttribute('type', 'password')
        }
    });
}
mostraSenha()

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

function usuarioLogadoClicado() {
    let usuarioLogado = document.querySelector('#pgsUsuario');
    
    usuarioLogado.addEventListener('click', (event) => {
        event.stopPropagation(); // Evita que o clique se propague para o documento
        if (usuarioLogado.classList.contains('naoClicadoPgsUsuario')) {
            usuarioLogado.classList.add('clicadoPgsUsuario');
            usuarioLogado.classList.remove('naoClicadoPgsUsuario');
        } else {
            usuarioLogado.classList.add('naoClicadoPgsUsuario');
            usuarioLogado.classList.remove('clicadoPgsUsuario');
        }
    });

    document.addEventListener('click', () => {
        usuarioLogado.classList.add('naoClicadoPgsUsuario');
        usuarioLogado.classList.remove('clicadoPgsUsuario');
    });
}
usuarioLogadoClicado()

function reduzTexto() {
    let nomeProduto = document.querySelectorAll('.parteInferiorCard h4');
    let nomeLoja = document.querySelectorAll('.parteInferiorCard h6');
    nomeProduto.forEach(nome => {
        if (nome.textContent.length > 18) {
            nome.style.fontSize = '0.9rem';
        }
    });

    nomeLoja.forEach(nome => {
        if (nome.textContent.length > 18) {
            nome.style.fontSize = '0.9rem';
        }
    });
}

function reduzTextoFavoritosHeader() {
    let nomeProduto = document.querySelectorAll('.parteInferiorCard h4');
    let nomeLoja = document.querySelectorAll('.parteInferiorCard h6');
    nomeProduto.forEach(nome => {
        if (nome.textContent.length > 18) {
            nome.style.fontSize = '0.9rem';
        }
    });

    nomeLoja.forEach(nome => {
        if (nome.textContent.length > 18) {
            nome.style.fontSize = '0.9rem';
        }
    });
}
    // Verifica se a tela é menor que 768px
    if (window.matchMedia("(max-width: 768px)").matches) {
    reduzTexto();
    reduzTextoFavoritosHeader();
}