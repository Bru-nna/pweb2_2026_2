<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarefa;
use App\Models\Projeto;
use App\Models\Categoria;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class TarefaController extends Controller
{
    public function index()
    {
        $tarefas = Tarefa::with(['projeto', 'categoria'])
            ->orderByRaw('data_vencimento IS NULL, data_vencimento ASC')
            ->get();
        return view('tarefas.index')->with(['tarefas' => $tarefas]);
    }

    function create()
    {
        $projetos = Projeto::orderBy('nome')->get();
        $categorias = Categoria::orderBy('nome')->get();
        return view('tarefas.create', compact('projetos', 'categorias'));
    }

    function validateForm(Request $request)
    {
        $request->validate([
            'titulo' => 'required',
            'prioridade' => 'required',
            'status' => 'required',
            'projeto_id' => 'required',
            'categoria_id' => 'required',
        ], [
            'titulo.required' => "O :attribute é obrigatorio",
            'prioridade.required' => "O :attribute é obrigatorio",
            'status.required' => "O :attribute é obrigatorio",
            'projeto_id.required' => "O :attribute é obrigatorio",
            'categoria_id.required' => "O :attribute é obrigatorio"
        ]);
    }

    function store(Request $request)
    {
        //dd($request->all());
        $this->validateForm($request);
        Tarefa::create($request->all());
        return redirect('tarefas')->with("success", 'Registro Salvo com sucesso!');
    }

    function edit($id)
    {
        $tarefa = Tarefa::find($id);
        $projetos = Projeto::orderBy('nome')->get();
        $categorias = Categoria::orderBy('nome')->get();
        return view('tarefas.edit', compact('tarefa', 'projetos', 'categorias'));
    }

    function update(Request $request, $id)
    {
        //dd($request->all());
        $this->validateForm($request);
        Tarefa::find($id)->update($request->all());
        return redirect('tarefas')->with("success", 'Registro Atualizado com sucesso!');
    }

    function destroy($id)
    {
        Tarefa::destroy($id);
        return redirect('tarefas')->with("success", 'Registro removido com sucesso!');
    }

    public function search(Request $request)
    {
        $query = Tarefa::with(['projeto', 'categoria'])
            ->orderByRaw('data_vencimento IS NULL, data_vencimento ASC');

        if (!empty($request->valor)) {
            $query->where($request->tipo, 'like', "%{$request->valor}%");
        }

        $tarefas = $query->get();
        return view('tarefas.index', compact('tarefas'));
    }

   
    function toggleStatus($id)
    {
        $tarefa = Tarefa::find($id);
        $tarefa->status = $tarefa->status === 'concluida' ? 'pendente' : 'concluida';
        $tarefa->save();

        return response()->json([
            'status' => $tarefa->status,
            'status_label' => ucfirst(str_replace('_', ' ', $tarefa->status)),
            'status_class' => match ($tarefa->status) {
                'concluida' => 'bg-success',
                'cancelada' => 'bg-danger',
                'em_andamento' => 'bg-warning',
                default => 'bg-secondary',
            },
        ]);
    }
        public function salvarPomodoro(Request $request)
    {
        $request->validate([
            'tempo_foco_minutos' => 'required|integer|min:1|max:240',
            'tarefa_id' => 'nullable|exists:tarefas,id'
        ]);

        \App\Models\RegistroPomodoro::create([
            'user_id' => auth()->id(),
            'tarefa_id' => $request->tarefa_id,
            'tempo_foco_minutos' => $request->tempo_foco_minutos,
        ]);

        return response()->json(['success' => true]);
    }

    // Gráfico de setores: tarefas concluídas, em andamento, pendentes e canceladas
    public function grafico()
    {
        $contagem = Tarefa::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');

        $status = [
            'concluida' => ['Concluídas', '#198754'],
            'em_andamento' => ['Em andamento', '#ffc107'],
            'pendente' => ['Pendentes', '#6c757d'],
            'cancelada' => ['Canceladas', '#dc3545'],
        ];

        $labels = [];
        $dados = [];
        $cores = [];
        foreach ($status as $chave => [$rotulo, $cor]) {
            $labels[] = $rotulo;
            $dados[] = (int) ($contagem[$chave] ?? 0);
            $cores[] = $cor;
        }

        $total = array_sum($dados);

        $grafico = (new LarapexChart)->pieChart()
            ->setTitle('Tarefas por status')
            ->addData($dados)
            ->setLabels($labels)
            ->setColors($cores);

        return view('tarefas.grafico', compact('grafico', 'labels', 'dados', 'cores', 'total'));
    }
}