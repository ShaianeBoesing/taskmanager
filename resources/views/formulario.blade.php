@extends('home')
@section('title', 'Formulário de Projeto')
@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md">
                                <h5 class="card-title">Novo Projeto</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="align-items-center" id="form_projeto">
                            <div class="form-row">
                                <input type="hidden" id="projeto_id" name="projeto_id"
                                       value="{{$projeto?$projeto->getAttribute('id'):''}}">
                                <div class="col-6">
                                    <div class="form-outline">
                                        <label class="form-label" for="nome">Nome</label>
                                        <input type="text"
                                               id="nome"
                                               name="nome"
                                               class="form-control"
                                               value="{{$projeto?$projeto->getAttribute('nome'):''}}"/>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-outline">
                                        <label class="form-label" for="inicio">Início</label>
                                        <input type='date'
                                               id="inicio"
                                               name="inicio"
                                               class="form-control"
                                               min="<?= date('Y-m-d'); ?>"
                                               value="{{$projeto?$projeto->getAttribute('inicio'):''}}"/>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-outline">
                                        <label class="form-label" for="fim" name="fim">Fim</label>
                                        <input type='date'
                                               id="fim"
                                               name="fim"
                                               class="form-control"
                                               min="<?= date('Y-m-d'); ?>"
                                               value="{{$projeto?$projeto->getAttribute('fim'):''}}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-auto">
                                    <button type="button" class="btn btn-secondary" onclick="location.href='/'">
                                        Voltar
                                    </button>
                                </div>
                                <div class="col">
                                    <button type="button" id="btn_salvar" class="btn btn-success">
                                        Salvar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                    required: "<span style='color: red'>Informe o nome do projeto</span>",
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

        $("#btn_salvar").on("click", function () {
            form = $("#form_projeto")
            form.validate(regras);
            if (form.valid()) {
                let projeto = $("#projeto_id").val();
                let dados = $('#form_projeto').serializeArray();
                if (projeto) {
                    editar(dados, projeto)
                } else {
                    criar(dados)
                }
            }
        })

        function criar(dados) {
            console.log('criar')
            $.ajax({
                type: 'POST',
                url: '/projeto/novo',
                data: dados,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (r) {
                    notificacao(r, 'success')
                },
                error: function (r) {
                    notificacao(r.responseText, 'danger')
                },
            });

        }

        function editar(dados, id) {
            console.log('edit')
            $.ajax({
                type: 'PUT',
                url: `/projeto/editar/${id}`,
                data: dados,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (r) {
                    notificacao(r, 'success')
                },
                error: function (r) {
                    notificacao(r.responseText, 'danger')
                },
            });

        }


    </script>
@endsection
