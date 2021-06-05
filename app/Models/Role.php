<?php

namespace App\Models;

use Laratrust\Models\LaratrustRole;
use App\Models\User;

class Role extends LaratrustRole
{
    protected $connection = "mysql2";
    
    public $guarded = [];

}
