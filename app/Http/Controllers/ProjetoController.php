<?php

namespace App\Http\Controllers;

use App\Models\Projeto;
use App\Models\Tarefa;
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
        $projetos = $this->buscarProjetos();

        return view('lista_projetos', compact('projetos'));
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

            $response = response(['status' => 'success',
                'message' => 'Projeto criado com sucesso'
            ])->setStatusCode(200);

        } catch (\Exception $exception) {
            $message = 'Ocorreu uma exceção ao criar seu projeto';
            Log::error($message . $exception->getMessage());

            $response = response(['status' => 'error',
                'message' => $message
            ])->setStatusCode(500);

        } catch (\Error $error) {
            $message = 'Ocorreu um erro ao criar seu projeto';
            Log::error($message . $error->getMessage());

            $response = response(['status' => 'error',
                'message' => $message
            ])->setStatusCode(500);

        } finally {
            return $response;
        }
    }

    public function deletar($id)
    {
        try {
            $projeto = $this->buscarProjeto($id);
            $projeto->delete();

            return response(['status' => 'success',
                'message' => 'Projeto deletado com sucesso'
            ])->setStatusCode(200);

        } catch (\Throwable $exception) {
            $message = 'Ocorreu um erro ao deletar seu projeto';
            Log::error($message . $exception->getMessage());

            return response(['status' => 'error',
                'message' => $message
            ])->setStatusCode(500);
        }
    }

    public function editar($id, Request $data)
    {
        try {
            $data = $data->all();
            $projeto = $this->buscarProjeto($id);
            $projeto->update($data);

            $response = response(['status' => 'success',
                'message' => 'Projeto editado com sucesso'
            ])->setStatusCode(200);

        } catch (\Exception $exception) {
            $message = 'Ocorreu uma exceção ao editar seu projeto';
            Log::error($message . $exception->getMessage());

            $response = response(['status' => 'error',
                'message' => $message
            ])->setStatusCode(500);

        } catch (\Error $error) {
            $message = 'Ocorreu um erro ao editar seu projeto';
            Log::error($message . $error->getMessage());

            $response = response(['status' => 'error',
                'message' => $message
            ])->setStatusCode(500);

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
            $projetos[$projeto->getAttribute('id')] = ['nome' => $projeto->getAttribute('nome'),
                'inicio' => $projeto->getAttribute('inicio'),
                'fim' => $projeto->getAttribute('fim'),
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

            $response = response(['status' => 'success',
                'message' => 'Tarefa criada com sucesso'
            ])->setStatusCode(200);

        } catch (\Exception $exception) {
            $message = 'Ocorreu uma exceção ao criar sua tarefa';
            Log::error($message . $exception->getMessage());

            $response = response(['status' => 'error',
                'message' => $message
            ])->setStatusCode(500);

        } catch (\Error $error) {
            $message = 'Ocorreu um erro ao criar sua tarefa';
            Log::error($message . $error->getMessage());

            $response = response(['status' => 'error',
                'message' => $message
            ])->setStatusCode(500);

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

            $response = response(['status' => 'success',
                'message' => 'Tarefa deletada com sucesso'
            ])->setStatusCode(200);
        } catch (\Exception $exception) {
            $message = 'Ocorreu uma exceção ao deletar sua tarefa';
            Log::error($message . $exception->getMessage());

            $response = response(['status' => 'error',
                'message' => $message
            ])->setStatusCode(500);
        } catch (\Error $error) {
            $message = 'Ocorreu um erro ao deletar sua tarefa';
            Log::error($message . $error->getMessage());

            $response = response(['status' => 'error',
                'message' => $message
            ])->setStatusCode(500);
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

            $response = response(['status' => 'success',
                'message' => 'Tarefa editada com sucesso'
            ])->setStatusCode(200);
        } catch (\Exception $exception) {
            $message = 'Ocorreu uma exceção ao editar sua tarefa';
            Log::error($message . $exception->getMessage());

            $response = response(['status' => 'error',
                'message' => $message
            ])->setStatusCode(500);
        } catch (\Error $error) {
            $message = 'Ocorreu um erro ao editar sua tarefa';
            Log::error($message . $error->getMessage());

            $response = response(['status' => 'error',
                'message' => $message
            ])->setStatusCode(500);
        } finally {
            return $response;
        }
    }

}
