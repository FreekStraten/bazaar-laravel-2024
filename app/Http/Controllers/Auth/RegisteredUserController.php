<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

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
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'user_type' => ['required', 'string', 'in:private,business'],
                'street' => ['required', 'string'],
                'house_number' => ['required', 'string'],
                'city' => ['required', 'string'],
                'zip_code' => ['required', 'string'],
            ]);

            $validator->validate();

            $address = Address::create([
                'street' => $request->street,
                'house_number' => $request->house_number,
                'city' => $request->city,
                'zip_code' => $request->zip_code,
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => $request->user_type,
                'address_id' => $address->id,
            ]);

            event(new Registered($user));

            Auth::login($user);

            //if response is json, send success with all user data with address
            if ($request->wantsJson()) {
                return response()->json(['user' => $user->load('address')], 201);
            }

            return redirect(RouteServiceProvider::HOME);
        } catch (ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json(['errors' => $e->errors()], 422);
            } else {
                return redirect()->back()->withErrors($e->errors())->withInput();
            }
        }
    }
}
