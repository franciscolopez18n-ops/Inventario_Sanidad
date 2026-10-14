@props(['id', 'message'])

<dialog id="{{ $id }}" class="confirm-dialog">
    <p>{{ $message }}</p>
    <input type="button" class="btn btn-success" data-action="confirm" value="Sí">
    <input type="button" class="btn btn-danger" data-action="cancel" value="No">
</dialog>