<?php namespace Vdomah\Roles\Classes;

use Auth;
use Vdomah\Roles\Models\Role as RoleModel;
use Vdomah\Roles\Models\Permission as PermissionModel;
use Vdomah\Roles\Models\Settings;
use System\Classes\PluginManager;

/**
 * Model
 */
class Helper
{
    const USER_PLUGIN_RAINLAB = 'RainLab.User';
    const USER_PLUGIN_LOVATA = 'Lovata.Buddies';
    const USER_MODEL_RAINLAB = 'RainLab\User\Models\User';
    const USER_MODEL_LOVATA = 'Lovata\Buddies\Models\User';
    const USER_CONTROLLER_RAINLAB = 'RainLab\User\Controllers\Users';
    const USER_CONTROLLER_LOVATA = 'Lovata\Buddies\Controllers\Users';

    public static function able($perm_code, $user = null)
    {
        $perm = PermissionModel::where('code', $perm_code)->first();

        if ($user == null) {
            $user = self::getUserPlugin()->authUser();
        }

        return $user && $user->role && $perm ? $user->role->gotPermission($perm) : false;
    }

    public static function isRole($code, $user = null)
    {
        $out = false;

        $first = RoleModel::where('code', $code)->first();

        if ($first) {
            $role_ids = array_pluck($first->ancestors, 'id');
            $role_ids[] = $first->id;

            if ($user == null) {
                $user = self::getUserPlugin()->authUser();
            }
//dd($user->role->code, $first->code, in_array($user->vdomah_role_id, $role_ids));
            if (in_array($user->vdomah_role_id, $role_ids)) {
                $out = true;
            }
        }
        //dd($out, $role_ids, $first);
        return $out;
    }

    public static function roleByCode($role_code)
    {
        return RoleModel::where('code', $role_code)->first();
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

    public static function getUserPlugin()
    {
        $userPluginInfo = null;
        $userPlugin = Settings::get('user_plugin');

        if (!in_array($userPlugin, [self::USER_PLUGIN_RAINLAB, self::USER_PLUGIN_LOVATA])) {
            if (PluginManager::instance()->exists(self::USER_PLUGIN_RAINLAB))
                $userPlugin = self::USER_PLUGIN_RAINLAB;

            if (PluginManager::instance()->exists(self::USER_PLUGIN_LOVATA))
                $userPlugin = self::USER_PLUGIN_LOVATA;
        }

        if ($userPlugin == self::USER_PLUGIN_LOVATA) {
            $userPluginInfo = LovataBuddies::instance();
        } elseif ($userPlugin == self::USER_PLUGIN_RAINLAB) {
            $userPluginInfo = RainLabUser::instance();
        }

        return $userPluginInfo;
    }

}