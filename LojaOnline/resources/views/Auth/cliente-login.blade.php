<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - LojaOnline</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar bg-white shadow-sm">
    <div class="container">
        <a href="{{ route('loja.index') }}"
           class="navbar-brand fw-bold text-primary">
            LojaOnline
        </a>

        <a href="{{ route('loja.index') }}"
           class="btn btn-outline-secondary">
            Continuar como visitante
        </a>
    </div>
</nav>

<main class="container">
    <div class="row justify-content-center align-items-center"
         style="min-height: 80vh;">

        <div class="col-md-5 col-lg-4">
            <div class="card border-0 shadow rounded-4">
                <div class="card-body p-4">

                    <h3 class="fw-bold text-center mb-2">
                        Entrar na LojaOnline
                    </h3>

                    <p class="text-muted text-center mb-4">
                        Acesse sua conta de cliente.
                    </p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('cliente.entrar') }}"
                          method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                E-mail
                            </label>

                            <input type="email"
                                   id="email"
                                   name="email"
                                   class="form-control"
                                   value="{{ old('email') }}"
                                   autocomplete="username"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">
                                Senha
                            </label>

                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-control"
                                   autocomplete="current-password"
                                   required>
                        </div>

                        <button type="submit"
                                class="btn btn-primary w-100 py-2">
                            Entrar
                        </button>
                    </form>

                    <p class="text-center mt-4 mb-0">
                        Ainda não tem conta?
                        <a href="{{ route('cliente.cadastro') }}">
                            Cadastre-se
                        </a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</main>

</body>
</html>
