<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto - LojaOnline</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">

        <h2 class="mb-4">Cadastrar Produto</h2>

        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('produtos.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" name="nome"
                               class="form-control"
                               value="{{ old('nome') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea name="descricao"
                                  class="form-control">{{ old('descricao') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Preço</label>
                        <input type="number" name="preco"
                               class="form-control"
                               step="0.01" min="0"
                               value="{{ old('preco') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Estoque</label>
                        <input type="number" name="estoque"
                               class="form-control"
                               min="0"
                               value="{{ old('estoque', 0) }}" required>
                    </div>

                    <button type="submit" class="btn btn-success">
                        Salvar Produto
                    </button>

                    <a href="{{ route('produtos.index') }}"
                    class="btn btn-secondary">
                        Cancelar
                    </a>
                </form>

                @if($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $erro)
                                <li>{{ $erro }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            </div>
        </div>

    </div>

</body>
</html>
