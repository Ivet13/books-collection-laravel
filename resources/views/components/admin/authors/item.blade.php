@props(['author'])

<table class="edit-tab" data-endpoint="{{ route('admin.authors.show', $author->id) }}"
    data-slug="{{ $author->sitemap->slug }}">
    <tr>
        <th>ID:</th>
        <td>{{ $author->id }}</td>
    </tr>
    <tr>
        <th>Nombre:</th>
        <td>{{ $author->name }}</td>
    </tr>
    <tr>
        <th>Bio:</th>
        <td>{{ Str::limit($author->bio, 50) }}</td>
    </tr>
    <tr>
        <th>Fecha de creación:</th>
        <td>{{ $author->created_at }}</td>
    </tr>
    <tr>
        <th>Fecha de actualización:</th>
        <td>{{ $author->updated_at }}</td>
    </tr>
</table>
