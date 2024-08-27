
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