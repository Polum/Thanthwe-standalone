<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\OfferingType;
use App\Traits\ResponseTrait;

class OfferingTypeController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $offering_types = OfferingType::all();
        return $this->respondData($offering_types);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        if(OfferingType::create($input)){
            return $this->respondSuccess('Offering type added successfuly.');
        }
        return $this->respondError('Error adding offering type');
    }

    public function update(Request $request, $id)
    {
        $offering_type = OfferingType::find($id);
        if($offering_type){
            $offering_type->id = $request->id;
            $offering_type->offering_title = $request->offering_title;
            if($offering_type->save()){
                return $this->respondSuccess('Offering type updated successfully.');
            }
            return $this->respondError('Error updating offering type.');
        }
        return $this->respondError('Offering type does not exist.');
    }

    public function destroy($id)
    {
        $offering_type = OfferingType::find($id);
        if($offering_type->delete()){
            return $this->respondSuccess('OfferingType deleted successfully');
        }
        return $this->respondError('Error deleting offering type.');
    }
}
