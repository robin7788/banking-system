<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserAccount;
use App\Models\UserTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\FundTransferNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;


class UserTransactionService 
{

    /**
     * ----------------------------------------------------------------------------------
     * Create Transaction Records of user
     * ----------------------------------------------------------------------------------
     * @var $userAccount : The account holder on which balance is being credited
     * @var $amount : The transaction amount
     * @var $by_user : The user who is transferring the amount 
     */

    public function deposit(UserAccount $userAccount, number|int|float $amount, User $deposited_by, $note = ""): bool
    {
        DB::beginTransaction();
        try {
            $depositorUserAccount = $deposited_by->account;

            $depositor = new UserTransactions();

            $depositor->user_account_id = $depositorUserAccount->id;
            $depositor->user_id = $deposited_by->id;
            $depositor->is_credit = true;
            $depositor->amount = $amount;

            if(!$deposited_by->is_admin) {
                $depositor->balance = $depositorUserAccount->balance - $amount;
            }

            $depositor->note = $note;
            $depositor->from_currency = $depositorUserAccount->currency;
            $depositor->to_currency = $userAccount->currency;
            $depositor->charge = 0;  // Though currency might be different but we do not charge fee for depositor
            $depositor->from_to = $userAccount->user->id;
            $depositor->ref = "Transferred " . $depositor->amount . " "  . strtoupper($depositor->from_currency) . " amount to " . $userAccount->user->name;
            $depositor->created_at = now();
            $depositor->updated_at = now();

            $depositor->save();


            $calculated_amount = $this->calculate_remaining_balance(
                $amount, 
                $depositorUserAccount->currency, 
                $userAccount->currency
            );

            $receiver = new UserTransactions();

            $receiver->user_account_id = $userAccount->id;
            $receiver->user_id = $userAccount->user_id;
            $receiver->is_credit = false;
            $receiver->balance = $userAccount->balance + $calculated_amount['amount'];
            $receiver->amount = $calculated_amount['amount'];
            $receiver->charge = $calculated_amount['charge'];
            $receiver->from_currency = $depositorUserAccount->currency;
            $receiver->to_currency = $userAccount->currency;
            $receiver->note = $note;
            $receiver->from_to = $deposited_by->id;
            $receiver->ref = "Received " . $receiver->amount . " "  . strtoupper($receiver->from_currency) . " amount from " . $deposited_by->name;
            $receiver->created_at = now();
            $receiver->updated_at = now();

            $receiver->save();

            if(!$deposited_by->is_admin) {
                $depositorUserAccount->balance = $depositorUserAccount->balance - $amount;
                $depositorUserAccount->save();
            }
            
            $userAccount->balance = $userAccount->balance + $receiver->amount;

            $userAccount->save();


            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return false;
        }

        try {
            $deposited_by->notify(new FundTransferNotification($amount, $depositorUserAccount->currency, false));
            $userAccount->user->notify(new FundTransferNotification( $calculated_amount['amount'], $userAccount->currency, true));
        } catch (\Exception $e){}

        return true;
    }

    public function calculate_remaining_balance(number|int|float $amount, $from_currency, $to_currency) : array 
    {
        $charge = $exchange_rate = 0;

        if($from_currency != $to_currency) {
            // $rates = $this->get_rates($from_currency)->{$to_currency};

            // TODO:: Integrate currency conversion API to get exchange rate of different currency
            // TODO:: Free version of exchangerate api doesnot support base parameter so was not able to look further.
            // Once we get the rate. We just need to replace current  exchange_rate value with given rate.
            $exchange_rate = 0.78;  # USD to GBP

            $charge = 0.01;
            $amount = ($exchange_rate * $amount) - $charge;
        }

        return ['amount' => $amount, 'charge' => $charge, 'rate' => $exchange_rate];
    }


    public function get_rates($base = 'eur') 
    {
        
        $endpoint = config('app.exchange_rate_api_url') . config('app.exchange_rate_api_key');
        $symbols = config('app.exchange_rate_api_symbols');

        $symbols = "&format=1&symbols=" . $symbols;
        
        
        $endpoint .= $symbols;
        
        $response = Http::get($endpoint);

        $data = $response->object();

        return $data->rates;
    }



}