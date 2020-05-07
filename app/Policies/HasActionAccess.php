<?php

namespace App\Policies;

// use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HasActionAccess
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function admin_only($user){
        if(!is_null($user->role)){
            if($user->role->name === 'admin'){
                return true;
            }
            return false;
        }
        return false;
    }

    public function clerk_only($user){
        if(!is_null($user->role)){
            if($user->role->name === 'clerk'){
                return true;
            }
            return false;
        }
        return false;
    }

    public function pastor_only($user){
        if(!is_null($user->role)){
            if($user->role->name === 'pastor'){
                return true;
            }
            return false;
        }
        return false;
    }

    public function homecellManager($user){
        if(!is_null($user->role)){
            if($user->role->name === "homecell manager"){
                return true;
            }
            return false;
        }
        return false;
    }

    public function accountant($user){
        if(!is_null($user->role)){
            if($user->role->name === "accountant"){
                return true;
            }
            return false;
        }
        return false;
    }

    public function treasure($user){
        if(!is_null($user->role)){
            if($user->role->name === "treasure"){
                return true;
            }
            return false;
        }
        return false;
    }
}
