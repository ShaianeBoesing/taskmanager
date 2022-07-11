<div class="modal fade bd-example-modal-lg" id="modal-detalhes-projeto" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" onclick="$('#modal-detalhes-projeto').modal('hide')" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <form class="align-items-center" id="form_tarefa">
                            <div class="form-row">
                                <div class="col-4">
                                    <input type="hidden" id="projeto_id" name="projeto_id" class="form-control"/>
                                    <div class="form-outline">
                                        <input type="text" id="nome" name="nome" class="form-control" placeholder="Nome da Tarefa"/>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-outline">
                                        <input type='date' id="inicio" name="inicio" class="form-control"
                                               min="<?= date('Y-m-d'); ?>" placeholder="Início"/>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-outline">
                                        <input type='date' id="fim" name="fim" class="form-control"
                                               min="<?= date('Y-m-d'); ?>" placeholder="Fim"/>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-outline">
                                        <button type="button" id="btn_salvar" class="btn btn-success" title="Adicionar Tarefa">
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Início</th>
                                    <th scope="col">Fim</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Ações</th>
                                </tr>
                                </thead>
                                <tbody id="tarefas">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
