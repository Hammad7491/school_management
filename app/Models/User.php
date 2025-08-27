<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasRoles;

    protected string $guard_name = 'web';

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden   = ['password', 'remember_token'];

    // 👇 add this
    public function student(): HasOne
    {
        return $this->hasOne(Student::class, 'user_id');
    }
}
