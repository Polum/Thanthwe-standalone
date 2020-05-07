<?php
namespace App\Http\Controllers\api\v1;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Transaction;
use App\Account;
use App\SundayBasket;
use App\Offering;
use App\OfferingType;
use App\User;
use App\Member;
use App\Traits\ResponseTrait;
use App\Traits\TransactionTrait;

class OfferingController extends Controller
{
    use ResponseTrait, TransactionTrait;

    public function index()
    {
        //
    }

    public function offerType(){
        $offer_types = OfferingType::all();
        return $this->respondData($offer_types);
    }

    public function store(Request $request)
    {
        if(Gate::allows('accountant', Auth::user()) || Gate::allows('clerk', Auth::user())){
            $account_query = DB::table('accounts')
                    ->select('accounts.*')
                    ->where('main', '=', true)->first();
            $account = Account::find($account_query->id);

            if($account){
                if(Carbon::parse($request->offering_date) <= Carbon::now()){
                    $request['account_id'] = $account->id;
                    $balance = $account->account_balance + $request->offering_amount;
                    $transaction = $this->makeTransaction('crediting', 'offerings', $request->offering_amount, $account->id);
                    $updateAccount = $this->updateAccount($account, $request, $balance);

                    $result['transaction'] = $transaction;
                    $result['update_account'] = $updateAccount;

                    if($transaction !== false && $updateAccount === true){
                        $member = Member::where('envelop_no', '=', $request->envelop_no)->first();

                        $request['member_id'] = $member? $member->id : null;
                        $request['transaction_id'] = $transaction['id'];
                        $input = $request->all();
                        if(Offering::create($input)){
                            return $this->respondActionComplete('Offering recorded succefully.', $account);
                        }
                    }
                    return $this->respondIncompleteAction('Error recording offering.', $result);
                }
                return $this->respondError('Cannot add offering to a future date');
            }
            return $this->respondError('Account does not exist');
        }
        return $this->respondAccessError();
    }

    public function stats($year, $month){
        $data = [];
        $offer_type_id = OfferingType::where('offering_title', 'tithe')->first()->id;

        $offerings = DB::table('offerings')
        ->join('transactions', 'transaction_id', '=', 'transactions.id')
        ->join('accounts', 'account_id', '=', 'accounts.id')
        ->where('offering_type_id', '!=', $offer_type_id)
        ->whereYear('offering_date', '=', $year)
        ->whereMonth('offering_date', '=', $month)
        ->get()->groupBy('offering_type_id');

        foreach($offerings as $offering){
            $offer_type = [];
            $total = 0;
            foreach($offering as $offer){
                $offer_name = OfferingType::find($offer->offering_type_id)->offering_title;
                $total = $total + $offer->offering_amount;
                if(isset($offer_type['offer'])){
                    if($offer_type['offer'] === $offer_name){}
                    else{ $offer_type['offer'] = $offer_name; }
                }
                else{
                    $offer_type['offer'] = $offer_name;
                }
            }
            $offer_type['total'] = $total;
            array_push($data, $offer_type);
        }
        return $this->respondData($data);
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
