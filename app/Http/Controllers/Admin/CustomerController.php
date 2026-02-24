<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\UserRequest;
use Barryvdh\Debugbar\Facades\Debugbar;


class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        // Texto libre: title o isbn
        if ($request->filled('q')) {
            $q = $request->string('q')->toString();

            $query->where(function ($sub) use ($q) {
                $sub->where('email', 'like', "%{$q}%")
                    ->orWhere('name', 'like', "%{$q}%");
            });
        }

        // records = paginación (mejor que get)
        $records = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();



        if ($request->ajax()) {
            return response()->view('admin.customers._list_and_pagination', [
                'records' => $records,
            ]);
        }
        return view('admin.customers.index', [
            'records' => $records,
        ]);
    }


    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(UserRequest $request)
    {
        echo 'hola';
        exit();
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
