<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Supplier;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'register_as' => ['required', 'string', 'in:user,supplier'],
        ];

        if ($request->register_as === 'supplier') {
            $rules['alamat'] = ['required', 'string'];
            $rules['telepon'] = ['required', 'string'];
            $rules['pic'] = ['required', 'string'];
        }

        $request->validate($rules);

        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 'aktif',
            ]);

            if ($request->register_as === 'supplier') {
                $user->syncRoles(['supplier']);

                Supplier::create([
                    'kode_supplier' => Supplier::generateKode(),
                    'nama_supplier' => $request->name,
                    'alamat' => $request->alamat,
                    'telepon' => $request->telepon,
                    'email' => $request->email,
                    'pic' => $request->pic,
                    'status' => 'aktif',
                ]);
            } else {
                $user->syncRoles(['staff_unit']);
            }

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
