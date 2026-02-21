@props(['customer'])

<table class="edit-tab" data-id="{{ $customer->id }}">
    <tr>
        <th>ID:</th>
        <td>{{ $customer->id }}</td>
    </tr>
    <tr>
        <th>Nombre:</th>
        <td>{{ $customer->name }}</td>
    </tr>
    <tr>
        <th>Email:</th>
        <td>{{ $customer->email }}</td>
    </tr>
    <tr>
        <th>Teléfono:</th>
        <td>{{ $customer->phone }}</td>
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
