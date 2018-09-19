<?php namespace Vdomah\Roles\Models;

use Model;
use Auth;
use Vdomah\Roles\Models\Permission as PermissionModel;
use Vdomah\Roles\Classes\Helper;

/**
 * Model
 */
class Role extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SimpleTree;

    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    public $translatable = ['name'];

    /*
     * Validation
     */
    public $rules = [
    ];

    public $fillable = [
        'name',
        'code',
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

    public function gotPermission($perm)
    {
        $out = false;

        if ($this->id == $perm->role_id) {
            $out = true;
        } else {
            if ($this->children != null) {
                $out = Helper::iterateChildren($this->children, $perm);
            }
        }

        return $out;
    }

    public function getAncestorsAttribute()
    {
        $ancestors = [];

        $current = $this->parent;
        while ($current != null) {
            $ancestors[] = $current;
            $current = $current->parent;
        }

        return $ancestors;
    }
}