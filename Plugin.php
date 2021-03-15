<?php namespace Vdomah\Roles;

use Cms\Classes\Page;
use Event;
use Backend;
use System\Classes\PluginBase;
use Vdomah\Roles\Classes\Helper;
use Vdomah\Roles\Components\Access;
use Vdomah\Roles\Models\Role as RoleModel;
use Vdomah\Roles\Models\Permission as PermissionModel;
use Vdomah\Roles\Models\Settings;

class Plugin extends PluginBase
{

    public function registerComponents()
    {
        return [
            Access::class       => 'rolesAccess',
        ];
    }

    public function registerSettings()
    {
        return [
            'config' => [
                'label'       => 'vdomah.roles::lang.plugin.name',
                'icon'        => 'oc-icon-cubes',
                'description' => 'vdomah.roles::lang.plugin.description_settings',
                'class'       => 'Vdomah\Roles\Models\Settings',
                'order'       => 100,
                'permissions' => [
                    'roles-menu-settings',
                ],
            ],
        ];
    }

    public function registerMarkupTags()
    {
        return [
            'functions'   => [
                'able'         => function($permission, $user = null) { return Helper::able($permission, $user); },
                'isRole'     => function($role_code, $user = null) { return Helper::isRole($role_code, $user); },
                'roleByCode'     => function($role_code, $user = null) { return Helper::roleByCode($role_code); },
            ]
        ];
    }

    public function boot()
    {
        if (!$userPlugin = Helper::getUserPlugin()) {
            return;
        }
		$userPlugin->checkRoleIdColumnExists();
        $userClass = $userPlugin->getUserClass();
        $userController = $userPlugin->getUserControllerClass();
        $userRoleIdColumnName = $userPlugin->getUserRoleIdColumnName();

        Event::listen('backend.menu.extendItems', function($manager) use ($userPlugin) {
            $menu = [];
            if ($userPlugin == Helper::USER_PLUGIN_RAINLAB) {
                $menu['users'] = [
                    'label'       => 'vdomah.roles::lang.menu.users',
                    'icon'        => 'icon-user',
                    'code'        => 'users',
                    'owner'       => $userPlugin->getPluginName(),
                    'url'         => Backend::url('rainlab/user/users'),
                    'order'       => 400,
                ];
            }

            $menu = array_merge($menu, [
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
            $manager->addSideMenuItems($userPlugin->getPluginName(), $userPlugin->getBackendMenuName(), $menu);
        });

        RoleModel::extend(function($model) use ($userClass) {
            $model->hasMany['users'] = $userClass;
        });

        $userClass::extend(function($model) use ($userRoleIdColumnName) {
            $model->belongsTo['role']      = ['Vdomah\Roles\Models\Role', 'key' => $userRoleIdColumnName,];
            $model->addFillable($userRoleIdColumnName);

            $model->addDynamicMethod('scopeFilterByRole', function($query, $filter) use ($model) {
                return $query->whereHas('role', function($group) use ($filter) {
                    $group->whereIn('id', $filter);
                });
            });

            $model->addDynamicMethod('isRole', function($code) use ($model) {
                return Helper::isRole($code, $model);
            });

            $model->addDynamicMethod('able', function($code) use ($model) {
                return Helper::able($code, $model);
            });
        });

        $userController::extendFormFields(function($form, $model, $context) use ($userClass) {

            if (!$model instanceof $userClass)
                return;

            if ($form->isNested)
                return;

            $form->addTabfields([
                'role' => [
                    'label'         => 'vdomah.roles::lang.fields.role',
                    'tab'           => 'vdomah.roles::lang.fields.role',
                    'type'          => 'relation',
                    'span'          => 'auto',
                    'emptyOption'   => 'vdomah.roles::lang.fields.empty',
                ],
            ]);
        });

        Event::listen('backend.list.extendColumns', function($widget) use ($userController, $userClass) {

            if (!$widget->getController() instanceof $userController) {
                return;
            }

            if (!$widget->model instanceof $userClass) {
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
            if (!$widget->model instanceof Page) return;

            $widget->addFields(
                [
                    'settings[role]' => [
                        'label'   => 'vdomah.roles::lang.fields.role',
                        'type'    => 'dropdown',
                        'tab'     => 'vdomah.roles::lang.editor.access',
                        'options' => $this->getRoleOptions(),
                        'span'    => 'auto'
                    ],
                    'settings[permission]' => [
                        'label'   => 'vdomah.roles::lang.fields.permission',
                        'type'    => 'dropdown',
                        'tab'     => 'vdomah.roles::lang.editor.access',
                        'options' => $this->getPermissionOptions(),
                        'span'    => 'auto'
                    ],
                    'settings[anonymous_only]' => [
                        'label'   => 'vdomah.roles::lang.fields.anonymous_only',
                        'type'    => 'checkbox',
                        'tab'     => 'vdomah.roles::lang.editor.access',
                        'span'    => 'auto',
                        'comment' => 'vdomah.roles::lang.comments.anonymous_only',
                    ],
                    'settings[logged_only]' => [
                        'label'   => 'vdomah.roles::lang.fields.logged_only',
                        'type'    => 'checkbox',
                        'tab'     => 'vdomah.roles::lang.editor.access',
                        'span'    => 'auto',
                    ],
                ],
                'primary'
            );
        });
    }

    public function getRoleOptions()
    {
        return array_merge([0 => 'vdomah.roles::lang.fields.empty'], RoleModel::lists('name', 'code'));
    }

    public function getPermissionOptions()
    {
        return array_merge([0 => 'vdomah.roles::lang.fields.empty'], PermissionModel::lists('name', 'code'));
    }
}
