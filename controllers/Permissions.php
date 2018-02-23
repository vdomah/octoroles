<?php namespace Vdomah\Roles\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Flash;
use Vdomah\Roles\Models\Permission as PermissionModel;
use Vdomah\Roles\Classes\Helper;

class Permissions extends Controller
{
    public $implement = ['Backend\Behaviors\ListController','Backend\Behaviors\FormController'];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext(Helper::getUserPlugin()->getPluginName(), Helper::getUserPlugin()->getBackendMenuName(), 'permissions_h');
    }

    public function index_onDelete()
    {
        if(($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds))
        {
            foreach ($checkedIds as $roleId)
            {
                if (!$role = PermissionModel::find($roleId))
                    continue;

                $role->delete();
            }

            Flash::success('The role has been deleted successfully.');
        }

        return $this->listRefresh();
    }
}