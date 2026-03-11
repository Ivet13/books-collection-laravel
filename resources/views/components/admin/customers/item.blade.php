@props(['customer'])

<table class="edit-tab" data-endpoint="{{ route('admin.customers.show', $customer->id) }}">
    <tr>
        <th>ID:</th>
        <td>{{ $customer->id }}</td>
    </tr>
    <tr>
        <th>Nombre:</th>
        <td>{{ $customer->name }}</td>
    </tr>
    <tr>
        <th>Sinopsis:</th>
        <td>{{ Str::limit($customer->synopsis, 50) }}</td>
    </tr>
    <tr>
        <th>Fecha de creación:</th>
        <td>{{ $customer->created_at }}</td>
    </tr>
    <tr>
        <th>Fecha de actualización:</th>
        <td>{{ $customer->updated_at }}</td>
    </tr>
</table>
