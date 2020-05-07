<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Account;
use App\Transaction;
use App\SundayBasket;
use App\Expense;
use App\PaymentVoucher;
use App\Deposit;
use App\Offering;
use App\Traits\TransactionTrait;
use App\Traits\ResponseTrait;

class TransactionController extends Controller
{
    use TransactionTrait, ResponseTrait;

    public function index()
    {
        $transactions = DB::table('transactions')
                        ->join('accounts', 'account_id', '=', 'accounts.id')
                        ->select('transactions.*', 'accounts.account_name')
                        ->where('transactions.deleted_at', null)
                        ->orderBy('transactions.id', 'desc')
                        ->paginate();
        return $this->respondData($transactions);
    }

    public function store(Request $request)
    {
    }

    public function requestReverse($id)
    {
        if(Gate::allows('accountant', Auth::user()) || Gate::allows('clerk', Auth::user())){
            $transaction = Transaction::find($id);
            if($transaction){
                $reverseTransaction = $this->reverseTransaction($transaction);
                if($reverseTransaction === true){
                    return $this->respondSuccess('Transaction reversal requested succesfully.');
                }
                return $this->respondError('Transaction reversal request faild.');
            }
            return $this->respondError('Error tracing transaction.');
        }
        return $this->respondAccessError();
    }

    public function reverseRequests()
    {
        if(Gate::allows('admin', Auth::user()) || Gate::allows('treasure', Auth::user())){
            $transaction = $this->reversedTransactions(Auth::user()->church_id);
            if($transaction){
                return $this->respondData($transaction->count());
            }
            return $this->respondError('Error tracing transaction.');
        }
        return $this->respondAccessError();
    }

    public function requestList()
    {
        if(Gate::allows('admin', Auth::user()) || Gate::allows('treasure', Auth::user())){
            $transactions = $this->reversedTransactions(Auth::user()->church_id);
            if($transactions){
                return $this->respondData($transactions);
            }
            return $this->respondError('Error tracing transaction.');
        }
        return $this->respondAccessError();
    }

    public function reversal(Request $request){
        if(Gate::allows('admin', Auth::user()) || Gate::allows('treasure', Auth::user())){
            $transaction = Transaction::find($request->transaction_id);
            if($transaction){
                if($request->response == 'allow'){
                    $allow = $this->allow($transaction);
                    if($allow){
                        return $this->respondSuccess('Transaction reversed.');
                    }
                    return $this->respondError('Transaction reversal failed.'.$allow);
                }
                else if($request->response == 'deny'){
                    $transaction->reverse = null;
                    if($transaction->save()){
                        return $this->respondSuccess('Transaction recalled.');
                    }
                    return $this->respondError('Transaction reversal failed.');
                }
            }
            return $this->respondError('Transaction does not exist.');
        }
        return $this->respondAccessError();
    }

    private function allow($transaction){
        $account = Account::find($transaction->account_id);
        $reverseAmount = $transaction->transaction_amount;
        $source = $transaction->transaction_source;

        if($transaction->transaction_type === "crediting"){
            $balance = $account->account_balance - $reverseAmount;
        }
        if($transaction->transaction_type === "debiting"){
            $balance = $account->account_balance + $reverseAmount;
        }

        $updateAccount = $this->updateAccount($account, $transaction, $balance);

        $result['update_account'] = $updateAccount;

        if($updateAccount !== false)
        {
            $transaction["reverse"] = "approved";
            $removeSource = $this->removeSource($source, $transaction->id);
            $transaction->save();
            return true;
        }
        return $result;
    }

    private function removeSource($source, $transaction_id)
    {
        if($source == "sunday basket"){
            $response = SundayBasket::where('transaction_id', '=', $transaction_id);
        }
        else if($source === "expenses"){
            $response = Expense::where('transaction_id', '=', $transaction_id);
        }
        else if($source === "deposit"){
            $response = Deposit::where('transaction_id', '=', $transaction_id);
        }
        else if($source === "payment voucher"){
            $response = PaymentVoucher::where('transaction_id', '=', $transaction_id);
        }
        else if($source === "offerings"){
            $response = Offering::where('transaction_id', '=', $transaction_id);
        }
        else{
            $response = null;
        }

        if($source !== null){
            return $response->first()->delete();
        }
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
