<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ClienteAuthController extends Controller
{
    /**
     * Exibe o formulário de cadastro.
     */
    public function mostrarCadastro()
    {
        // Usuários autenticados não precisam se cadastrar novamente.
        if (Auth::check()) {
            return redirect()->route('loja.index');
        }

        return view('auth.cliente-cadastro');
    }

    /**
     * Cadastra um novo cliente.
     */
    public function cadastrar(Request $request)
    {
        // Impede que uma conta autenticada crie outra conta
        // por meio deste formulário.
        if (Auth::check()) {
            return redirect()->route('loja.index');
        }

        // Valida os dados enviados pelo cliente.
        $dados = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Cria o usuário sem privilégios administrativos.
        $cliente = new User();

        $cliente->name = $dados['name'];
        $cliente->email = $dados['email'];
        $cliente->password = Hash::make($dados['password']);
        $cliente->is_admin = false;

        $cliente->save();

        // Autentica o cliente após o cadastro.
        Auth::login($cliente);

        // Regenera a sessão para maior segurança.
        $request->session()->regenerate();

        return redirect()->route('loja.index')
            ->with('success', 'Cadastro realizado com sucesso!');
    }

    /**
     * Exibe o formulário de login do cliente.
     */
    public function mostrarLogin()
    {
        if (Auth::check()) {
            return redirect()->route('loja.index');
        }

        return view('auth.cliente-login');
    }


    /**
     * Realiza o login de clientes e administradores.
     * Ambos são direcionados para a loja pública.
     */
    public function entrar(Request $request)
    {
        // Valida os dados enviados pelo formulário.
        $dados = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Autentica qualquer usuário com credenciais válidas.
        // O campo is_admin não é usado como restrição aqui.
        if (!Auth::attempt($dados)) {
            return back()
                ->withErrors([
                    'email' => 'E-mail ou senha inválidos.',
                ])
                ->onlyInput('email');
        }

        // Regenera a sessão após autenticar.
        $request->session()->regenerate();

        // Cliente e administrador visualizam a mesma loja.
        return redirect()->route('loja.index')
            ->with('success', 'Login realizado com sucesso!');
    }


    /**
     * Encerra a sessão do cliente.
     */
    public function sair(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('loja.index')
            ->with('success', 'Você saiu da sua conta.');
    }
}
