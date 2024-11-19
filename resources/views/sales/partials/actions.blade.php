<a href="{{ route('ventas.generateInvoice', $id) }}" class="btn btn-warning btn-sm"
    title="Factura PDF" target="_blank">
    <i class="mdi mdi-file-pdf"></i>
</a>

<a href="{{ route('ventas.generateTicket', $id) }}" class="btn btn-success btn-sm"
    title="Factura Ticket" target="_blank">
    <i class="mdi mdi-file-pdf"></i>
</a>

<button type="button" class="btn btn-info btn-sm" onclick="viewRecord({{ $id }})">
    <i class="mdi mdi-eye"></i>
</button>
