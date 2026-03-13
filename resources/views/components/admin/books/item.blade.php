@props(['book'])

<table class="edit-tab" data-endpoint="{{ route('admin.books.show', $book->id) }}">
    <tr>
        <th>ID:</th>
        <td>{{ $book->id }}</td>
    </tr>
    <tr>
        <th>Nombre:</th>
        <td>{{ $book->title }}</td>
    </tr>
    <tr>
        <th>Sinopsis:</th>
        <td>{{ Str::limit($book->synopsis, 50) }}</td>
    </tr>
    <tr>
        <th>Fecha de creación:</th>
        <td>{{ $book->created_at }}</td>
    </tr>
    <tr>
        <th>Fecha de actualización:</th>
        <td>{{ $book->updated_at }}</td>
    </tr>
</table>
