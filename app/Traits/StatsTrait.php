<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\OfferingType;
use App\Offering;
use App\SundayBasket;
use App\Member;

trait StatsTrait{


    public function titheStats($church_id, $year)
    {
        $quartery = array_fill(0,4,0);
        $NOMembers = array_fill(0,4,0);
        $output = [];
        $tithe_id = OfferingType::where('offering_title', '=', 'tithe')->first()->id;

        $tithes = DB::table('offerings')
        ->orderBy('offerings.offering_date', 'asc')
        ->join('transactions', 'transaction_id', '=', 'transactions.id')
        ->join('accounts', 'account_id', '=', 'accounts.id')
        ->where('offerings.offering_type_id', '=', $tithe_id)
        ->whereYear('offerings.offering_date', '=', $year)
        ->get()
        ->groupBy(function($date) {
            return Carbon::parse($date->offering_date)->quarter;
        });

        foreach($tithes as $key => $quarter){
            $qt = array();
            foreach($quarter as $tithe){
                $date = Carbon::parse($tithe->offering_date)->quarter-1;
                $quartery[$date] = $quartery[$date] + $tithe->offering_amount;

                if(in_array($tithe->member_id, $qt, true)){}
                else{array_push($qt, $tithe->member_id);}
            }
            $NOMembers[$key-1] = sizeOf($qt);
        }
        $output['quartery'] = $quartery;
        $output['NOMembers'] = $NOMembers;
        return $output;
    }

    public function sundayBasketStats($church_id, $year)
    {
        $quartery = array_fill(0,4,0);
        $baskets = DB::table('sunday_baskets')
        ->join('transactions', 'transaction_id', '=', 'transactions.id')
        ->join('accounts', 'account_id', '=', 'accounts.id')
        ->whereYear('offering_date', '=', $year)
        ->get()
        ->groupBy(function($date) {
            return Carbon::parse($date->offering_date)->quarter;
        });

        foreach($baskets as $quarter){
            foreach($quarter as $basket){
                $date = Carbon::parse($basket->offering_date)->quarter-1;
                $quartery[$date] = $quartery[$date] + $basket->total_offerings;
            }
        }
        return $quartery;
    }

    public function memberStats($church_id, $year, $key)
    {
        $quartery = array_fill(0,4,0);
        $members = DB::table('members')
        ->whereYear($key, '=', $year)
        ->get()
        ->groupBy(function($date) use($key) {
            return Carbon::parse($date->$key)->quarter;
        });

        if($members !==null){
            foreach($members as $quarter){
                foreach($quarter as $member){
                    $date = Carbon::parse($member->$key)->quarter-1;
                    $quartery[$date] = $quartery[$date] + 1;
                }
            }
        }
        else if($members === null){
            for($i=0; $i<sizeOf($quartery); $i++){
                $quartery[$i] = 0;
            }
        }
        return $quartery;
    }

}
