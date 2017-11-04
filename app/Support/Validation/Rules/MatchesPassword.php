<?php

namespace App\Support\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use App\Models\Users;

class MatchesPassword extends AbstractRule
{
    protected $password;
    
    public function __construct($password)
    {
        $this->password = $password;
    }

    public function validate($input)
    {
        return password_verify($input, $this->password);
    }
}