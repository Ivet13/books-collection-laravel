<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;


class CustomerController extends Controller
{
    public function index()
    {
        $query = Customer::orderBy('created_at', 'desc');
        $records = $query->paginate(10);

        return view('admin.customers.index', [
            'records' => $records,
        ]);
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|min:6',
            'name' => 'required|string|max:255',
            'about' => 'nullable|string',
        ]);

        $data['password'] = bcrypt($data['password']);

        Customer::create($data);

        return redirect()->route('customers.index')
            ->with('success', 'Customer creado correctamente');
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', [
            'customer' => $customer,
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'password' => 'nullable|min:6',
            'name' => 'required|string|max:255',
            'about' => 'nullable|string',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $customer->update($data);

        return redirect()->route('customers.index')
            ->with('success', 'Customer actualizado');
    }

    public function destroy(Customer $customer)
    {
        $customer->deactivated_at = now();
        $customer->save();

        return redirect()->route('customers.index')
            ->with('success', 'Customer desactivado');
    }
}
