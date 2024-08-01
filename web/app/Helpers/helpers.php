<?php 
use App\Models\UserAccount;

if (! function_exists('getRandomAccountNumber')) {
    function getRandomAccountNumber(): int
    {
        do {
            $randomNumber = (string) rand(1000000, 9999999);
            $year = now()->format('Y');
            $account_number = (int) ($year . $randomNumber);
        } while (UserAccount::where('account_number', $account_number)->limit(1)->count() > 0);
        return $account_number;
    }
}