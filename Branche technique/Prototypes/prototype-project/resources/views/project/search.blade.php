@forelse ($projects as $index => $project)
<tr>
    <td>{{ $project->name }}</td>
    <td>{!! $project->description !!}</td>
    <td>{{ $project->start_date }}</td>
    <td>{{ $project->end_date }}</td>
    <td>
        <a href="{{ route('task.index' , $project->id) }}" class="btn btn-sm btn-primary">{{__('message.view')}}</a>
    </td>
    @role('project-leader')
    <td>
        <a href="{{ route('project.edit', $project) }}" class="btn btn-sm btn-default">
            <i class="fas fa-edit"></i>
        </a>
        <form method="POST" action="{{ route('project.destroy', $project) }}" style="display: inline-block;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i
                    class="fas fa-trash"></i></button>
        </form>
    </td>
    @endrole
</tr>
@empty
<tr>
    <td colspan="6">Projects not found.
        <a href="{{ route('project.create') }}" class="mx-1">{{__('message.add')}} Project</a>
    </td>
</tr>
@endforelse
<tr class="tr">
    @role('project-leader')
    <td>
        <div class="d-flex justify-content-between align-items-center p-2">
            <div class="d-flex align-items-center">
                <form action="{{ route('project.import') }}" method="post" enctype="multipart/form-data"
                    id="importForm">
                    @csrf
                    <label for="upload" class="btn btn-default btn-sm mb-0 font-weight-normal">
                        <i class="fa-solid fa-file-arrow-down"></i>
                        {{__('message.import')}}
                    </label>
                    <input type="file" id="upload" name="file" style="display:none;" onchange="submitForm()" />
                </form>
                <a href="{{ route('project.export') }}" class="btn  btn-default btn-sm mt-0 mx-2">
                    {{__('message.export')}}
                </a>
            </div>

        </div>
    </td>
    @endrole
    <td>
        {{$projects->links()}}
    </td>
</tr>