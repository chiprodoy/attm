<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'uuid',
        'nomor_telpon',
        'fcm_token',
        'foto'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'fcm_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['roles'];
    /**
     * Set the user's password.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {

        if(!empty($value)) $this->attributes['password'] = Hash::make($value);
    }
    /**
     * get foto user.
     *
     * @param  string  $value
     * @return void
     */
    public function getFotoAttribute($value)
    {

        return asset($value);
    }

    /**
     * Set the uid.
     *
     * @param  string  $value
     * @return void
     */
    public function setUuidAttribute($value)
    {
        $this->attributes['uuid'] = (string) Str::uuid();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function getPermission() {
        return $this->roles();
    }

    public function isRole($role){
        return $this->roles()->where('role_name',$role)->count() > 0;

        //return ($this->roles()->where('role_name',$role)->count() > 0) ? true : false ;
    }

    public function hasPermission($permission,$modName) {
            foreach ($this->roles as $role) {

                if($role->role_name==RoleName::SUPERADMIN) return true;

                foreach ($role->permissions as $perm) {
                    if ($perm->permission_name === $permission && $perm->mod_name === $modName)
                    {
                        return true;
                    }
                }
            }
        return false;
        // foreach($this->roles as $k => $v){
        //     return $v->permissions()
        //                 ->where('permission_name', $permission)
        //                 ->where('mod_name',"$modName")
        //                 ->orWhere('mod_name','*')->first() ?: false;
        // }
        /*       \Illuminate\Support\Facades\DB::enableQueryLog();
             $data=$this->role->permissions()
             ->where('permission_name', $permission)
             ->where('mod_name',"$modName")->first();
             dd($data);
             dd(\Illuminate\Support\Facades\DB::getQueryLog()); */

            //  return $this->roles()
            //  ->where('role_name', $permission.':'.$modName)->first() ?: false;
    }
}

