<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class UserController extends Controller
{
    public function relatorio()
{
    $users = User::withCount('equipes')
        ->withSum('registrosPomodoros', 'tempo_foco_minutos')
        ->orderBy('name')
        ->get();

    // Projetos distintos em que cada usuário registrou foco
    foreach ($users as $user) {
        $user->projetos_count = $user->registrosPomodoros()
            ->with('tarefa:id,projeto_id')
            ->get()
            ->pluck('tarefa.projeto_id')
            ->filter()
            ->unique()
            ->count();
    }

    $pdf = Pdf::loadView('users.relatorio', [
        'users'    => $users,
        'geradoEm' => now(),
    ])->setPaper('a4', 'portrait');

    return $pdf->stream('relatorio-usuarios.pdf');
} 
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'telefone' => 'nullable|max:20',
            'cargo' => 'nullable|max:100',
            'status' => 'required|in:ativo,inativo',
            'imagem' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        //hash é pra ocultar a senha
        $validated['password'] = Hash::make($validated['password']);
        
        //armazenar imagem no reositório público
        if ($request->hasFile('imagem')) {
            $validated['imagem'] = $request->file('imagem')->store('users', 'public');
        }

        User::create($validated);

        return redirect()->route('users.index')
                         ->with('success', 'Usuário criado com sucesso!');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telefone' => 'nullable|max:20',
            'cargo' => 'nullable|max:100',
            'status' => 'required|in:ativo,inativo',
            'imagem' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:6|confirmed'
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('imagem')) {
            // remove imagem antiga
            if ($user->imagem && Storage::disk('public')->exists($user->imagem)) {
                Storage::disk('public')->delete($user->imagem);
            }
            $validated['imagem'] = $request->file('imagem')->store('users', 'public');
        }

        $user->update($validated);

        return redirect()->route('users.index')
                         ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {
            return redirect()->route('users.index')
                             ->with('error', 'Você não pode deletar seu próprio usuário!');
        }

        if ($user->imagem && Storage::disk('public')->exists($user->imagem)) {
            Storage::disk('public')->delete($user->imagem);
        }

        $user->delete();

        return redirect()->route('users.index')
                         ->with('success', 'Usuário excluído com sucesso!');
    }

    public function search(Request $request)
    {
        $query = User::query();

        if ($request->filled('valor')) {
            $query->where($request->tipo, 'like', "%{$request->valor}%");
        }

        $users = $query->get();
        return view('users.index', compact('users'));
    }

    // Gráfico de barras: minutos de foco (Pomodoro) trabalhados por usuário
    public function grafico()
    {
        $users = User::withSum('registrosPomodoros', 'tempo_foco_minutos')
            ->get()
            ->sortByDesc(fn ($user) => (int) $user->registros_pomodoros_sum_tempo_foco_minutos)
            ->values();

        $nomes = $users->pluck('name')->all();
        $minutos = $users->map(fn ($user) => (int) $user->registros_pomodoros_sum_tempo_foco_minutos)->all();
        $total = array_sum($minutos);

        $grafico = (new LarapexChart)->barChart()
            ->setTitle('Minutos trabalhados por usuário')
            ->addData($minutos, 'Minutos' )
            ->setXAxis($nomes)
            ->setColors(['#0d6efd']);

        return view('users.grafico', compact('grafico', 'nomes', 'minutos', 'total'));
    }
}