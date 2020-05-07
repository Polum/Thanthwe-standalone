<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Role;
use App\Uer;
use App\Traits\ResponseTrait;

class SetupController extends Controller
{
    use ResponseTrait;

    public function init(Request $request)
    {
        $roles = $this->roles();
        $user = $this->user();

        if($roles === true && $user === true)
        {
            return $this->respondSuccess('Setup completed.');
        }
        return $this->respondError('Setup failed :'.$roles.' '.$user);
    }

    private function roles()
    {
        $roles = array(
            array('name' => 'clerk', 'level' => 1),
            array('name' => 'accountant', 'level' => 1),
            array('name' => 'treasure', 'level' => 1),
            array('name' => 'homecell manager', 'level' => 1),
            array('name' => 'admin', 'level' => 1),
            array('name' => 'hq', 'level' => 2),
            array('name' => 'super user', 'level' => 2)
        );

        $query = DB::table('roles')->insert($roles);
        if($query){
            return true;
        }
        return 'Error adding roles';
    }

    private function user()
    {
        $password = bcrypt('qwerty');
        $user = array(
            'name' => 'Samuel marcelli',
            'email' => 'samuelpmarcello@gmail.com',
            'password' => $password,
            'role_id' => 7,
        );

        $query = DB::table('users')->insert($user);
        if($user){
            return true;
        }
        return 'Error adding user';
    }
}
