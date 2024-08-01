<?php

namespace App\Http\Controllers\Admin;

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
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use Illuminate\Support\Facades\Hash;
use App\Services\UserTransactionService;
use App\Http\Resources\UserTransactionsResource;
use App\Notifications\RegistrationNotificationNotification;

class UserController extends Controller
{
    /**
     * ------------------------------------------------------------------------------------
     * Display users
     * ------------------------------------------------------------------------------------
     * 
     */
    public function index(Request $request): Response | RedirectResponse
    {
        if(!auth()->user()->is_admin) {
            return Redirect::route('dashboard')->withStatus("Unauthorise access");
        }

        // User::factory()->count(30)->create();

        $columns = [
            'id', 'first_name', 'last_name', 'email', 'dob',
            'address_1', 'address_2', 'town', 'country', 'post_code',
            'created_at'
        ];

        $query = User::query()->with('account')->select($columns);

        if($request->get('search')) {
            $query->where('first_name', 'LIKE', '%' . $request->get('search') . '%')
                ->orWhere('last_name', 'LIKE', '%' . $request->get('search') . '%')
                ->orWhere('address_1', 'LIKE', '%' . $request->get('search') . '%')
                ->orWhere('address_2', 'LIKE', '%' . $request->get('search') . '%')
                ->orWhere('town', 'LIKE', '%' . $request->get('search') . '%')
                ->orWhere('country', 'LIKE', '%' . $request->get('search') . '%')
                ->orWhere('post_code', 'LIKE', '%' . $request->get('search') . '%')
                ->orWhere('created_at', 'LIKE', '%' . $request->get('search') . '%');
        }
        
        $users = $query->orderBy('updated_at', 'desc')->paginate()->appends($request->all());
        
        return Inertia::render('Admin/User/List', [
            'users' =>UserResource::collection($users),
            'searchValue' => $request->get('search')
        ]);
    }

    /**
     * ------------------------------------------------------------------------------------
     * Display create user form
     * ------------------------------------------------------------------------------------
     */
    public function create(Request $request, UserTransactionService $transaction): Response | RedirectResponse
    {
        if(!auth()->user()->is_admin) {
            return Redirect::route('dashboard')->withStatus("Unauthorise access");
        }
        
        $transaction->get_rates();

        return Inertia::render('Admin/User/Create');
    }

    /**
     * ------------------------------------------------------------------------------------
     * Store the user information.
     * ------------------------------------------------------------------------------------
     * 
     */
    public function store(UserCreateRequest $request, UserTransactionService $transaction): RedirectResponse
    {
        if(!auth()->user()->is_admin) {
            return Redirect::route('dashboard')->withStatus("Unauthorise access");
        }
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
            'password' => Hash::make($request->password),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password'))
        ]);

        try {
            $user->notify(new RegistrationNotificationNotification($request->get('password')));
        } catch (\Exception $e){}

        $userAccount = UserAccount::create([
            'user_id' => $user->id,
            'account_number' => getRandomAccountNumber(),
            'type' => $request->get('type'),
            'balance' => 0,
            'currency' => config('app.user_currency')
        ]);

        $transaction->deposit(
            $userAccount, 
            config('app.user_account_balance'), 
            auth()->user(), 
        );

        return $request->add_new ? Redirect::route('admin.user.create') 
            : Redirect::route('admin.user.index');
    }


    /**
     * ------------------------------------------------------------------------------------
     * Display edit user form
     * ------------------------------------------------------------------------------------
     */
    public function edit(User $user): Response
    {
        if(!auth()->user()->is_admin) {
            return Redirect::route('dashboard')->withStatus("Unauthorise access");
        }
        return Inertia::render('Admin/User/Edit', [
            'user' => $user
        ]);
    }

    /**
     * ------------------------------------------------------------------------------------
     * Update the user information
     * ------------------------------------------------------------------------------------
     */
    public function update(UserUpdateRequest $request, UserTransactionService $transaction, User $user): RedirectResponse
    {
        if(!auth()->user()->is_admin) {
            return Redirect::route('dashboard')->withStatus("Unauthorise access");
        }
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->dob = $request->get('dob');
        $user->address_1 = $request->get('address_1');
        $user->address_2 = $request->get('address_2');
        $user->town = $request->get('town');
        $user->country = $request->get('country');
        $user->post_code = $request->get('post_code');
        $user->email = $request->get('email');
        $user->updated_at = Carbon::now();

        if(!$user->account){
            $userAccount = UserAccount::create([
                'user_id' => $user->id,
                'account_number' => getRandomAccountNumber(),
                'balance' => 0,
                'type' => 'saving',
                'currency' => config('app.user_currency')
            ]);
            $success = $transaction->deposit(
                $userAccount, 
                config('app.user_account_balance'), 
                auth()->user(), 
            );

        }

        if($request->get('password'))
            $user->password = Hash::make($request->get('password'));

        $user->save();

        return Redirect::route('admin.user.edit', $user->id);
    }

    /**
    * ------------------------------------------------------------------------------------
    * List Selected user transaction histories in tabular format.
    * ------------------------------------------------------------------------------------ 
    */
    public function transaction(User $user) : Response
    {
        if(!auth()->user()->is_admin) {
            return Redirect::route('dashboard')->withStatus("Unauthorise access");
        }
        $transactions = $user->account?->transactions()->orderBy('id', 'desc')->get();
        return Inertia::render('SharedFile/Transactions', [
            'user' => $user,
            'transactions' => !empty($transactions) ? UserTransactionsResource::collection($transactions) : []
        ]);
    }
}
