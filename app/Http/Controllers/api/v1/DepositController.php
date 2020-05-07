<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Transaction;
use App\Account;
use App\Deposit;
use App\User;
use App\Traits\ResponseTrait;
use App\Traits\TransactionTrait;

class DepositController extends Controller
{
    use ResponseTrait, TransactionTrait;

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $result = [];
        if(Gate::allows('accountant', Auth::user()) || Gate::allows('clerk', Auth::user())){
            $account = Account::find($request->account_id);

            $balance = $account->account_balance + $request->deposit_amount;
            $transaction = $this->makeTransaction('crediting', 'deposit', $request->deposit_amount, $request->account_id);
            $updateAccount = $this->updateAccount($account, $request, $balance);

            $result['transaction'] = $transaction;
            $result['update_account'] = $updateAccount;
            if($transaction !== false && $updateAccount === true){
                $request['deposit_date'] = Carbon::now();
                $request['transaction_id'] = $transaction['id'];
                $input = $request->all();
                if(Deposit::create($input)){
                    return $this->sendResponse('success', $request, 'Transaction completed.', 200);
                    // return $this->respondActionComplete('Transaction completed.');
                }
            }
            return $this->respondIncompleteAction('Transaction failed', $result);
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
