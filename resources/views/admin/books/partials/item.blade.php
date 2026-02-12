<table class="edit-tab" data-id="{{ $record->id }}">
    <tr>
        <th>ID:</th>
        <td>{{ $record->id }}</td>
    </tr>
    <tr>
        <th>Título:</th>
        <td>{{ $record->title }}</td>
    </tr>
    <tr>
        <th>ISBN:</th>
        <td>{{ $record->isbn }}</td>
    </tr>
    <tr>
        <th>Sinopsis:</th>
        <td>{{ $record->description }}</td>
    </tr>
    <tr>
        <th>Fecha de creación:</th>
        <td>{{ $record->created_at }}</td>
    </tr>
    <tr>
        <th>Fecha de actualización:</th>
        <td>{{ $record->updated_at }}</td>
    </tr>
</table>
