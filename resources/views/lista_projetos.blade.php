@forelse($projetos as $key => $projeto)
    <div>{{json_encode($projeto)}}</div>
@empty
    <div>Não há projetos cadastrados</div>
@endforelse
