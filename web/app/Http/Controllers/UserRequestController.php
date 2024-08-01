<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\UserAccount;
use App\Http\Resources\UserResource;
use App\Services\UserTransactionService;
use App\Http\Resources\UserTransactionsResource;
use Illuminate\Validation\ValidationException;

class UserRequestController extends Controller
{
    /**
     * Display fund transfer form
     */
    public function fund_transfer(Request $request): Response
    {
        return Inertia::render('FundTransfer/FundTransfer');
    }

    /**
     * Get charges and other detail before transferring to another user
     */
    public function get_fund_detail(Request $request, UserTransactionService $transaction): Response
    {
        $request->validate([
            'account_number' => ['required', 'integer'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        if(auth()->user()->account->balance < $request->get('amount')) {
            throw ValidationException::withMessages([
                'amount' => __('Sorry, your account has insufficient fund for that request.'),
            ]);
        }

        $receiptant = UserAccount::select('id', 'user_id', 'account_number', 'currency')
            ->where('account_number', $request->get('account_number'))
            ->first();

        $calculated_amount = $transaction->calculate_remaining_balance(
            $request->get('amount'), 
            auth()->user()->account->currency, 
            $receiptant->currency
        );

        if(!$receiptant) {
            throw ValidationException::withMessages([
                'account_number' => __('Requested user account does not exist.'),
            ]);
        }

        return Inertia::render('FundTransfer/TransferDetail', [
            'amount' => $request->get('amount'),
            'account_number' => $request->get('account_number'),
            'transfer_amount' => $calculated_amount['amount'],
            'rate' => $calculated_amount['rate'],
            'charge' => $calculated_amount['charge'],
            'receiptant' => $receiptant
        ]);
    }

    /**
     * Transfer fund
     */
    public function get_fund_confirm(Request $request, UserTransactionService $transaction): RedirectResponse
    {
        $request->validate([
            'account_number' => ['required', 'integer'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        if(auth()->user()->account->balance < $request->get('amount')) {
            throw ValidationException::withMessages([
                'status' => __('Sorry, your account has insufficient fund for that request.'),
            ]);
        }

        $userAccount = UserAccount::where('account_number', $request->get('account_number'))
            ->first();
        
        $success = $transaction->deposit(
            $userAccount, 
            $request->get('amount'), 
            auth()->user(), 
        );

        $message = __("The transfer has been successful.");
        if(!$success) {
            $message = __('There is some issue with the transfer. Please try again later.');
        }

        return redirect()->route('dashboard')->with(['status' => $message]);
    }


    public function transactions() 
    {
        $user = auth()->user();
        $transactions = $user->account?->transactions()->orderBy('id', 'desc')->get();
        return Inertia::render('SharedFile/Transactions', [
            'user' => $user,
            'transactions' => !empty($transactions) ? UserTransactionsResource::collection($transactions) : []
        ]);
    }
}
