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

    /*
     * Check if given user or auth user has permission with given code.
     *
     * @param string $perm_code
     *
     * @param mixed $user
     *
     * @return bool
     */
    public static function able($perm_code, $user = null)
    {
        $perm = PermissionModel::where('code', $perm_code)->first();

        if ($user == null) {
            $user = self::getUserPlugin()->authUser();
        }

        return $user && $user->role && $perm ? $user->role->gotPermission($perm) : false;
    }

    /*
     * Check if given user or auth user belongs to the role with given code.
     *
     * @param string $code
     *
     * @param mixed $user
     *
     * @return bool
     */
    public static function isRole($code, $user = null)
    {
        $out = false;

        $role = RoleModel::where('code', $code)->first();

        if ($role) {
            $role_ids = array_pluck($role->ancestors, 'id');
            $role_ids[] = $role->id;

            if ($user == null) {
                $user = self::getUserPlugin()->authUser();
            }

            if ($user && in_array($user->vdomah_role_id, $role_ids)) {
                $out = true;
            }
        }

        return $out;
    }

    /*
     * Find Role record by code.
     *
     * @param string $role_code
     *
     * @return Vdomah\Roles\Models\Role
     */
    public static function roleByCode($role_code)
    {
        return RoleModel::where('code', $role_code)->first();
    }

    /*
     * Recursively iterates through children tree to find if any child's role matches the permission role.
     *
     * @param collection $children
     *
     * @param \Vdomah\Roles\Models\Permission $perm
     *
     * @return bool
     */
    public static function iterateChildren($children, $perm)
    {
        $out = false;

        foreach ($children as $child) {
            if ($child->id == $perm->role_id) {
                $out = true;
            } elseif ($child->children != null) {
                $out = self::iterateChildren($child->children, $perm);
            }

            if ($out)
                break;
        }

        return $out;
    }

    /*
     * Get user plugin info object by detecting presence in the system.
     *
     * @return \Vdomah\Roles\Classes\AbstractPluginInfo
     */
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