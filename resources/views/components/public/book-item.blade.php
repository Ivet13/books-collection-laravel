@props(['book'])

<table class="edit-tab" data-id="{{ $book->id }}">
    <tr>
        <th>ID:</th>
        <td>{{ $book->id }}</td>
    </tr>
    <tr>
        <th>Título:</th>
        <td>{{ $book->title }}</td>
    </tr>
    <tr>
        <th>ISBN:</th>
        <td>{{ $book->isbn }}</td>
    </tr>
    <tr>
        <th>Sinopsis:</th>
        <td>{{ $book->description }}</td>
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
