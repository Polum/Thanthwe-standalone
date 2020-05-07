<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Member;
use App\Family;
use App\Committee;
use App\OfferingType;
use App\Traits\UserTrait;
use App\Traits\ResponseTrait;

class MemberController extends Controller
{

    use UserTrait, ResponseTrait;

    public function index()
    {
        if(Auth::user()->role->name === 'homecell manager'){
            $members = DB::table('members')
                ->join('homecells', 'homecell_id', '=', 'homecells.id')
                ->select('members.*')
                ->where('homecells.id', '=', Auth::user()->homecell_id)->get();
        }
        else{
            $members = Member::all();
        }
        foreach($members as $member){
            $member->dob = Carbon::parse($member->dob)->format('d/m/Y');
            $dateJoined = Carbon::parse($member->join_date);

            if(Carbon::now()->diffInDays($dateJoined) < 30){
                $member->isNew = true;
            }
        }
        return $this->respondData($members);
    }

    public function store(Request $request)
    {
        if(Gate::allows('clerk_only', Auth::user()) || Gate::allows('admin', Auth::user())){
            if($request->family_id === null || empty($request->family_id)){
                $family = [];
                $family['family_name'] = $request->last_name;
                $family = Family::create($family);
                $request['family_id'] = $family->id;
            }

            $request['envelop_no'] = $this->getEnvelop() + 1;
            $request['join_date'] = Carbon::now();
            $input = $request->all();

            $results = Member::create($input);
            if($results){
                return $this->respondSuccess('Member added succesfully (without adding committee).');
            }
            return $this->respondError('Adding member failed.');
            // return $this->respondData($input);
        }
        return $this->respondAccessError();
    }

    public function memberCommittee(Request $request){
        if(Gate::allows('clerk_only', Auth::user())){
            $input = $request->all();
            $results = Committee::insert($input);
            if($results){
                return $this->respondSuccess('Member assigned to committee.');
            }
            return $this->respondError('Adding member to committee failed.');
        }
        return $this->respondAccessError();
    }

    public function profile($id){
        $offerTypeId = OfferingType::where('offering_title', 'tithe')->first()->id;

        $member = Member::with(['offering' => function($query) use ($offerTypeId){
                        $query->where('offering_type_id', '=', $offerTypeId);
                    }, 'attendances.homecellActivity', 'homecell', 'homecell.zone', 'family.members'])
                    ->where('members.id', '=', $id)
                    ->first();

        $member['committee'] = $this->getCommitteeDetails($id);
        return $this->respondData($member);
    }

    public function getCommitteeDetails($id){

        $results = DB::table('committees')
                    ->join('committee_types', 'committee_type_id', 'committee_types.id')
                    ->join('departments', 'department_id', 'departments.id')
                    ->join('ministries', 'ministry_id', 'ministries.id')
                    ->select('committees.id', 'committee_types.name AS position', 'departments.name AS department', 'ministries.name AS ministry')
                    ->get();
        if($results){
            return $results;
        }
        return false;
    }

    public function show($id)
    {
        $member = Member::with('homecell', 'homecell.zone')->findOrFail($id);
        return $this->respondData($member);
    }

    private function getEnvelop(){
        $envelop_no = null;
        $result = DB::table('members')->orderBy('envelop_no', 'desc')->take(1)
        ->select('members.envelop_no')->get();
        if(!empty($result)){
            foreach($result as $val){
                $envelop_no = $val->envelop_no;
            }
        }
        return $envelop_no;
    }

    public function update(Request $request, $id)
    {
        if(Gate::allows('clerk_only', Auth::user())){
            $member = Member::find($id);

            foreach($request->all() as $key => $value){
                $member->$key = $request->$key? $value: $member->$key;
            }
            if($member->save()){
                return Response(['code' => 202, 'message' => 'Member details updated succesfully'], 202);
            }
            return Response(['code' => 401, 'message' => 'Failed to update member details'], 401);
        }
        return Response(['code' => 401, 'message' => 'You must login first'], 401);
    }

    public function search(Request $request)
    {
        $user = Auth::user();
        if($request->keyword !== null || !empty($request->keyword)){
            $members = Member::where('first_name', 'like','%'.$request->keyword.'%')
            ->orWhere('last_name', 'like','%'.$request->keyword.'%')
            ->orWhere('occupation', 'like', '%'.$request->keyword.'%')->paginate(10);

            if($members){
                foreach($members as $member){
                    $dateJoined = Carbon::parse($member->join_date);
                    if(Carbon::now()->diffInDays($dateJoined) < 30){
                        $member->isNew = true;
                    }
                }
                return $this->respondActionComplete('Search complete', $members);
            }
        }
        return $this->respondIncompleteAction('No keyword provided');
    }

    public function families(){
        $members = DB::table('families')->distinct()
                ->join('members', 'families.id', '=', 'members.family_id')
                ->select('families.*')->get();
        return $this->respondActionComplete('', $members);
    }

    public function attendanceSheet($id){
        if(Gate::allows('homecell-manager', Auth::user())){
            $member = Member::with('attendances.homecellActivity')->find($id);
            if($member){
                return response()->json(['code' => 202, 'data' => $member], 202);
            }
            return response()->json(['code' => 202, 'error' => 'Member does not exist or does not have any attendances'], 202);
        }
        return response()->json(['code' => 401, 'data' => 'Un-authorized access.'], 401);
    }

    public function left($id, $leave_date)
    {
        if(Gate::allows('clerk', Auth::user())){
            $member = Member::find($id);
            if($member){
                $member->left = $leave_date;
                if($member->save()){
                    return $this->respondSuccess('Successfuly removed member from church.');
                }
                return $this->respondError('Error removing member from church.');
            }
            return $this->respondError('Member does not exist');
        }
        return $this->respondAccessError();
    }

    public function deceased($id, $deceased_date)
    {
        if(Gate::allows('clerk', Auth::user())){
            $member = Member::find($id);
            $member->deceased = $deceased_date;
            if($member->save()){
                return $this->respondActionComplete('Deceased record updated.');
            }
            return response()->json(['code' => 202, 'error' => 'Member does not exist or does not have any attendances'], 202);
        }
        return response()->json(['code' => 401, 'data' => 'Un-authorized access.'], 401);
    }

    public function destroy($id)
    {
        //
    }

    public function memberCount(){
        $user = Auth::user();
        $church_id = $user->church_id;
        $members = DB::table('members')->select('members.id')->count();
            return response()->json(['code' => 200, 'data' => $members], 200);
    }
}
