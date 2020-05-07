<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Transaction;
use App\Account;
use App\Offering;

trait TransactionTrait{

    public function makeTransaction($type = 'none', $source = 'none', $amount = 0, $account_id = 0){
        $transaction = [];
        $transaction['transaction_type'] = $type;
        $transaction['transaction_source'] = $source;
        $transaction['transaction_amount'] = $amount;
        $transaction['transaction_date'] = Carbon::now();
        $transaction['account_id'] = $account_id;
        $result = Transaction::create($transaction);

        if($result){
            return $result;
        }
        return false;
    }

    public function updateAccount($account, $request, $balance){
        $account['id'] = $request->account_id;
        $account['account_name'] = $account->account_name;
        $account['account_balance'] = $balance;

        if($account->save()){
            return true;
        }
        return $account;
    }

    public function updateOfferings($offering, Request $request)
    {
        $offering['id'] = $request->id ? $request->id : $offering->id;
        $offering['other_type'] = $request->other_type ? $request->other_type : $offering->other_type;
        $offering['offering_amount'] = $request->offering_amount ? $request->offering_amount : $offering->offering_amount;
        $offering['offering_date'] = $request->offering_date ? $request->offering_date : $offering->offering_date;
        $offering['member_id'] = $request->member_id ? $request->member_id : $offering->member_id;
        $offering['offering_type_id'] = $request->offering_type_id ? $request->offering_type_id : $offering->offering_type_id;
        $offering['transaction_id'] = $request->transaction_id ? $request->transaction_id : $offering->transaction_id;

        if($offering->save()){
            return true;
        }
        return false;
    }

    public function reversedTransactions($id)
    {
        return DB::table('transactions')
        ->join('accounts', 'account_id', '=', 'accounts.id')
        ->select('transactions.*')
        ->where('transactions.reverse', '=', 'requested')
        ->get();
    }

    public function reverseTransaction($transaction)
    {
        $transaction['id'] = $transaction->id ;
        $transaction['transaction_type'] = $transaction->transaction_type ;
        $transaction['transaction_source'] = $transaction->transaction_source ;
        $transaction['transaction_amount'] = $transaction->transaction_amount;
        $transaction['transaction_date'] = $transaction->transaction_date ;
        $transaction['account_id'] = $transaction->account_id ;
        $transaction['reverse'] = "requested" ;

        if($transaction->save())
        {
            return true;
        }
        return $transaction;
    }
}
