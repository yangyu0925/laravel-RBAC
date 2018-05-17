<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Zizaco\Entrust\Traits\EntrustUserTrait;


class Admin extends Authenticatable
{
    use Notifiable, EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class,'role_user','user_id');
    }

    public function editViewData($id)
    {
        $user = $this->find($id, ['id', 'name', 'email']);
        $userRole = DB::table('role_user')->where('user_id', $id)->first();
        if ($user) {
            return compact('user', 'userRole');
        }
        abort(404);
    }

}
