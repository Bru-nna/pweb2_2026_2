<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Exibe o formulário de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Processa a tentativa de login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Bloqueia usuários marcados como inativos
            if (Auth::user()->status === 'inativo') {
                Auth::logout();

                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'Este usuário está inativo. Contate um administrador.']);
            }

            $request->session()->regenerate();

            return redirect()->intended(route('projetos.index'))
                             ->with('success', 'Login realizado com sucesso!');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'E-mail ou senha inválidos.']);
    }

    // Efetua logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
                         ->with('success', 'Você saiu do sistema.');
    }
}