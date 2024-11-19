<a href="{{ route('ordenes-trabajo.workorderpdf', $id) }}" class="btn btn-warning btn-sm"
    title="Orden de Trabajo PDF" target="_blank">
    <i class="mdi mdi-file-pdf"></i>
</a>

<a href="{{ route('ordenes-trabajo.workorderpos', $id) }}" class="btn btn-secondary btn-sm"
    title="Orden de Trabajo POS" target="_blank">
    <i class="mdi mdi-file-pdf"></i>
</a>

<button type="button" class="btn btn-info btn-sm" onclick="viewRecord({{ $id }})">
    <i class="mdi mdi-eye"></i>
</button>

<a href="{{ route('ordenes-trabajo.sendEmailWorkorderpdf', $id) }}" class="btn btn-success btn-sm">
    <i class="mdi mdi-email"></i>
</a>

<a href="{{ route('ordenes-trabajo.edit', $id) }}" class="btn btn-primary btn-sm">
    <i class="mdi mdi-square-edit-outline"></i>
</a>


