<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // 'surname' => 'required|string|max:255',
            // 'username' => ['required', 'string', 'max:255', 'regex:/^[\w\-\.]+$/i'],
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'phone' => 'required|string|max:15|unique:' . User::class,
            // 'birthday' => 'required|date|before:today',
            // 'gender' => ['required', Rule::in(['Male', 'Female', 'Other'])],
            // 'contact' => [
            //     'required',
            //     function ($attribute, $value, $fail) {
            //         if (!filter_var($value, FILTER_VALIDATE_EMAIL) && !preg_match('/^09\d{9}$/', $value)) {
            //             $fail('The contact must be a valid email or a valid phone number');
            //         }

            //         if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            //             if (User::where('email', $value)->exists()) {
            //                 $fail('The email is already registered.');
            //             }
            //         } else {
            //             if (User::where('phone', $value)->exists()) {
            //                 $fail('The phone number is already registered.');
            //             }
            //         }
            //     },
            // ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // if (filter_var($request->contact, FILTER_VALIDATE_EMAIL)) {
        //     $email = $request->contact;
        //     $phone = null;
        // } else {
        //     $email = null;
        //     $phone = $request->contact;
        // }

        $user = User::create([
            'name' => $request->name,
            // 'surname' => $request->surname,
            // 'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            // 'birthday' => $request->birthday,
            // 'gender' => $request->gender,
            // 'email' => $email,
            // 'phone' => $phone,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    // public function messages()
    // {
    //     return [
    //         'regex' => 'Username can only contain alphanumeric characters, dash (-) and dot(.).'
    //     ];
    // }
}
