@props(['genre'])

<table class="edit-tab" data-endpoint="{{ route('admin.genres.show', $genre->id) }}">
    <tr>
        <th>ID:</th>
        <td>{{ $genre->id }}</td>
    </tr>
    <tr>
        <th>Nombre:</th>
        <td>{{ $genre->name }}</td>
    </tr>
    <tr>
        <th>Bio:</th>
        <td>{{ Str::limit($genre->bio, 50) }}</td>
    </tr>
    <tr>
        <th>Fecha de creación:</th>
        <td>{{ $genre->created_at }}</td>
    </tr>
    <tr>
        <th>Fecha de actualización:</th>
        <td>{{ $genre->updated_at }}</td>
    </tr>
</table>
