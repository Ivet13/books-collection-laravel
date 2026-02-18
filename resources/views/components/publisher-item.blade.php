@props(['publisher'])

<table class="edit-tab" data-id="{{ $publisher->id }}">
    <tr>
        <th>ID:</th>
        <td>{{ $publisher->id }}</td>
    </tr>
    <tr>
        <th>Nombre:</th>
        <td>{{ $publisher->name }}</td>
    </tr>
    <tr>
        <th>Bio:</th>
        <td>{{ Str::limit($publisher->bio, 50) }}</td>
    </tr>
    <tr>
        <th>Fecha de creación:</th>
        <td>{{ $publisher->created_at }}</td>
    </tr>
    <tr>
        <th>Fecha de actualización:</th>
        <td>{{ $publisher->updated_at }}</td>
    </tr>
</table>
