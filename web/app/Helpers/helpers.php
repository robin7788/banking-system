<?php 
use App\Models\User;

if (! function_exists('getRandomAccountNumber')) {
    function getRandomAccountNumber(): int
    {
        do {
            $randomNumber = (string) rand(1000000, 9999999);
            $year = now()->format('Y');
            $account_number = (int) ($year . $randomNumber);
        } while (User::where('account_number', $account_number)->count() > 1);
        return $account_number;
    }
}