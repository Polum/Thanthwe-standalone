<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Attendance;

class AttendanceController extends Controller
{
    public function index()
    {
    }

    public function store(Request $request)
    {
        if(Gate::allows('homecell-manager', Auth::user())){
            $attendance = $request->all();
            if(Attendance::insert($attendance))
            return response()->json(['code' => 202, 'message' => 'Attendance sheet updated.'], 202);
        }
        return response()->json(['code' => 401, 'data' => 'Un-authorized access.'], 401);
    }

    public function show($id)
    {

    }

    public function destroy($id)
    {
        if(Gate::allows('homecell-manager', Auth::user())){
            $attendance = Attendance::find($id);
            if($attendance->delete()){
                return response()->json(['code' => 202, 'message' => 'Attendance deleted.'], 202);
            }
            return response()->json(['code' => 202, 'message' => 'Delete attendance failed.'], 202);
        }
        return response()->json(['code' => 401, 'data' => 'Un-authorized access.'], 401);
    }
}
