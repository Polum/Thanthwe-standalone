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
use App\Offering;
use App\OfferingType;
use App\User;
use App\Member;
use App\Traits\ResponseTrait;
use App\Traits\TransactionTrait;

class TitheController extends Controller
{
    use ResponseTrait, TransactionTrait;

    public function index()
    {
        //
    }

    public function stats(){
        $data = [];

        $this_month = $this->monthlyTithe(Carbon::now()->year, Carbon::now()->month);
        $data['this_month'] = $this->sumUpTithe($this_month);

        $last_month = $this->monthlyTithe(Carbon::now()->year, Carbon::now()->subMonth()->month);
        $data['last_month'] = $this->sumUpTithe($last_month);

        $last_year_this_month = $this->monthlyTithe(Carbon::now()->subYear()->year, Carbon::now()->month);
        $data['last_year_this_month'] = $this->sumUpTithe($last_year_this_month);

        return $this->respondData($data);
    }

    private function monthlyTithe($year, $month){
        $monthTitheData = DB::table('offerings')
                        ->join('offering_types', 'offering_types.id', '=', 'offering_type_id')
                        ->join('transactions', 'transactions.id', '=', 'transaction_id')
                        ->join('accounts', 'accounts.id', '=', 'account_id')
                        ->where('offering_types.offering_title', '=', 'tithe')
                        ->whereYear('offerings.offering_date', '=', $year)
                        ->whereMonth('offerings.offering_date', '=', $month)
                        ->get();
        return $monthTitheData;
    }

    private function sumUpTithe($tithes){
        $totalTithe = 0;
        foreach($tithes as $tithe){
            $totalTithe = $totalTithe + $tithe->offering_amount;
        }
        return $totalTithe;
    }

    public function store(Request $request)
    {
        if(Gate::allows('accountant', Auth::user()) || Gate::allows('clerk', Auth::user())){
            $account_query = DB::table('accounts')
                    ->select('accounts.*')
                    ->where('main', '=', true)->first();
            $account = Account::find($account_query->id);

            if($account){
                $offering = OfferingType::where('offering_title', '=', 'tithe')->first();

                if(Carbon::parse($request->offering_date) <= Carbon::now()){
                    $request['account_id'] = $account->id;
                    $balance = $account->account_balance + $request->offering_amount;
                    $transaction = $this->makeTransaction('crediting', 'offerings', $request->offering_amount, $account->id);
                    $updateAccount = $this->updateAccount($account, $request, $balance);

                    $result['transaction'] = $transaction;
                    $result['update_account'] = $updateAccount;

                    if($transaction !== false && $updateAccount === true){
                        $member = Member::where('envelop_no', '=', $request->envelop_no)->first();
                        if($member){
                            $request['offering_type_id'] = $offering->id;
                            $request['member_id'] = $member->id;
                            $request['transaction_id'] = $transaction['id'];
                            $input = $request->all();
                            if(Offering::create($input)){
                                return $this->respondSuccess('Tithe recorded.');
                            }
                            return $this->respondError('Failed recording tithe.');
                        }
                        return $this->respondError('Member with envelop numbe: '.$request->envelop_no.' does not exist.');
                    }
                    return $this->respondError('Tithe not recorded.');
                }
                return $this->respondError('Cannot add tithe to a future date');
            }
            return $this->respondError('Account does not exist');
        }
        return $this->respondAccessError();
    }

    public function show($id)
    {
        //
    }

    public function graphData($year)
    {
        $GraphData = [];
        $GraphData['labels'] = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $GraphData['datasets'] = [];
        $data = [];
        $monthly = array_fill(0,12,0);

        $offering_type_id = OfferingType::where('offering_title', '=', 'tithe')
                            ->first()->id;

        $yearTitheData = DB::table('offerings')
                    ->join('transactions', 'transactions.id', '=', 'transaction_id')
                    ->join('accounts', 'accounts.id', '=', 'account_id')
                    ->where('offering_type_id', '=', $offering_type_id)
                    ->whereYear('offering_date', '=', $year)
                    ->orderBy('offering_date')
                    ->get()
                    ->groupBy(function($date) {
                        return Carbon::parse($date->offering_date)->format('m');
                    });

        foreach($yearTitheData as $monthTitheData){
            foreach($monthTitheData as $tithe){
                $month = Carbon::parse($tithe->offering_date)->month - 1;
                $monthly[$month] = $monthly[$month] + $tithe->offering_amount;
            }
        }

        $data['label'] = $year." Tithes";
        $data['backgroundColor'] = "#0cc2aa";
        $data['data'] = $monthly;
        $data['pointBackgroundColor'] = "#ffffff";
        $data['pointBorderColor'] = "blue";

        array_push($GraphData['datasets'], $data);

        return $this->respondData($GraphData);
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

