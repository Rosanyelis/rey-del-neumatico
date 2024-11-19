<a href="{{ route('categories.productcategory', $id) }}" class="btn btn-info btn-sm"
    title="Productos de la Categoria" target="_blank">
    <i class="mdi mdi-file-pdf"></i>
</a>

<a href="{{ route('categorias.edit', $id) }}" class="btn btn-primary btn-sm">
    <i class="mdi mdi-square-edit-outline"></i>
</a>

<button type="button" class="btn btn-danger btn-sm" onclick="deleteRecord({{ $id }})" >
    <i class="mdi mdi-delete"></i>
</button>
