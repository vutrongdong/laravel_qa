<?php

namespace App\Repositories\Roles;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'permissions'];

    /**
     * The attributes that are cast permission from json string to array
     * @var array
     */
    protected $casts = ['permissions' => 'array'];

    /**
     * Relationship with user
     * @return Relation
     */
    public function users()
    {
        return $this->belongToMany(\App\User::class, 'role_users');
    }

    /**
     * Check specific role has access a resource
     * @param  array   $permissions
     * @return boolean
     */
    public function hasAccess(array $permissions) : bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check a specific permission that belongs to this role
     * @param  string  $permission
     * @return boolean
     */
    private function hasPermission(string $permission) : bool
    {
        return $this->permissions[$permission] ?? false;
    }
}
