<?php namespace Vdomah\Roles;

use Event;
use Backend;
use System\Classes\PluginBase;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Controllers\Users as UsersController;
use Vdomah\Roles\Models\Role as RoleModel;

class Plugin extends PluginBase
{
    /**
     * @var array   Require the RainLab.User plugin
     */
    public $require = ['RainLab.User'];

    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function registerMarkupTags()
    {
        return [
            'functions'   => [
                'able'         => function($permission, $user = null) { return RoleModel::able($permission, $user); },
                'isRole'     => function($role, $user = null) { return RoleModel::isRole($role, $user); }
            ]
        ];
    }

    public function boot()
    {
        Event::listen('backend.menu.extendItems', function($manager) {
            $manager->addSideMenuItems('RainLab.User', 'user', [
                'roles_h' => [
                    'label'       => 'Roles Hierarchy',
                    'icon'        => 'icon-registered',
                    'code'        => 'roles_h',
                    'owner'       => 'Vdomah.Roles',
                    'url'         => Backend::url('vdomah/roles/roles'),
                    'order'       => 400
                ],
                'permissions_h' => [
                    'label'       => 'Permissions Hierarchy',
                    'icon'        => 'icon-lock',
                    'code'        => 'permissions_h',
                    'owner'       => 'Vdomah.Roles',
                    'url'         => Backend::url('vdomah/roles/permissions'),
                    'order'       => 400
                ],
            ]);
        });

        UserModel::extend(function($model)
        {
            $model->belongsTo['role']      = ['Vdomah\Roles\Models\Role'];

            $model->addDynamicMethod('scopeFilterByRole', function($query, $filter) use ($model) {
                return $query->whereHas('role', function($group) use ($filter) {
                    $group->whereIn('id', $filter);
                });
            });
        });

        UsersController::extendFormFields(function($form, $model, $context){

            if (!$model instanceof UserModel)
                return;

//            if (!$model->exists)
//                return;

            $form->addTabfields([
                'role' => [
                    'label'     => 'vdomah.roles::lang.fields.role',
                    'tab'       => 'rainlab.user::lang.user.account',
                    'type'      => 'dropdown',
                    'options'   => array_merge([0 => '- Not chosen -'], RoleModel::lists('name', 'id')),
                ],
            ]);
        });

        Event::listen('backend.list.extendColumns', function($widget) {

            if (!$widget->getController() instanceof \RainLab\User\Controllers\Users) {
                return;
            }

            if (!$widget->model instanceof \RainLab\User\Models\User) {
                return;
            }

            $widget->addColumns([
                'role' => [
                    'select' => 'name',
                    'relation' => 'role',
                ]
            ]);
        });
    }
}
