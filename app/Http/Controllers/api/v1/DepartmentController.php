<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

use App\Department;
use App\Traits\ResponseTrait;

class DepartmentController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $departments = Department::all();
        return $this->respondActionComplete('', $departments);
    }

    public function store(Request $request)
    {
        if(Gate::allows('admin', Auth::user())){
            $department = $request->all();
            if(Department::create($department)){
                return $this->respondSuccess('department added.');
            }
            return $this->respondError('Add failed.');
        }
        return $this->respondAccessError();
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        if(Gate::allows('admin', Auth::user())){
            $department = Department::findOrFail($request->id);
            $department['id'] = $request->input('id');
            $department['name'] = $request->input('name');
            if($department->save()){
                return $this->respondSuccess('Update succesful.');
            }
            return $this->respondError('Update failed.');
        }
        return $this->respondAccessError();
    }

    public function destroy($id)
    {
        if(Gate::allows('admin', Auth::user())){
            $zone = Zone::find($id);
            if($zone->delete()){
                return $this->respondSuccess('Zone deleted.');
            }
            return $this->respondError('Delete failed.');
        }
        return $this->respondAccessError();
    }
}
