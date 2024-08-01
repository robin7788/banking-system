<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAccount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\UserTransactionService;

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
    public function store(Request $request, UserTransactionService $transaction): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'dob' => 'required|date|before:tomorrow',
            'address_1' => 'required|string|max:150',
            'address_2' => 'nullable|string|max:150',
            'town' => 'required|string|max:150',
            'country' => 'required|string|max:150',
            'post_code' => 'required|string|max:20',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'dob' => $request->dob,
            'address_1' => $request->address_1,
            'address_2' => $request->address_2,
            'town' => $request->town,
            'country' => $request->country,
            'post_code' => $request->post_code,
            'country' => $request->country,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $userAccount = UserAccount::create([
            'user_id' => $user->id,
            'account_number' => getRandomAccountNumber(),
            'balance' => 0,
            'currency' => config('app.user_currency')
        ]);
        
        $transaction->deposit(
            $userAccount, 
            config('app.user_account_balance'), 
            User::find(1), 
        );

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
