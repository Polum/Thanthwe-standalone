<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

use App\CommitteeType;
use App\Traits\ResponseTrait;

class CommitteeController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $committees = CommitteeType::all();
        return $this->respondActionComplete('', $committees);
    }

    public function store(Request $request)
    {
        if(Gate::allows('admin', Auth::user())){
            $committee = $request->all();
            if(CommitteeType::create($committee)){
                return $this->respondSuccess('committee added.');
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
            $committee = CommitteeType::findOrFail($request->id);
            $committee['id'] = $request->input('id');
            $committee['name'] = $request->input('name');
            if($committee->save()){
                return $this->respondSuccess('Update succesful.');
            }
            return $this->respondError('Update failed.');
        }
        return $this->respondAccessError();
    }

    public function destroy($id)
    {
        if(Gate::allows('admin', Auth::user())){
            $committee = committeeType::find($id);
            if($committee->delete()){
                return $this->respondSuccess('committee deleted.');
            }
            return $this->respondError('Delete failed.');
        }
        return $this->respondAccessError();
    }
}
