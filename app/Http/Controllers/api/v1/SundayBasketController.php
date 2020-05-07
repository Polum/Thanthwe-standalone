<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\SundayBasket;
use App\Account;
use App\Traits\TransactionTrait;
use App\Traits\ResponseTrait;

class SundayBasketController extends Controller
{
    use ResponseTrait, TransactionTrait;

    public function index()
    {

    }

    public function store(Request $request){
        if(Gate::allows('accountant', Auth::user()) || Gate::allows('clerk', Auth::user())){
            $account_query = DB::table('accounts')
                    ->select('accounts.*')
                    ->where('main', '=', true)->first();
            $account = Account::find($account_query->id);

            if($account){
                if(Carbon::parse($request->offering_date) <= Carbon::now()){
                    $request['account_id'] = $account->id;
                    $balance = $account->account_balance + $request->total_offerings;
                    $transaction = $this->makeTransaction('crediting', 'sunday basket', $request->total_offerings, $account->id);
                    $updateAccount = $this->updateAccount($account, $request, $balance);

                    $result['transaction'] = $transaction;
                    $result['update_account'] = $updateAccount;

                    if($transaction !== false && $updateAccount === true){
                        $request['transaction_id'] = $transaction['id'];
                        $input = $request->all();
                        if(sundayBasket::create($input)){
                            return $this->respondSuccess('Sunday basket added.');
                        }
                    }
                    return $this->respondError('Adding sunday basket failed.');
                }
                return $this->respondError('Cannot add sunday basket to a future date');
            }
            return $this->respondError('Account does not exist');
        }
        return $this->respondAccessError();
    }

    public function show($id)
    {
        //
    }

    public function basketGraph($year, $month)
    {
        $data = [];
        $data['labels'] = array('Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5');
        $data['datasets'] = [];
        $colors = ["1" => "#0cc2aa", "2" => "#0077be", "3" => "#ff5722", "4" => "#4caf50"];

        $holder = [];
        $holder['dataset'] =[]; //Hold number of weeks for the chosen month

        $service_list = DB::table('sunday_baskets')->select('service_no')
            ->join('transactions', 'transaction_id', '=', 'transactions.id')
            ->join('accounts', 'account_id', '=', 'accounts.id')
            ->whereYear('offering_date', '=', $year)
            ->distinct()
            ->get();


        foreach($service_list as $serv){
            $phase1 = [];
            $phase1['no'] = $serv->service_no;
            $phase1['data'] = array_fill(0,5,0);
            array_push($holder['dataset'], $phase1);
        }

        foreach($holder['dataset'] as $set){
            $composit = DB::table('sunday_baskets')->select('*')
            ->join('transactions', 'transaction_id', '=', 'transactions.id')
            ->join('accounts', 'account_id', '=', 'accounts.id')
            ->where('sunday_baskets.deleted_at', '=', null)
            ->whereYear('offering_date', '=', $year)
            ->whereMonth('offering_date', '=', $month)
            ->where('service_no', '=', $set['no'])
            ->get();

            foreach($composit as $el){
                $wn = Carbon::parse($el->offering_date)->weekOfMonth;
                $set['data'][$wn-1] = $set['data'][$wn-1] + $el->total_offerings;
            }
            $service = [];
            $service['label'] = "Service #".$set['no'];
            $service['backgroundColor'] = $colors[$set['no']];
            $service['data'] = $set['data'];
            $service['pointBackgroundColor'] = '#ffffff';
            $service['pointBorderColor'] = 'blue';
            array_push($data['datasets'], $service);

        }

        return $this->respondData($data);
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
