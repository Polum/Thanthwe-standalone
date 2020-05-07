<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Activity;
use App\Traits\ResponseTrait;

class ActivityController extends Controller
{

    use ResponseTrait;

    public function index()
    {
        $from_day = Carbon::now();
        $to_day = Carbon::now()->addDay(20);
        $activities = DB::table('activities')
        ->where('church_id', '=', Auth::user()->church_id)
        ->whereBetween('start_date',array($from_day, $to_day))->get();

        return $this->respondData($activities);
    }

    public function store(Request $request)
    {
        if(Gate::allows('clerk', Auth::user())){
            $request['church_id'] = Auth::user()->church_id;
            $input = $request->all();
            if(Activity::create($input)){
                return $this->respondSuccess('Activity added.');
            }
            return $this->respondError('Error adding activity.');
        }
        return $this->respondAccessError();
    }

    public function update(Request $request, $id)
    {
        if(Gate::allows('clerk', Auth::user())){
            $activity = Activity::find($id);

            foreach($request->all() as $key => $value){
                $activity->$key = $request->$key? $value: $activity->$key;
            }
            if($activity->save()){
                return $this->respondSuccess('Activity updated.');
            }
            return $this->respondError('Error updating activity.');
        }
        return $this->respondAccessError();
    }

    public function destroy($id)
    {
        if(Gate::allows('clerk', Auth::user())){
            $activity = Activity::find($id);

            if($activity->delete()){
                return $this->respondSuccess('Activity deleted.');
            }
            return $this->respondError('Error deleting activity.');
        }
        return $this->respondAccessError();
    }
}
