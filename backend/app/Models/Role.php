<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    const SUPERADMIN='superadmin';
    const ADMIN='admin';
    const PETUGAS_MEDIS='petugas_medis';




       /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_name',
    ];
   /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'user_modify',
        'user_id'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */

    //protected $with = ['permissions'];

    /**
     * The users that belong to the role.
     */

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    /**
     * The role that belong to the permission.
    *
    * public function permissions()
    * {
    *   return $this->belongsToMany(Permission::class);
    * }
    */

}
