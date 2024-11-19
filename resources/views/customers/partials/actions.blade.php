<button type="button" class="btn btn-info btn-sm" onclick="viewRecord({{ $id }})">
    <i class="mdi mdi-eye"></i>
</button>

<a href="{{ route('clientes.edit', $id) }}" class="btn btn-primary btn-sm">
    <i class="mdi mdi-square-edit-outline"></i>
</a>

<button type="button" class="btn btn-danger btn-sm" onclick="deleteRecord({{ $id }})" >
    <i class="mdi mdi-delete"></i>
</button>
