<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Notifications\SendTwoFactorCodeNotification;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TwoFactorController extends Controller
{
    /**
     * -------------------------------------------------------
     * Display 2FA form
     * -------------------------------------------------------
     * 
     * Code gets autofilled in field when existed.
     * 
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Auth/TwoFactorAuthenticate', [
            'status' => session('status'),
            'code' => $request->get('code') ?? ''
        ]);
    }


    /**
     * -------------------------------------------------------
     * Validate 2FA code
     * -------------------------------------------------------
     * 
     */
    public function store(Request $request): ValidationException|RedirectResponse
    {
        $request->validate([
            'two_factor_code' => ['integer', 'required'],
        ]);

        $user = auth()->user();

        if ((int) $request->input('two_factor_code') !== (int) $user->two_factor_code) {
            throw ValidationException::withMessages([
                'two_factor_code' => __('The code you entered doesn\'t match our records'),
            ]);
        }

        $user->resetTwoFactorCode();
        
        return redirect()->intended(route('dashboard', absolute: false));
    }
    

    /**
     * -------------------------------------------------------
     * Resend 2FA code to user
     * -------------------------------------------------------
     * 
     */
    public function resend(): RedirectResponse
    {
        $user = auth()->user();
        $user->generateTwoFactorCode();
        $user->notify(new SendTwoFactorCodeNotification());

        return redirect()->back()->withStatus(__('The two factor code has been sent again'));
    }
}
