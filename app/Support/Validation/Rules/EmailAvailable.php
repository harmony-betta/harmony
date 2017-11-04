<?php

namespace App\Support\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use App\Models\Users;

class EmailAvailable extends AbstractRule
{
    public function validate($input)
    {
        return Users::where('email', $input)->count() === 0;
    }
}