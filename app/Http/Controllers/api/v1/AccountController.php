<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Account;
use App\User;
use App\Traits\UserTrait;
use App\Traits\ResponseTrait;

class AccountController extends Controller
{
    use UserTrait, ResponseTrait;

    public function index()
    {
        if(Gate::allows('accountant', Auth::user()) || Gate::allows('clerk', Auth::user())
            || Gate::allows('admin', Auth::user()) || Gate::allows('pastor', Auth::user())
            || Gate::allows('treasure', Auth::user())){
            $accounts = Account::all();
            if($accounts){
                return $this->respondData($accounts);
            }
            return $this->respondIncompleteAction('You do not have any account set.');
        }
        return respondAccessError();
    }

    public function store(Request $request)
    {
        if(Gate::allows('accountant', Auth::user())){
            $account = $request->all();
            if(Account::create($account)){
                return $this->respondActionComplete('Account created successfully');
            }
            return $this->respondIncompleteAction('Error creating account');
        }
        return $this->respondAccessError();
    }

    public function show($id)
    {
        if(Gate::allows('accountatnt', Auth::user())){
            $account = Account::find($id);
            if($account){
                return $this->respondData($account);
            }
            return $this->respondIncompleteAction('Could not find account registered with this ID');
        }
        return $this->respondAccessError();
    }

    public function update(Request $request, $id)
    {
        if(Gate::allows('accountant', Auth::user())){
            $account = Account::find($id);
            $account->id = $request->id;
            $account->account_name = $request->account_name;
            $account->account_balance = $account->account_balance;
            if($account->main == true){
                $account->main = true;
            }
            else{ $account->main = false; }

            if($account->save()){
                return $this->respondActionComplete('Account details updated successfuly.');
            }
            return $this->respondIncompleteAction('Error updating account details.');
        }
        return $this->respondAccessError();
    }

    public function destroy($id)
    {
        if(Gate::allows('accountant', Auth::user())){
            $account = Account::find($id);
            if($account->delete()){
                return $this->respondActionComplete('Account deleted successfuly.');
            }
            return $this->respondIncompleteAction('Error deleting account.');
        }
        return $this->respondAccessError();
    }
}
