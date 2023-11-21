@extends('layouts.app')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Taches de {{ $project ? $project->name : '' }} </h1>
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    <a href="{{ route('task.create', $project->id) }}" class="btn btn-sm btn-primary">Add Task</a>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('success') }}.
        </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header col-md-12">
                        <div class="d-flex justify-content-end align-items-center  p-0">
                            <div class="input-group input-group-sm col-md-3 p-0">
                                <input id="searchTask" type="text" class="form-control float-right"
                                    placeholder="Search">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0 table-tasks">
                        @include('task.table')
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function() {

    var id = "";

    function fetch_data(page, search) {
        var id = $('#id_project').val();
        $.ajax({
            url: "/projects/" + id + "/tasks?page=" + page + '&searchTask=' + search,
            success: function(data) {
                $('tbody').html('');
                $('tbody').html(data);

            }
        });


    }

    $('body').on('click', '.pagination a', function(param) {
        param.preventDefault();

        var page = $(this).attr('href').split('page=')[1];
        var search = $('#searchTask').val();

        fetch_data(page, search);
    });

    $('body').on('keyup', '#searchTask', function() {
        var search = $('#searchTask').val();
        var page = $('#page_hidden').val();

        fetch_data(page, search);
    })

   
    fetch_data(1, '');

});
</script>

@endsection