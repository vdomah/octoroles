<?php namespace Vdomah\Roles\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Redirect;
use Flash;
use Vdomah\Roles\Models\Role as RoleModel;
use Vdomah\Roles\Models\Permission as PermissionModel;
use Backend;
use Vdomah\Roles\Classes\Helper;

class Roles extends Controller
{
    public $implement = ['Backend\Behaviors\ListController','Backend\Behaviors\FormController'];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $roles;
    public $permissions;

    public function __construct()
    {
        parent::__construct();

        $this->roles = RoleModel::get();
        $this->permissions = PermissionModel::get();

        BackendMenu::setContext(Helper::getUserPlugin()->getPluginName(), Helper::getUserPlugin()->getBackendMenuName(), 'roles_h');
    }

    public function onAssignPerm()
    {
        if (post('perm_id') && post('role_id')) {
            $p = PermissionModel::find(post('perm_id'));
            $p->role_id = post('role_id');
            $p->save();
        }

        return Redirect::to(Backend::url('vdomah/roles/roles'));
    }
}