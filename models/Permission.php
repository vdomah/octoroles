<?php namespace Vdomah\Roles\Models;

use Model;

/**
 * Model
 */
class Permission extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

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
    public $table = 'vdomah_roles_permissions';

    public $belongsTo = [
        'role' => [
            'Vdomah\Roles\Models\Role',
        ],
    ];

    public $belongsToMany = [
        'roles_many' => [
            Role::class,
            'table' => 'vdomah_roles_permission_role',
        ],
        'conditions' => [
            Condition::class,
            'table' => 'vdomah_roles_condition_permission',
        ],
    ];
}
