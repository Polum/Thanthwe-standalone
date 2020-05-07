<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\HomecellActivity;
use App\Attendance;
use App\Traits\ResponseTrait;

class HomecellActivityController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $homecell_activities = HomecellActivity::paginate(1);
        return $this->respondData($homecell_activities);
    }

    public function store(Request $request)
    {
        if(Gate::allows('homecell-manager', Auth::user())){
            $request['homecell_id'] = Auth::user()->homecell_id;
            $activity = $request->all();
            if(HomecellActivity::create($activity)){
                return $this->respondActionComplete('Activity created succesfully.');
            }
            return $this->respondIncompleteAction('Failed creating activity.');
        }
        return $this->respondAccessError();
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

    public function attendance($id)
    {
        if(Gate::allows('homecell-manager', Auth::user())){
            $attendance_sheet = HomecellActivity::with('attendances.member')->find($id);
            if($attendance_sheet){
                return $this->respondSuccess('Attendance recorded.');
            }
            return $this->respondError('Error recording attendance.');
        }
        return $this->respondAccessError();
    }
}
