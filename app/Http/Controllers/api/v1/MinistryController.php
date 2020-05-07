<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

use App\Ministry;
use App\Traits\ResponseTrait;

class MinistryController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $ministries = Ministry::all();
        return $this->respondActionComplete('', $ministries);
    }

    public function store(Request $request)
    {
        if(Gate::allows('admin', Auth::user())){
            $ministry = $request->all();
            if(Ministry::create($ministry)){
                return $this->respondSuccess('ministry added.');
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
            $ministry = Ministry::findOrFail($request->id);
            $ministry['id'] = $request->input('id');
            $ministry['name'] = $request->input('name');
            if($ministry->save()){
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
