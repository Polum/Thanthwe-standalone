<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Transaction;
use App\Account;
use App\PaymentVoucher;
use App\User;
use App\Traits\ResponseTrait;
use App\Traits\TransactionTrait;

class PaymentVoucherController extends Controller
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
                    $balance = $account->account_balance - $request->amount;
                    $transaction = $this->makeTransaction('debiting', 'payment voucher', $request->amount, $request->account_id);
                    $updateAccount = $this->updateAccount($account, $request, $balance);

                    $result['transaction'] = $transaction;
                    $result['update_account'] = $updateAccount;
                    if($transaction !== false && $updateAccount === true){
                        $request['transaction_id'] = $transaction['id'];
                        $input = $request->all();
                        if(PaymentVoucher::create($input)){
                            return $this->respondActionComplete('Transaction completed.');
                        }
                    }
                    return $this->respondIncompleteAction('Transaction failed', $result);
                }
                return $this->respondError('Cannot add payment voucher to a future date');
            }
            return $this->respondError('Account does not exist');
        }
        return $this->respondAccessError();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
