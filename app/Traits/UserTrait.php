<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Validator;
use App\User;
use App\Church;

trait UserTrait {

    public function createUser($input){
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        if($user){
            return true;
        }
        return false;
    }

    public function userDetails($id)
    {
        $user = User::findOrFail($id);
        $user['church'] = $user->church;

        return $user;
    }
}
