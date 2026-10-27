<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Produto::orderByDesc('id')->get();

        return view('index', compact('produtos'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'estoque' => 'required|integer|min:0',
            'imagem' => [
                'nullable',
                File::image()->max('2mb'),
            ],
        ]);

        if ($request->hasFile('imagem')) {
            $dados['imagem'] = $request->file('imagem')
                ->store('produtos', 'public');
        }

        Produto::create($dados);

        return redirect()->route('produtos.index')
            ->with('success', 'Produto cadastrado com sucesso!');
    }

    public function update(Request $request, Produto $produto)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'estoque' => 'required|integer|min:0',
            'imagem' => [
                'nullable',
                File::image()->max('2mb'),
            ],
        ]);

        $imagemAntiga = $produto->imagem;

        if ($request->hasFile('imagem')) {
            $dados['imagem'] = $request->file('imagem')
                ->store('produtos', 'public');
        }

        $produto->update($dados);

        if ($request->hasFile('imagem') && $imagemAntiga) {
            Storage::disk('public')->delete($imagemAntiga);
        }

        return redirect()->route('produtos.index')
            ->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Produto $produto)
    {
        $imagem = $produto->imagem;

        $produto->delete();

        if ($imagem) {
            Storage::disk('public')->delete($imagem);
        }

        return redirect()->route('produtos.index')
            ->with('success', 'Produto excluído com sucesso!');
    }
}
