<?php namespace Vdomah\Roles\Classes;

class LovataBuddies extends AbstractPluginInfo
{
    const ADAPTER_PLUGIN = 'Vdomah.RolesBuddies';

    public function getPluginName()
    {
        return 'Lovata.Buddies';
    }

    public function getUserClass()
    {
        return 'Lovata\Buddies\Models\User';
    }

    public function getUserControllerClass()
    {
        return 'Lovata\Buddies\Controllers\Users';
    }

    public function getBackendMenuName()
    {
        return 'main-menu-buddies';
    }

    public function authUser()
    {
        return \Lovata\Buddies\Facades\AuthHelper::getUser();
    }
}