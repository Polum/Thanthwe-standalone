<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Transaction;
use App\Account;
use App\Expense;
use App\User;
use App\Traits\ResponseTrait;
use App\Traits\TransactionTrait;

class ExpenseController extends Controller
{
    use ResponseTrait, TransactionTrait;

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        if(Gate::allows('accountant', Auth::user()) || Gate::allows('clerk', Auth::user())){
            $account = Account::find($request->account_id);
            if($account){
                if(Carbon::parse($request->expenditure_date) <= Carbon::now()){
                    $balance = $account->account_balance - $request->expense_amount;
                    $transaction = $this->makeTransaction('debiting', 'expenses', $request->expense_amount, $request->account_id);
                    $updateAccount = $this->updateAccount($account, $request, $balance);

                    $result['transaction'] = $transaction;
                    $result['update_account'] = $updateAccount;
                    if($transaction !== false && $updateAccount === true){
                        $request['transaction_id'] = $transaction['id'];
                        $input = $request->all();
                        if(Expense::create($input)){
                            return $this->respondActionComplete('Transaction completed.');
                        }
                    }
                    return $this->respondIncompleteAction('Transaction failed', $result);
                }
                return $this->respondError('Cannot add expense to a future date');
            }
            return $this->respondError('Account does not exist');
        }
        return $this->respondAccessError();
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
