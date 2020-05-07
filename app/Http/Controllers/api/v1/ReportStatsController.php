<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Traits\StatsTrait;
use App\Traits\ResponseTrait;

class ReportStatsController extends Controller
{
    use StatsTrait, ResponseTrait;

    public function stats($year){
        $church_id = Auth::user()->church_id;
        $output = [];
        $tithe_stats = $this->titheStats($church_id, $year);
        $output['tithe'] = $tithe_stats['quartery'];
        $output['members_tithed'] = $tithe_stats['NOMembers'];
        $output['sundayBasket'] = $this->sundayBasketStats($church_id, $year);
        $output['member_joining'] = $this->memberStats($church_id, $year, 'join_date');
        $output['member_leaving'] = $this->memberStats($church_id, $year, 'left');

        return $this->respondData($output);
    }
}
