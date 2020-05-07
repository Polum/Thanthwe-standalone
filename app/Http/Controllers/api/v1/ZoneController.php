<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

use App\Zone;
use App\Traits\ResponseTrait;

class ZoneController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $zones = Zone::withCount('homecells')->get();
        return $this->respondActionComplete('', $zones);
    }

    public function store(Request $request)
    {
        if(Gate::allows('admin', Auth::user())){
            $zone = $request->all();
            if(zone::create($zone)){
                return $this->respondSuccess('Zone added.');
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
            $zone = Zone::findOrFail($request->id);
            $zone['id'] = $request->input('id');
            $zone['name'] = $request->input('name');
            $zone['location'] = $request->input('location');
            if($zone->save()){
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
