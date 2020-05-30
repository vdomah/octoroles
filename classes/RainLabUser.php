<?php namespace Vdomah\Roles\Classes;

class RainLabUser extends AbstractPluginInfo
{
    const ADAPTER_PLUGIN = 'Vdomah.RolesUser';

    public function getPluginName()
    {
        return 'RainLab.User';
    }

    public function getUserClass()
    {
        return 'RainLab\User\Models\User';
    }

    public function getUserControllerClass()
    {
        return 'RainLab\User\Controllers\Users';
    }

    public function getBackendMenuName()
    {
        return 'user';
    }

    public function authUser()
    {
        return \Auth::getUser();
    }
}