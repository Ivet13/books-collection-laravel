<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\UserRequest;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function __construct(private User $user) {}

    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Opcional: impedir login si está desactivado
        $admin = User::where('email', $credentials['email'])->first();
        if ($admin && $admin->deactivated_at) {
            return back()->withErrors(['email' => 'Cuenta desactivada.']);
        }

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('admin.customers.index');
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas.'])->onlyInput('email');
    }


    public function index()
    {
        try {
            $records = $this->user
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $view = View::make('admin.customers.index')
                ->with('records', $records);

            return $view;
        } catch (\Exception $e) {
        }
    }

    public function create()
    {
        try {
            if (request()->ajax()) {
                return response()->json([], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' =>  \Lang::get('admin/notification.error'),
            ], 500);
        }
    }

    public function store(UserRequest $request)
    {
        try {

            $data = $request->validated();

            unset($data['password_confirmation']);

            if (!$request->filled('password') && $request->filled('id')) {
                unset($data['password']);
            }

            $this->user->updateOrCreate([
                'id' => $request->input('id')
            ], $data);

            return response()->json([
                'message' => 'Usuario creado correctamente',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    public function edit(User $user)
    {
        return response()->json([
            'user' => $user,
        ], 200);
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();

            return response()->json([
                'message' => 'Usuario eliminado correctamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
