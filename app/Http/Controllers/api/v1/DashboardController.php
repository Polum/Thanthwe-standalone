<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Activity;
use App\Homecell;
use App\Member;
use App\Traits\ResponseTrait;

class DashboardController extends Controller
{
    use ResponseTrait;

    public function index(){
        $data = [];
        $church_id = Auth::user()->church_id;
        $data['activities'] = $this->activities($church_id);
        $data['homecells'] = $this->homecells($church_id);
        $data['member_stats'] = $this->memberStats($church_id);
        $data['notifications'] = $this->reverseTransactionNotification();

        return $this->respondData($data);
    }

    private function activities($id){
        $from_day = Carbon::now()->subDay(1);
        $to_day = Carbon::now()->addDay(20);
        $activities = DB::table('activities')
        ->whereBetween('start_date',array($from_day, $to_day))
        ->orderBy('start_date', 'asc')
        ->take(5)
        ->get();

        return $activities;
    }

    private function homecells($id){
        $homecells = Homecell::select('homecell.id')->count();
        return $homecells;
    }

    public function memberStats($id)
    {
        $members = Member::all();
        $cats = [];
        $output = [];
        $total_members = 0;

        foreach($members as $member){
            if( array_key_exists($member->joining_means, $cats)){
                $cats[$member->joining_means] = $cats[$member->joining_means] + 1;
            }
            else{
                $cats[$member->joining_means] = 1;
            }
            $total_members++;
        }

        foreach($cats as $key => $value){
            $data = [];
            $data['means'] = $key;
            $data['percent'] = number_format((float)($value/$total_members) * 100, 1, '.', '');
            array_push($output, $data);
        }

        return $output;
    }

    public function reverseTransactionNotification(){
        $RequestCount = DB::table('transactions')->where('reverse', '=', 'requested')->count();
        return $RequestCount;
    }
}
