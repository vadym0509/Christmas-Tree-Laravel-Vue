<?php

namespace App\Models;

use Purcell\Hone\Models\Member as HoneMember;

class Member extends HoneMember
{
    protected $fillable = [
        'first_name',
        'last_name',
        'company_name',
        'email',
        'password',
    ];
}
