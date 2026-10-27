
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Administrativo - LojaOnline</title>

    <!-- Bootstrap 5 para estilizar a página. -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

    <!-- Cabeçalho da loja. -->
    <nav class="navbar bg-white shadow-sm">
        <div class="container">

            <a href="{{ route('loja.index') }}"
               class="navbar-brand fw-bold text-primary">
                LojaOnline
            </a>

            <a href="{{ route('loja.index') }}"
               class="btn btn-outline-secondary">
                Voltar à loja
            </a>

        </div>
    </nav>

    <!-- Centraliza o formulário de login na tela. -->
    <main class="container">

        <div class="row justify-content-center align-items-center"
             style="min-height: 80vh;">

            <div class="col-md-5 col-lg-4">

                <div class="card border-0 shadow rounded-4">

                    <div class="card-body p-4">

                        <h3 class="fw-bold text-center mb-2">
                            Área Administrativa
                        </h3>

                        <p class="text-muted text-center mb-4">
                            Entre com suas credenciais para gerenciar a loja.
                        </p>

                        <!-- Exibe erros de autenticação. -->
                        @if ($errors->any())

                            <div class="alert alert-danger">
                                {{ $errors->first() }}
                            </div>

                        @endif

                        <!-- Envia os dados para o LoginController. -->
                        <form action="{{ route('admin.login.entrar') }}"
                              method="POST">

                            <!-- Proteção CSRF obrigatória. -->
                            @csrf

                            <!-- E-mail do administrador. -->
                            <div class="mb-3">

                                <label for="email" class="form-label">
                                    E-mail
                                </label>

                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="form-control"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                >

                            </div>

                            <!-- Senha do administrador. -->
                            <div class="mb-4">

                                <label for="password" class="form-label">
                                    Senha
                                </label>

                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control"
                                    required
                                    autocomplete="current-password"
                                >

                            </div>

                            <!-- Botão para autenticar. -->
                            <button type="submit"
                                    class="btn btn-primary w-100 py-2">

                                Entrar no painel

                            </button>

                        </form>

                    </div>
                </div>

                <p class="text-center text-muted small mt-4">
                    Acesso exclusivo para administradores.
                </p>

            </div>
        </div>
    </main>

</body>
</html>
