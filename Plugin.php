<?php namespace Vdomah\Roles;

use Event;
use Backend;
use System\Classes\PluginBase;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Controllers\Users as UsersController;
use Vdomah\Roles\Classes\Helper;
use Vdomah\Roles\Models\Role as RoleModel;
use Vdomah\Roles\Models\Permission as PermissionModel;

class Plugin extends PluginBase
{
    /**
     * @var array   Require the RainLab.User plugin
     */
    public $require = ['RainLab.User'];

    public function registerComponents()
    {
        return [
            'Vdomah\Roles\Components\Access'       => 'rolesAccess',
        ];
    }

    public function registerSettings()
    {
    }

    public function registerMarkupTags()
    {
        return [
            'functions'   => [
                'able'         => function($permission, $user = null) { return Helper::able($permission, $user); },
                'isRole'     => function($role, $user = null) { return Helper::isRole($role, $user); }
            ]
        ];
    }

    public function boot()
    {
        Event::listen('backend.menu.extendItems', function($manager) {
            $manager->addSideMenuItems('RainLab.User', 'user', [
                'users' => [
                    'label'       => 'vdomah.roles::lang.menu.users',
                    'icon'        => 'icon-user',
                    'code'        => 'users',
                    'owner'       => 'RainLab.User',
                    'url'         => Backend::url('rainlab/user/users'),
                    'order'       => 400
                ],
                'roles_h' => [
                    'label'       => 'vdomah.roles::lang.menu.roles_h',
                    'icon'        => 'icon-registered',
                    'code'        => 'roles_h',
                    'owner'       => 'Vdomah.Roles',
                    'url'         => Backend::url('vdomah/roles/roles'),
                    'order'       => 400
                ],
                'permissions_h' => [
                    'label'       => 'vdomah.roles::lang.menu.permissions_h',
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
                    'type'      => 'relation',
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
                    'label'     => 'vdomah.roles::lang.fields.role',
                    'select' => 'name',
                    'relation' => 'role',
                ]
            ]);
        });
    }

    public function register()
    {
        Event::listen('backend.form.extendFields', function($widget)
        {
            if (!$widget->model instanceof \Cms\Classes\Page) return;

            $widget->addFields(
                [
                    'settings[role]' => [
                        'label'   => 'vdomah.roles::lang.fields.role',
                        'type'    => 'dropdown',
                        'tab'     => 'cms::lang.editor.settings',
                        'options' => $this->getRoleOptions(),
                        'span'    => 'right'
                    ],
                    'settings[permission]' => [
                        'label'   => 'vdomah.roles::lang.fields.permission',
                        'type'    => 'dropdown',
                        'tab'     => 'cms::lang.editor.settings',
                        'options' => $this->getPermissionOptions(),
                        'span'    => 'right'
                    ],
                    'settings[anonymous_only]' => [
                        'label'   => 'vdomah.roles::lang.fields.anonymous_only',
                        'type'    => 'checkbox',
                        'tab'     => 'cms::lang.editor.settings',
                        'span'    => 'left',
                        'comment' => 'vdomah.roles::lang.comments.anonymous_only',
                    ],
                ],
                'primary'
            );
        });
    }

    public function getRoleOptions()
    {
        return array_merge([0 => 'vdomah.roles::lang.fields.empty'], RoleModel::lists('name', 'id'));
    }

    public function getPermissionOptions()
    {
        return array_merge([0 => 'vdomah.roles::lang.fields.empty'], PermissionModel::lists('name', 'id'));
    }
}
