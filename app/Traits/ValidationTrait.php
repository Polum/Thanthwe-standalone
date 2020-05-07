<?php

namespace App\Traits;

use Validator;

Trait ValidationTrait{

    public function validateUserData($input)
    {
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        return $validator;
    }

    public function validateChurchData($input)
    {

    }
}
