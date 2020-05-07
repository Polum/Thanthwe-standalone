<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

use App\Homecell;
use App\Traits\ResponseTrait;

class HomecellController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $homecells = Homecell::withCount('members')->get();
        return $this->respondActionComplete('', $homecells);
    }

    public function store(Request $request)
    {
        if(Gate::allows('admin', Auth::user())){
            $homecell = $request->all();
            if(Homecell::create($homecell)){
                return $this->respondSuccess('Homecell added.');
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
            $homecell = Homecell::findOrFail($request->id);
            $homecell['id'] = $request->input('id');
            $homecell['name'] = $request->input('name');
            $homecell['location'] = $request->input('location');
            if($homecell->save()){
                return $this->respondSuccess('Update succesful.');
            }
            return $this->respondError('Update failed.');
        }
        return $this->respondAccessError();
    }

    public function destroy($id)
    {
        if(Gate::allows('admin', Auth::user())){
            $homecell = Homecell::find($id);
            if($homecell->delete()){
                return $this->respondSuccess('Homecell deleted.');
            }
            return $this->respondError('Delete failed.');
        }
        return $this->respondAccessError();
    }
}
