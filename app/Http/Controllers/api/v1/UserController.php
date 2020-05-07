<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use Validator;
use Lcobucci\JWT\Parser;
use App\Traits\UserTrait;
use App\Traits\ValidationTrait;
use App\Traits\ResponseTrait;
use App\Subscription;

class UserController extends Controller
{
    use UserTrait, ValidationTrait, ResponseTrait;

    public $successStatus = 200;

    public function index(){
        $res = array();
        if(Gate::allows('admin', Auth::user())){
            $users = DB::table('users', 'roles')
                    ->join('roles', 'role_id', '=', 'roles.id')
                    ->select('users.*', 'roles.name as role')
                    ->orderBy('users.id', 'desc')
                    ->where('roles.level', 1)
                    ->where('users.deleted_at', '=', null)->get();

            return $this->respondData($users);
        }
        return $this->respondAccessError();
    }

    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $token =  $user->createToken('koppa')-> accessToken;
            $user['role'] = $user->role->name;
            return response()->json(['status'=>'success', 'token' => $token, 'user' => $user ], 200);
        }
        else{
            return $this->respondIncompleteAction('Invalid Email or Password');
        }
    }

    public function register(Request $request)
    {
        $input = $request->all();
        $validation = $this->validateUserData($input);
        if($validation->fails()){
            return $this->respondError($validator->errors());
        }

        $userExists = User::where('role_id', '=', $request->role_id)
                    ->orWhere('email', '=', $request->email)->first();

        $homcell_manager_role = Role::find($request->role_id);

        if($userExists && $homcell_manager_role->name !== "homecell manager"){
            return $this->respondError('User role or email already exists');
        }

        if($this->createUser($input)){
            return $this->respondSuccess('User added');
        }
        return $this->respondError('Error adding user');
    }

    public function update(Request $request)
    {
        if(Gate::allows('admin', Auth::user())){
            $user = user::findOrFail($request->id);
            $user->id = $request->input('id');
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->address = $request->input('address');
            $user->phone = $request->input('phone');
            $user->employee = $request->input('employee');
            $user->occupation = $request->input('occupation');
            $user->church_id = $request->input('church_id');
            $user->role_id = $request->input('role_id');
            $user->homecell_id = $request->input('homecell_id');
            if($user->save()){
                return $this->respondSuccess('User updated.');
            }
            return $this->respondError('Update failed.');
        }
        return $this->respondAccessError();
    }

    public function editPassword(Request $request)
    {
        $user = User::find(Auth::user()->id);
        if($user){
            $user->password = bcrypt($request->new_password);
            if($user->save()){
                return $this->respondSuccess('Password updated successfuly.');
            }
            return $this->respondError('Erorr changing password.');
        }
        return $this->respondError('User does not exist.');
    }

    public function updateSuper(Request $request)
    {
        if(Gate::allows('superUser', Auth::user()))
        {
            $user = User::find($request->id);
            if($user){
                foreach($request->all() as $key => $value){
                    if($key === 'c_password'){}
                    else if($key === 'password'){
                        $user->$key = $request->$key? bcrypt($value): $user->$key;
                    }
                    else{
                        $user->$key = $request->$key? $value: $user->$key;
                    }
                }

                if($user->save()){
                    return $this->respondSuccess('User updated.');
                }
                return $respondError('User does not exist.');
            }
            return $this->respondAccessError();
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if($user !== null && $user->token()->revoke())
        {
            return $this->respondSuccess('You have successfully logged out.');
        }
        return $this->respondAccessError();
    }

    public function show($id)
    {
        if(Gate::allows('admin', Auth::user())){
            $user = User::findOrFail($id);
            $user['role'] = $user->role;
            return $user;
        }
        return $this->respondAccessError();
    }

    public function destroy($id)
    {
        if(Gate::allows('admin', Auth::user())){
            $user = User::find($id);
            if($user->delete()){
                return $this->respondSuccess('User deleted.');
            }
            return $this->respondError('Delete failed.');
        }
        return $this->respondAccessError();
    }
}
