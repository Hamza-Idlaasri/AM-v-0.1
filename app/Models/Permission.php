<?php

namespace App\Models;

use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission
{
    protected $connection = "mysql2";
    
    public $guarded = [];
}
