@props(['faq'])

<table class="edit-tab" data-endpoint="{{ route('admin.faqs.show', $faq->id) }}">

    <tr>
        <th>Título:</th>
        <td>{{ $faq->locale['es']['title'] }}</td>
    </tr>
    <tr>
        <th>Descripción:</th>
        <td>{{ Str::limit($faq->locale['es']['description'], 50) }}</td>
    </tr>
    <tr>
        <th>Fecha de creación:</th>
        <td>{{ $faq->created_at }}</td>
    </tr>
    <tr>
        <th>Fecha de actualización:</th>
        <td>{{ $faq->updated_at }}</td>
    </tr>
</table>
