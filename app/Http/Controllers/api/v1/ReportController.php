<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Excel;
use App\Exports\SundayBasketReport;
use App\Exports\Report;
use App\Exports\MemberTitheReport;
use App\Exports\IncomeStatement;
use App\User;
use App\Member;
use App\SundayBasket;
use App\Offering;
use App\OfferingType;
use App\Account;
use App\Transaction;
use App\Traits\ResponseTrait;

class ReportController extends Controller
{
    use ResponseTrait;

    public function download($name, $year)
    {
        $data = [];
        $output = [];
        $data_array = ["q1"=>1, "q1"=>2, "q1"=>3, "q1"=>4];

        if($name === 'tithe')
        {
            return (new Report($year))->download($year.' Annual '.$name.' report.xls');
        }
        if($name === 'members-tithe')
        {
            return (new MemberTitheReport($year))->download($year.' Annual '.$name.' report.xls');
        }
        if($name === 'sunday-basket')
        {
            return (new SundayBasketReport($year))->download($year.' Annual '.$name.' report.xls');
        }
        if($name === 'income-statement')
        {
            return (new IncomeStatement($year))->download($year.' Annual '.$name.' report.xls');
        }

        return $data;
    }

    private function general($year)
    {
        $transaction_data = DB::table('transactions')
        ->select('transactions.transaction_date', 'transactions.transaction_amount')->get()
        ->groupBy(function($date) {
            return Carbon::parse($date->transaction_date)->quarter;
        });
    }
}
