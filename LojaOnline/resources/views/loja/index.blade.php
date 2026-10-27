<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>LojaOnline - Loja</title>

    <!-- Importa os estilos do Bootstrap 5. -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

        <!-- Cabeçalho da LojaOnline -->
    <nav class="navbar bg-white shadow-sm">
        <div class="container">

            <!-- Logo da loja à esquerda -->
            <a class="navbar-brand fw-bold text-primary"
            href="{{ route('loja.index') }}">
                LojaOnline
            </a>

            <!-- Agrupa todos os botões à direita -->
            <div class="d-flex align-items-center justify-content-end gap-2 flex-wrap">

                {{-- Botões de autenticação --}}
                @guest

                    <!-- Abre o modal de login -->
                    <button type="button"
                            class="btn btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#modalLogin">
                        Entrar
                    </button>

                @else

                    {{-- Botão exclusivo do administrador --}}
                    @if (auth()->user()->is_admin)
                        <a href="{{ route('admin.index') }}"
                        class="btn btn-outline-primary">
                            Painel Admin
                        </a>
                    @endif

                    <!-- Nome do usuário autenticado -->
                    <span class="text-muted small">
                        Olá, {{ auth()->user()->name }}
                    </span>

                    <!-- Botão para encerrar a sessão -->
                    <form action="{{ route('cliente.sair') }}"
                        method="POST"
                        class="m-0">
                        @csrf

                        <button type="submit"
                                class="btn btn-outline-primary">
                            Sair
                        </button>
                    </form>

                @endguest

                <!-- Botão do carrinho -->
                <button type="button"
                        class="btn btn-outline-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#modalCarrinho">

                    Meu carrinho

                    <!-- Contador de produtos -->
                    <span id="contadorCarrinho"
                        class="badge bg-primary ms-1">
                        {{ collect(session('carrinho', []))->sum('quantidade') }}
                    </span>

                </button>

            </div>

        </div>
    </nav>

    <!-- Catálogo de produtos. -->
    <main class="container py-5">

        <h2 class="fw-bold">Bem-vindo à LojaOnline!</h2>

        <p class="text-muted">
            Confira os produtos disponíveis em nossa loja.
        </p>

        <div class="row g-4">

            {{-- Percorre os produtos enviados pelo LojaController. --}}
            @forelse($produtos as $produto)

                <div class="col-md-4">

                    <div class="card h-100 shadow-sm">

                        {{-- Imagem cadastrada pelo administrador. --}}
                        @if($produto->imagem)
                            <img
                                src="{{ asset('storage/' . $produto->imagem) }}"
                                class="card-img-top"
                                alt="{{ $produto->nome }}"
                                style="height: 220px; object-fit: contain;"
                            >
                        @endif

                        <div class="card-body">

                            <h5 class="card-title">
                                {{ $produto->nome }}
                            </h5>

                            <h4 class="text-success">
                                R$ {{ number_format($produto->preco, 2, ',', '.') }}
                            </h4>

                            <p class="text-muted">
                                {{ $produto->estoque }} unidades disponíveis
                            </p>

                            {{-- Abre os detalhes sem navegar para outra página. --}}
                            <button
                                type="button"
                                class="btn btn-primary w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#modalProduto{{ $produto->id }}">

                                Ver detalhes
                            </button>

                        </div>
                    </div>
                </div>

                {{-- Modal individual de detalhes do produto. --}}
                <div
                    class="modal fade"
                    id="modalProduto{{ $produto->id }}"
                    tabindex="-1"
                    aria-labelledby="tituloProduto{{ $produto->id }}"
                    aria-hidden="true">

                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 shadow">

                            {{-- Cabeçalho do modal. --}}
                            <div class="modal-header">

                                <h5
                                    class="modal-title fw-bold"
                                    id="tituloProduto{{ $produto->id }}">

                                    {{ $produto->nome }}
                                </h5>

                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Fechar">
                                </button>

                            </div>

                            {{-- Informações detalhadas do produto. --}}
                            <div class="modal-body p-4">

                                <div class="row g-4 align-items-center">

                                    <div class="col-md-5 text-center">

                                        @if($produto->imagem)

                                            <img
                                                src="{{ asset('storage/' . $produto->imagem) }}"
                                                alt="{{ $produto->nome }}"
                                                class="img-fluid rounded"
                                                style="max-height: 300px; object-fit: contain;"
                                            >

                                        @else

                                            <div class="bg-light rounded p-5 text-muted">
                                                Sem imagem disponível
                                            </div>

                                        @endif

                                    </div>

                                    <div class="col-md-7">

                                        <h4 class="fw-bold">
                                            {{ $produto->nome }}
                                        </h4>

                                        <p class="text-muted">
                                            {{ $produto->descricao ?: 'Sem descrição disponível.' }}
                                        </p>

                                        <h3 class="text-success fw-bold">
                                            R$ {{ number_format($produto->preco, 2, ',', '.') }}
                                        </h3>

                                        <p>
                                            <span class="badge bg-success">
                                                {{ $produto->estoque }} unidades disponíveis
                                            </span>
                                        </p>

                                        {{-- O JavaScript intercepta este formulário. --}}
                                        <form
                                            class="form-carrinho-ajax"
                                            action="{{ route('carrinho.adicionar', $produto->id) }}"
                                            method="POST">

                                            {{-- Proteção CSRF do Laravel. --}}
                                            @csrf

                                            <div class="mb-3">

                                                <label
                                                    for="quantidadeProduto{{ $produto->id }}"
                                                    class="form-label fw-semibold">

                                                    Quantidade
                                                </label>

                                                <input
                                                    type="number"
                                                    id="quantidadeProduto{{ $produto->id }}"
                                                    name="quantidade"
                                                    class="form-control"
                                                    value="1"
                                                    min="1"
                                                    max="{{ $produto->estoque }}"
                                                    required
                                                    style="max-width: 140px;"
                                                >

                                            </div>

                                            <button
                                                type="submit"
                                                class="btn btn-success w-100">

                                                Adicionar ao carrinho
                                            </button>

                                        </form>
                                        {{-- Botão Comprar agora - apenas visual, sem funcionalidade. --}}
                                        <button type="button"
                                                class="btn btn-primary w-100 mt-2"
                                                disabled>
                                            Comprar agora
                                        </button>

                                    </div>
                                </div>
                            </div>

                            {{-- Botão para fechar os detalhes. --}}
                            <div class="modal-footer">

                                <button
                                    type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">

                                    Continuar comprando
                                </button>

                            </div>

                        </div>
                    </div>
                </div>

            @empty

                <div class="col-12">
                    <p>Nenhum produto disponível no momento.</p>
                </div>

            @endforelse

        </div>
    </main>

    {{-- =====================================================
     MODAL DE LOGIN - CLIENTES E ADMINISTRADORES
     ===================================================== --}}

    <div class="modal fade"
        id="modalLogin"
        tabindex="-1"
        aria-labelledby="tituloModalLogin"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content rounded-4 border-0 shadow">

                <div class="modal-header border-0 pb-0">

                    <h5 class="modal-title fw-bold"
                        id="tituloModalLogin">
                        Entrar na LojaOnline
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Fechar">
                    </button>

                </div>

                <div class="modal-body p-4">

                    <p class="text-muted mb-4">
                        Acesse sua conta para continuar.
                    </p>

                    {{-- Utiliza a rota de login unificado. --}}
                    <form action="{{ route('cliente.entrar') }}"
                        method="POST">

                        @csrf

                        <div class="mb-3">

                            <label for="loginEmail" class="form-label">
                                E-mail
                            </label>

                            <input type="email"
                                id="loginEmail"
                                name="email"
                                class="form-control"
                                autocomplete="username"
                                required>

                        </div>

                        <div class="mb-4">

                            <label for="loginSenha" class="form-label">
                                Senha
                            </label>

                            <input type="password"
                                id="loginSenha"
                                name="password"
                                class="form-control"
                                autocomplete="current-password"
                                required>

                        </div>

                        <button type="submit"
                                class="btn btn-primary w-100">
                            Entrar
                        </button>

                    </form>

                    <div class="text-center mt-4">

                        <span class="text-muted">
                            Ainda não possui conta?
                        </span>

                        <a href="{{ route('cliente.cadastro') }}">
                            Criar conta
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         MODAL DO CARRINHO DE COMPRAS
         ===================================================== --}}

    <div
        class="modal fade"
        id="modalCarrinho"
        tabindex="-1"
        aria-labelledby="tituloCarrinho"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">

            <div class="modal-content border-0 shadow">

                <div class="modal-header">

                    <h5 class="modal-title fw-bold" id="tituloCarrinho">
                        Meu carrinho
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Fechar">
                    </button>

                </div>

                {{-- O JavaScript preencherá os produtos nesta área. --}}
                <div class="modal-body" id="conteudoCarrinho">
                    <p class="text-muted">Carregando carrinho...</p>
                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">

                        Continuar comprando
                    </button>

                    {{-- Checkout será desenvolvido posteriormente. --}}
                    <button type="button" class="btn btn-success" disabled>
                        Finalizar compra
                    </button>

                </div>

            </div>
        </div>
    </div>

    {{-- =====================================================
         NOTIFICAÇÕES TOAST
         ===================================================== --}}

    <div
        class="toast-container position-fixed bottom-0 end-0 p-3"
        style="z-index: 2000;">

        <div
            id="toastLoja"
            class="toast align-items-center text-bg-success border-0"
            role="alert"
            aria-live="polite"
            aria-atomic="true">

            <div class="d-flex">

                <div class="toast-body" id="mensagemToast"></div>

                <button
                    type="button"
                    class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"
                    aria-label="Fechar">
                </button>

            </div>
        </div>
    </div>

    {{-- Exibe notificações de sessões tradicionais também como Toast. --}}
    @php
        $mensagemInicial = session('error')
            ?: ($errors->any() ? $errors->first() : session('success'));

        $erroInicial = session()->has('error') || $errors->any();
    @endphp

    <!-- JavaScript do Bootstrap 5 para modais e Toasts. -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // =====================================================
        // CONFIGURAÇÕES DO CARRINHO
        // =====================================================

        // Utiliza as rotas geradas pelo Laravel.
        const urlCarrinho = @json(route('carrinho.index'));

        // __ID__ será substituído pelo ID do produto selecionado.
        const urlAtualizar = @json(route('carrinho.atualizar', ['produto' => '__ID__']));
        const urlRemover = @json(route('carrinho.remover', ['produto' => '__ID__']));

        // Token obrigatório para requisições que alteram dados.
        const csrfToken = @json(csrf_token());

        // Elementos utilizados para atualizar a interface.
        const modalCarrinho = document.getElementById('modalCarrinho');
        const areaCarrinho = document.getElementById('conteudoCarrinho');
        const contadorCarrinho = document.getElementById('contadorCarrinho');

        // Formata valores monetários em reais.
        function moeda(valor) {
            return Number(valor).toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });
        }

        // =====================================================
        // NOTIFICAÇÕES
        // =====================================================

        // Mostra uma notificação durante três segundos.
        function notificar(mensagem, erro = false) {

            const toast = document.getElementById('toastLoja');

            // Vermelho para erros e verde para operações concluídas.
            toast.classList.toggle('text-bg-danger', erro);
            toast.classList.toggle('text-bg-success', !erro);

            document.getElementById('mensagemToast').textContent = mensagem;

            bootstrap.Toast.getOrCreateInstance(toast, {
                delay: 3000,
                autohide: true
            }).show();
        }

        // =====================================================
        // CONSULTA DO CARRINHO
        // =====================================================

        // Busca os produtos atualizados na sessão do Laravel.
        async function carregarCarrinho() {

            const resposta = await fetch(urlCarrinho, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!resposta.ok) {
                throw new Error('Não foi possível carregar o carrinho.');
            }

            const dados = await resposta.json();

            // Atualiza o contador exibido no cabeçalho.
            contadorCarrinho.textContent = dados.quantidade_total;

            // Atualiza os produtos exibidos no modal.
            desenharCarrinho(dados);
        }

        // Cria elementos HTML com segurança.
        // textContent impede que o nome de um produto seja interpretado como HTML.
        function elemento(tag, classe = '', texto) {

            const el = document.createElement(tag);

            if (classe) {
                el.className = classe;
            }

            if (texto !== undefined) {
                el.textContent = texto;
            }

            return el;
        }

        // =====================================================
        // DESENHAR OS PRODUTOS DO CARRINHO
        // =====================================================

        function desenharCarrinho(dados) {

            // Remove o conteúdo anterior antes de reconstruir a lista.
            areaCarrinho.replaceChildren();

            // Exibe uma mensagem caso não existam produtos.
            if (!dados.itens.length) {

                areaCarrinho.appendChild(
                    elemento(
                        'p',
                        'text-center text-muted py-5 mb-0',
                        'Seu carrinho está vazio!'
                    )
                );

                return;
            }

            // Percorre todos os produtos retornados pelo Controller.
            for (const item of dados.itens) {

                // Linha principal de cada produto.
                const linha = elemento(
                    'div',
                    'row align-items-center g-3 border-bottom py-3'
                );

                // Coluna da imagem.
                const colunaImagem = elemento(
                    'div',
                    'col-3 col-md-2 text-center'
                );

                if (item.imagem_url) {

                    const imagem = elemento('img', 'img-fluid rounded');

                    imagem.src = item.imagem_url;
                    imagem.alt = item.nome;
                    imagem.style.maxHeight = '90px';

                    colunaImagem.appendChild(imagem);
                }

                // Coluna com nome e preço.
                const informacoes = elemento(
                    'div',
                    'col-9 col-md-4'
                );

                informacoes.append(
                    elemento('strong', 'd-block', item.nome),
                    elemento('span', 'text-success', moeda(item.preco))
                );

                // Coluna com ações de quantidade e remoção.
                const colunaAcoes = elemento(
                    'div',
                    'col-12 col-md-6'
                );

                // Formulário para atualizar a quantidade.
                const formulario = elemento(
                    'form',
                    'd-flex gap-2 flex-wrap align-items-center form-carrinho-ajax'
                );

                formulario.action = urlAtualizar.replace('__ID__', item.id);
                formulario.method = 'POST';

                // Laravel utiliza _method para reconhecer PATCH.
                const metodo = elemento('input');
                metodo.type = 'hidden';
                metodo.name = '_method';
                metodo.value = 'PATCH';

                // Campo para informar a nova quantidade.
                const quantidade = elemento('input', 'form-control');

                quantidade.type = 'number';
                quantidade.name = 'quantidade';
                quantidade.min = '1';
                quantidade.value = item.quantidade;
                quantidade.required = true;
                quantidade.style.width = '90px';


                // Adiciona somente o método PATCH e o campo de quantidade.
                // O botão Atualizar não será mais necessário.
                formulario.append(metodo, quantidade);

                // Atualiza o carrinho automaticamente quando o cliente
                // termina de alterar a quantidade do produto.
                quantidade.addEventListener('change', function () {

                    // Verifica se o campo contém um número inteiro válido.
                    if (!quantidade.checkValidity()) {
                        quantidade.reportValidity();
                        return;
                    }

                    // Envia a nova quantidade para o CarrinhoController.
                    // Reaproveita a função AJAX que já está funcionando.
                    enviarCarrinho(
                        formulario.action,
                        'POST',
                        new FormData(formulario)
                    );

                });

                // Botão para remover o produto do carrinho.
                const remover = elemento(
                    'button',
                    'btn btn-outline-danger btn-sm',
                    'Remover'
                );

                remover.type = 'button';

                remover.addEventListener('click', function() {
                    enviarCarrinho(
                        urlRemover.replace('__ID__', item.id),
                        'DELETE'
                    );
                });

                // Cria uma linha horizontal para organizar os controles.
                const linhaAcoes = elemento(
                    'div',
                    'd-flex align-items-center gap-2 flex-nowrap'
                );

                // Evita que o formulário ocupe toda a largura.
                formulario.className = 'd-flex align-items-center m-0';

                // Mantém o campo de quantidade com largura fixa.
                quantidade.style.width = '90px';

                // Coloca o campo de quantidade e o botão Remover lado a lado.
                linhaAcoes.append(formulario, remover);

                // Adiciona a linha de controles à coluna do produto.
                colunaAcoes.appendChild(linhaAcoes);

                // Adiciona as colunas à linha do produto.
                linha.append(colunaImagem, informacoes, colunaAcoes);

                // Adiciona o produto ao modal.
                areaCarrinho.appendChild(linha);
            }

            // Exibe o valor total da compra.
            const total = elemento(
                'div',
                'text-end fw-bold fs-5 mt-3',
                `Total: ${moeda(dados.total)}`
            );

            areaCarrinho.appendChild(total);
        }

        // =====================================================
        // ADICIONAR, ATUALIZAR E REMOVER PRODUTOS
        // =====================================================

        async function enviarCarrinho(url, metodo = 'POST', dadosFormulario = null) {

            // Usa os dados do formulário ou cria um formulário vazio.
            const dados = dadosFormulario || new FormData();

            // Adiciona o token CSRF quando necessário.
            if (!dados.has('_token')) {
                dados.append('_token', csrfToken);
            }

            // Simula PATCH ou DELETE usando um POST.
            if (metodo !== 'POST') {
                dados.set('_method', metodo);
            }

            try {

                // Envia a requisição sem recarregar a página.
                const resposta = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: dados
                });

                const resultado = await resposta.json();

                // Trata erros de validação ou estoque.
                if (!resposta.ok) {

                    const primeiroErro = resultado.errors
                        ? Object.values(resultado.errors)[0]?.[0]
                        : null;

                    throw new Error(
                        primeiroErro
                        || resultado.message
                        || 'Não foi possível atualizar o carrinho.'
                    );
                }

                // Mostra a mensagem de sucesso.
                notificar(resultado.message || 'Carrinho atualizado!');

                // Recarrega os dados do carrinho e o contador.
                await carregarCarrinho();

            } catch (erro) {

                // Exibe a mensagem de erro sem sair da página.
                notificar(erro.message, true);
            }
        }

        // =====================================================
        // INTERCEPTAÇÃO DOS FORMULÁRIOS
        // =====================================================

        // Intercepta formulários de adicionar e atualizar produtos.
        document.addEventListener('submit', function(evento) {

            const formulario = evento.target.closest(
                'form.form-carrinho-ajax'
            );

            if (!formulario) {
                return;
            }

            // Impede o redirecionamento tradicional do navegador.
            evento.preventDefault();

            // Envia os dados utilizando JavaScript.
            enviarCarrinho(
                formulario.action,
                'POST',
                new FormData(formulario)
            );
        });

        // Atualiza o carrinho sempre que o modal for aberto.
        modalCarrinho.addEventListener('show.bs.modal', function() {

            carregarCarrinho().catch(function(erro) {
                notificar(erro.message, true);
            });
        });

        // Atualiza o contador ao carregar a página.
        carregarCarrinho().catch(function() {
            // O contador inicial continua com o valor da sessão.
        });

        // Exibe mensagens tradicionais de sessão como Toast.
        const mensagemInicial = @json($mensagemInicial);
        const erroInicial = @json($erroInicial);

        if (mensagemInicial) {
            notificar(mensagemInicial, erroInicial);
        }
    </script>

</body>
</html>
