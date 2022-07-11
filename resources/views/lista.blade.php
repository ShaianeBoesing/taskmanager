@extends('home')
@section('title', 'Meus Projetos')
@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5 mb-5">
            <div class="col-12">
                <div class="card ">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md">
                                <h5 class="card-title">Meus projetos</h5>
                            </div>
                            <div class="col-md-auto">
                                <form action="{{route('formulario')}}">
                                    <button type="submit" class="btn btn-primary">
                                        Novo
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Início</th>
                                    <th scope="col">Fim</th>
                                    <th scope="col">Finalizado</th>
                                    <th scope="col">Atrasado</th>
                                    <th scope="col">Ações</th>
                                </tr>
                                </thead>
                                <tbody id="projetos">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('modal')
@endsection
@section('scripts')
    <script>
        let regras = {
            debug: true,
            rules: {
                nome: 'required',
                inicio: 'required',
                fim: 'required',
            },
            messages: {
                nome: {
                    required: "<span style='color: red'>Informe o nome da tarefa</span>",
                },
                inicio: {
                    required: "<span style='color: red'>Informe a data inicial</span>",
                    min: "<span style='color: red'>Data deve ser maior que a atual</span>"
                },
                fim: {
                    required: "<span style='color: red'>Informe a data final</span>",
                    min: "<span style='color: red'>Data deve ser maior que a atual</span>"
                }
            }
        }

        $(document).ready(function () {
            carregaTabela();

        })

        function carregaTabela() {
            $.ajax({
                type: 'GET',
                url: '/projeto/lista',
                dataType: 'json',
                success: function (r) {
                    $("#projetos").html('')
                    $.each(r, function (key, value) {
                        $("#projetos").append(`<tr>
                        <td>${key}</td>
                        <td>${r[key].nome}</td>
                        <td>${r[key].inicio}</td>
                        <td>${r[key].fim ?? ''}</td>
                        <td>${r[key].percentagem + '%'}</td>
                        <td>${(r[key].atraso) ? 'Sim' : 'Não'}</td>
                        <td colspan="2">
                            <button class="btn-primary ver"
                                    data-name="${r[key].nome}"
                                    data-id=${key}>Tarefas</button>
                            <button class="btn-primary editar"
                                    data-id=${key}>Editar</button>
                            <button class="btn-primary excluir"
                                    data-id=${key}>Excluir</button>
                        </tr>`)
                    })
                }
            });
        }

        function carregaTabelaModal(projetoId) {
            console.log(projetoId)
            $.ajax({
                type: 'GET',
                url: `/tarefa/busca/${projetoId}`,
                success: function (r) {
                    $("#tarefas").html('')
                    $.each(r, function (key, value) {
                        $("#tarefas").append(`<tr>
                            <td>${key}</td>
                            <td>${value.nome}</td>
                            <td>${value.inicio}</td>
                            <td>${value.fim ?? ''}</td>
                            <td>
                                <input type="checkbox" name="status" id="status" data-id=${key} ${(value.status) ? 'checked' : 'title="Finalizar Tarefa"'} >
                            </td>
                            <td>
                                <button class="btn-primary excluir-tarefa"
                                data-projeto=${projetoId}
                                data-id=${key}>Excluir</button>
                            </td>
                            </tr>`)
                    })
                    $('#modal-detalhes-projeto').modal('show')
                },
            })
            return true;
        }

        $(document).on('click', '.ver', function () {
            let projetoId = $(this).data('id')
            let projetoNome = $(this).data('name')
            $("#projeto_id").val(projetoId)
            $(".modal-title").text('Tarefas - ' + projetoNome)
            carregaTabelaModal(projetoId)
        })

        $(document).on('click', '.editar', function () {
            let projetoId = $(this).data('id')
            location.href = '/projeto/formulario/' + projetoId
        })

        $(document).on('click', '.excluir', function () {
            let projetoId = $(this).data('id')
            $.ajax({
                type: 'DELETE',
                url: `/projeto/deletar/${projetoId}`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (r) {
                    notificacao(r, 'success')
                    carregaTabela()
                },
                error: function (r) {
                    notificacao(r.responseText, 'danger')
                }
            })
        })

        $(document).on('click', '#status', function () {
            let tarefaId = $(this).data('id')
            let status = 0
            if ($(this).is(":checked")) {
                status = 1
            }

            let dados = {
                status: status
            }
            $.ajax({
                type: 'PUT',
                url: `/tarefa/editar/${tarefaId}`,
                data: dados,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (r) {
                    notificacao(r, 'success')
                    carregaTabela();
                },
                error: function (r) {
                    notificacao(r.responseText, 'danger')
                },
            });

        })

        $("#btn_salvar").on("click", function () {
            let dados = $('#form_tarefa').serializeArray();
            form = $("#form_tarefa")
            form.validate(regras);
            if (form.valid()) {
                $.ajax({
                    type: 'POST',
                    url: '/tarefa/novo',
                    data: dados,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (r) {
                        notificacao(r, 'success')
                        carregaTabelaModal($("#projeto_id").val())
                        carregaTabela()
                        form[0].reset()
                    },
                    error: function (r) {
                        notificacao(r.responseText, 'danger')
                    },
                });
            }

        })

        $(document).on('click', '.excluir-tarefa', function () {
            let tarefaId = $(this).data('id')
            let projetoId = $(this).data('projeto')
            $.ajax({
                type: 'DELETE',
                url: `/tarefa/deletar/${tarefaId}`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (r) {
                    notificacao(r, 'success')
                    carregaTabelaModal(projetoId)
                    carregaTabela()
                },
                error: function (r) {
                    notificacao(r.responseText, 'danger')
                }
            })
        })


    </script>
@endsection
