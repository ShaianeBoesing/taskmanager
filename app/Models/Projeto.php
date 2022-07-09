<?php

namespace App\Models;

use App\Models\Tarefa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projeto extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'inicio',
        'fim'
    ];

    /**
     * @var mixed
     */

    public function tarefas()
    {
        return $this->hasMany('App\Models\Tarefa');
    }

    public function emAtraso()
    {
        foreach ($this->tarefas as $tarefa) {
            if ($tarefa->getAttribute('fim') > $this->getAttribute('fim')) {
                return true;
            }
        }
        return false;
    }

    public function percentagemCompleto()
    {
        $total = count($this->tarefas);
        $totalCompleto = 0;
        $percentagem = 0;

        foreach ($this->tarefas as $tarefa) {
            if ($tarefa->getAttribute('status')) {
                $totalCompleto++;
            }
        }
        if ($totalCompleto > 0) {
            $percentagem = round(($totalCompleto / $total) * 100, 0);
        }

        return $percentagem;
    }

    public function criarTarefa($data)
    {
        $response = [];
        $tarefa = new Tarefa();
        $tarefa->projeto_id = $this->getAttribute('id');
        $tarefa->nome = $data['nome'];
        $tarefa->inicio = $data['inicio'];
        $tarefa->fim = '2022-09-12 00:00:00';
        $tarefa->status = false;
        if ($tarefa->save()) {
            return true;
        }
        return false;
    }

    public function deletarTarefa($tarefa)
    {
        if ($tarefa) {
            $tarefa->delete();
            return true;
        }
        return false;
    }

    public function editarTarefa($tarefa, $data)
    {
        if ($tarefa) {
            $tarefa->update($data);
            return true;
        }
        return false;
    }

    public function buscarTarefa($id)
    {
        foreach ($this->tarefas as $tarefa) {
            if ($tarefa->getAttribute('id') == $id) {
                return $tarefa;
            }
        }

        return null;
    }

    public function buscarTarefas()
    {
        $tarefas = [];

        foreach ($this->tarefas as $tarefa) {
            $tarefas[$tarefa->getAttribute('id')] = ['nome' => $tarefa->getAttribute('nome'),
                'projeto_id' => $tarefa->getAttribute('projeto_id'),
                'inicio' => $tarefa->getAttribute('inicio'),
                'fim' => $tarefa->getAttribute('fim'),
                'status' => $tarefa->getAttribute('status')];
        }

        return $tarefas;
    }

}
