<?php namespace Vdomah\Roles\Models;

use Model;
use Auth;
use RainLab\Builder\Classes\PermissionsModel;
use Vdomah\Roles\Models\Permission as PermissionModel;

/**
 * Model
 */
class Role extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['RainLab.Translate.Behaviors.TranslatableModel'];

    public $translatable = ['name'];

    /*
     * Validation
     */
    public $rules = [
    ];

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'vdomah_roles_roles';

    public $belongsTo = [
        'parent' => [
            'Vdomah\Roles\Models\Role',
            'key' => 'parent_id',
        ],
    ];

    public $hasMany = [
        'children' => [
            'Vdomah\Roles\Models\Role',
            'key' => 'parent_id',
        ],
        'permissions' => [
            'Vdomah\Roles\Models\Permission',
        ],
    ];

    public function getParentOptions()
    {
        if ($this->exists) {
            $id = $this->id;
        } else {
            $id = 0;
        }

        return array_merge([0 => '- None -'], Role::where('id', '!=', $id)
            ->lists('name', 'id'));
    }

    public function getParentNameAttribute()
    {
        return $this->parent ? $this->parent->name : '';
    }

    public static function able($perm_code, $user = null)
    {
        $perm = PermissionModel::where('code', $perm_code)->first();

        if (Auth::check() && $user == null) {
            $user = Auth::getUser();
        }

        return $user && $perm ? $user->role->gotPermission($perm) : false;
    }

    public static function isRole($code, $user = null)
    {
        $out = false;

        //$role_ids = [];
        $first = self::where('code', $code)->first();

        $role_ids = $first->ancestors->lists('id');
        $role_ids[] = $first->id;

        if (Auth::check() && $user == null) {
            $user = Auth::getUser();
        }

        if (in_array($user->role_id, $role_ids)) {
            $out = true;
        }

        return $out;
    }

    public function gotPermission($perm)
    {
        $out = false;

        if ($this->id == $perm->role_id) {
            $out = true;
        } else {
            if ($this->children != null) {
                $out = self::iterateChildren($this->children, $perm);
            }
        }

        return $out;
    }

    public static function iterateChildren($children, $perm)
    {
        $out = false;

        foreach ($children as $child) {
            if ($child->id == $perm->role_id) {
                $out = true;
            } elseif ($child->children != null) {
                $out = self::iterateChildren($child->children, $perm);
            }
        }

        return $out;
    }

    public function getAncestorsAttribute()
    {
        $ancestors = [];

        $current = $this->parent;
        while ($current != null) {
            $ancestors[] = $this->parent;
            $current = $current->parent;
        }

        return $ancestors;
    }
}