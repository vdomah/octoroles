<?php namespace Vdomah\Roles\Models;

use Model;

/**
 * Model
 */
class Condition extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var bool timestamps are disabled.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'vdomah_roles_conditions';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

    public $belongsToMany = [
        'permissions' => [
            Permission::class,
            'table' => 'vdomah_roles_condition_permission',
        ],
    ];
}
