<?php

namespace App\Http\Controllers;

use App\Models\Projeto;
use App\Models\Tarefa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjetoController extends Controller
{
    private $projetos;

    function __construct(Projeto $projetos)
    {
        $this->projetos = $projetos->all();
    }


    public function index()
    {
        return view('lista');
    }

    public function listar()
    {
        try {
            $projetos = $this->buscarProjetos();
        } catch (\Exception $exception) {
            $message = 'Ocorreu um problema ao carregar os dados';
            Log::error($message . $exception->getMessage());

            $projetos = [];

        }
        return $projetos;
    }

    public function formulario($id = null)
    {
        $projeto = [];
        if ($id) {
            $projeto = $this->buscarProjeto($id);
            $projeto->inicio = $projeto->getAttribute('inicio')?
                Carbon::parse($projeto->inicio)->format('Y-m-d'):'';
            $projeto->fim = $projeto->getAttribute('fim')?
                Carbon::parse($projeto->fim)->format('Y-m-d'):'';

        }
        return view('formulario', compact('projeto'));
    }

    public function novo(Request $data)
    {
        try {
            $data = $data->all();
            $projeto = new Projeto();

            $projeto->nome = $data['nome'];
            $projeto->inicio = $data['inicio'];
            $projeto->fim = $data['fim'];

            $projeto->save();

            $response = response('Projeto criado com sucesso')->setStatusCode(200);

        } catch (\Exception $exception) {
            $message = 'Não foi possível criar seu projeto';
            Log::error($message . $exception->getMessage());

            $response = response($message)->setStatusCode(500);

        } catch (\Error $error) {
            $message = 'Não foi possível criar seu projeto';
            Log::error($message . $error->getMessage());

            $response = response($message)->setStatusCode(500);

        } finally {
            return $response;
        }
    }

    public function deletar($id)
    {
        try {
            $projeto = $this->buscarProjeto($id);
            $projeto->delete();

            return response('Projeto deletado com sucesso')->setStatusCode(200);

        } catch (\Throwable $exception) {
            $message = 'Não foi possível deletar seu projeto';
            Log::error($message . $exception->getMessage());

            return response($message)->setStatusCode(500);
        }
    }

    public function editar($id, Request $data)
    {
        try {
            $data = $data->all();
            $projeto = $this->buscarProjeto($id);
            $projeto->update($data);

            $response = response('Projeto editado com sucesso')->setStatusCode(200);

        } catch (\Exception $exception) {
            $message = 'Não foi possível editar seu projeto';
            Log::error($message . $exception->getMessage());

            $response = response($message)->setStatusCode(500);

        } catch (\Error $error) {
            $message = 'Não foi possível editar seu projeto';
            Log::error($message . $error->getMessage());

            $response = response($message)->setStatusCode(500);

        } finally {
            return $response;
        }
    }

    public function buscarProjeto($id)
    {
        foreach ($this->projetos as $projeto) {
            if ($projeto->getAttribute('id') == $id) {
                return $projeto;
            }
        }

        return null;
    }

    public function buscarProjetos()
    {
        $projetos = [];

        foreach ($this->projetos as $projeto) {

            $fim = $projeto->getAttribute('fim')?Carbon::createFromFormat(
                'Y-m-d',
                $projeto->getAttribute('fim')
            )->format('d/m/Y'):'';

            $inicio = $projeto->getAttribute('inicio')?Carbon::createFromFormat(
                'Y-m-d',
                $projeto->getAttribute('inicio')
            )->format('d/m/Y'):'';

            $projetos[$projeto->getAttribute('id')] = ['nome' => $projeto->getAttribute('nome'),
                'inicio' => $inicio,
                'fim' => $fim,
                'atraso' => $projeto->emAtraso(),
                'percentagem' => $projeto->percentagemCompleto(),
                'tarefas' => $projeto->buscarTarefas()];
        }

        return $projetos;
    }

    public function novaTarefa(Request $data)
    {
        try {
            $data = $data->all();
            $projeto = $this->buscarProjeto($data['projeto_id']);

            $projeto->criarTarefa($data);

            $response = response('Tarefa criada com sucesso')->setStatusCode(200);

        } catch (\Exception $exception) {
            $message = 'Não foi possível criar esta tarefa';
            Log::error($message . $exception->getMessage());

            $response = response($message)->setStatusCode(500);

        } catch (\Error $error) {
            $message = 'Não foi possível criar esta tarefa';
            Log::error($message . $error->getMessage());

            $response = response($message)->setStatusCode(500);

        } finally {
            return $response;
        }

    }

    public function deletarTarefa($id)
    {
        try {
            $tarefa = Tarefa::where('id', $id)->first();
            $projeto_id = $tarefa->getAttribute('projeto_id');
            $projeto = $this->buscarProjeto($projeto_id);
            $projeto->deletarTarefa($tarefa);

            $response = response('Tarefa deletada com sucesso')->setStatusCode(200);
        } catch (\Exception $exception) {
            $message = 'Ocorreu uma exceção ao deletar sua tarefa';
            Log::error($message . $exception->getMessage());

            $response = response($message)->setStatusCode(500);
        } catch (\Error $error) {
            $message = 'Não foi possível deletar sua tarefa';
            Log::error($message . $error->getMessage());

            $response = response($message)->setStatusCode(500);
        } finally {
            return $response;
        }
    }

    public function editarTarefa($id, Request $data)
    {
        try {
            $data = $data->all();
            $tarefa = Tarefa::where('id', $id)->first();
            $projeto_id = $tarefa->getAttribute('projeto_id');
            $projeto = $this->buscarProjeto($projeto_id);
            $projeto->editarTarefa($tarefa, $data);

            $response = response('Tarefa editada com sucesso')->setStatusCode(200);
        } catch (\Exception $exception) {
            $message = 'Ocorreu uma exceção ao editar sua tarefa';
            Log::error($message . $exception->getMessage());

            $response = response($message)->setStatusCode(500);
        } catch (\Error $error) {
            $message = 'Não foi possível editar sua tarefa';
            Log::error($message . $error->getMessage());

            $response = response($message)->setStatusCode(500);
        } finally {
            return $response;
        }
    }

    public function buscarTarefas($projeto_id)
    {
        $tarefas = [];
        $projeto = $this->buscarProjeto($projeto_id);
        foreach ($projeto->tarefas as $tarefa) {

            $fim = $tarefa->getAttribute('fim')?Carbon::createFromFormat(
                'Y-m-d',
                $tarefa->getAttribute('fim')
            )->format('d/m/Y'):'';

            $inicio = $tarefa->getAttribute('inicio')?Carbon::createFromFormat(
                'Y-m-d',
                $tarefa->getAttribute('inicio')
            )->format('d/m/Y'):'';

            $tarefas[$tarefa->getAttribute('id')] = ['nome' => $tarefa->getAttribute('nome'),
                'inicio' => $inicio,
                'fim' => $fim,
                'status' => $tarefa->getAttribute('status'),
            ];
        }

        return $tarefas;

    }

}
