@extends('layouts.layout')
@extends('layouts.nav')
@section('content')
<div class="d-flex justify-content-between p-4">
    <h2>Liste des projets</h2>

    <div class="d-flex">
        <form action="" method="post">
            @csrf
            @method('POST')
            <button type="submit" class="btn btn-success">Exporter</button>
        </form>

        <form class="ml-4" action="" method="post" id="importForm"
            enctype="multipart/form-data">
            @csrf
            @method('POST')
            <input type="file" id="fileInputImporter" name="file" style="display: none;">
            <button type="button" class="btn btn-primary" id="chooseFileButtonImporter">Importer</button>
        </form>

    </div>
</div>
<div class="container">
    <div class="mt-4">
        @if(session('success'))
        <div class="alert alert-success">
            <p>{{session('success')}}</p>
        </div>
        @endif
    </div>
</div>
<div class="input-group">
    <input type="text" id="searchInput" class="form-control" placeholder="Rechercher">
</div>
<div class="ml-4 mb-4 mt-4 d-flex">
    <div class="form-group">
        <label>Filtre</label>
        <div class="d-flex">
        <select class="custom-select">
            <option value="" selected>Choisir le nom</option>
            @foreach($projets as $projet)
            <option value="{{ $projet->nom }}">{{ $projet->nom }}</option>
            @endforeach
        </select>
        <select class="custom-select ml-5">
            <option value="" selected>Sort</option>
            <option value="ancienne">Ancienne date</option>
            <option value="derniere">Derniere date</option>

        </select>
        </div>
       
    </div>

</div>
<div class="container">
    <table id="example2" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Nom de projet</th>
                <th>Description</th>
                <th>Date de debut</th>
                <th>Date de fin</th>
                <th>Task de projet</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            @if($projets->isEmpty())
            <tr>
                <td colspan="6">Non projet existe</td>
            </tr>
            @else
            @foreach($projets as $projet)
            <tr>
                <td>{{$projet->nom}}</td>
                <td>{!! $projet->description !!}</td>
                <td>{{$projet->date_debut}}</td>
                <td>{{$projet->date_fin}}</td>
                <td>
                    <a href="{{ route('show.task' , ['id' => $projet->id] ) }}" class="nav-link">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                </td>
                <td>
                    @can('update', $projet)
                    <form action="{{ route('edit.projet', $projet->id ) }}" method="post">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-success">Editer</button>
                    </form>
                    @endcan
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
    <div class="d-flex justify-content-center" id="pagination-links">
        {{ $projets->links() }}
    </div>
</div>

<script>
$(document).ready(function() {
    $('#searchInput').on('keyup', function() {
        var searchInput = $('#searchInput').val();
        console.log(searchInput);

        $.ajax({
            type: 'POST',
            url: '{{ route('projet.search') }}',
            data: {
                search: searchInput,
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                $('#tableBody').empty();
                $('#pagination-links').empty();
                console.log(response.data);
                response.data.forEach(function(projet) {
                    var projetId = projet.id;

                    var editLinkHref = editProjetLink(projetId);

                    var rowHtml = `
                        <tr>
                            <td>${projet.nom}</td>
                            <td>${projet.description}</td>
                            <td>${projet.date_debut}</td>
                            <td>${projet.date_fin}</td>
                            <td>
                                <a href="{{ route('show.task', ['id' => ':value']) }}"
                                   class="nav-link">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                            <td>
                                @can('update', $projet)
                                <form action="${editLinkHref}" method="post">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-success">Editer</button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    `;
                    $('#tableBody').append(rowHtml);
                });
                $('#pagination-links').append(response.links);
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
            }
        });
    });
});

function editProjetLink(projetId) {
    return `{{ route('edit.projet', ['id' => ':value']) }}`.replace(':value', projetId);
}
</script>

@endsection