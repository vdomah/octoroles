<?php namespace Vdomah\Roles\Components;

use Lang;
use Auth;
use Event;
use Flash;
use Request;
use Redirect;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use ValidationException;
use Vdomah\Roles\Classes\Helper;
use Vdomah\Roles\Models\Role as RoleModel;
use Vdomah\Roles\Models\Permission as PermissionModel;

class Access extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'vdomah.roles::lang.access.label',
            'description' => 'vdomah.roles::lang.access.desc'
        ];
    }

    public function defineProperties()
    {
        return [
            'redirect' => [
                'title'       => 'vdomah.roles::lang.access.redirect_title',
                'description' => 'vdomah.roles::lang.access.redirect_desc',
                'type'        => 'dropdown',
                'default'     => ''
            ],
            'redirectAuth' => [
                'title'       => 'vdomah.roles::lang.access.redirect_auth_title',
                'description' => 'vdomah.roles::lang.access.redirect_auth_desc',
                'type'        => 'dropdown',
                'default'     => ''
            ]
        ];
    }

    public function getRedirectOptions()
    {
        return [''=>'- none -'] + Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getRedirectAuthOptions()
    {
        return [''=>'- none -'] + Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    /**
     * Executed when this component is bound to a page or layout.
     */
    public function onRun()
    {
        $isAuthenticated = Helper::getUserPlugin()->authUser() ? true : false;
        if ($isAuthenticated) {
            $redirectUrl = $this->controller->pageUrl($this->property('redirectAuth'));
        } else {
            $redirectUrl = $this->controller->pageUrl($this->property('redirect'));
        }

        if ($this->page->anonymous_only && $isAuthenticated) {
            if (get('redirect')) {
                $redirectUrl = get('redirect');
            }

            return Redirect::intended($redirectUrl);
        }

        if ($this->page->logged_only && !$isAuthenticated) {
            if (get('redirect')) {
                $redirectUrl = get('redirect');
            }

            return Redirect::guest($redirectUrl);
        }

        if ($redirectUrl) {
            $allowedRole = RoleModel::find($this->page->role);
            $allowedPerm = PermissionModel::find($this->page->permission);

            $allowedByRole = true;
            if ($allowedRole) {
                if ($isAuthenticated)
                    $allowedByRole = Helper::isRole($allowedRole->code);
                else
                    $allowedByRole = false;
            }

            $allowedByPermission = true;
            if ($allowedPerm) {
                if ($isAuthenticated)
                    $allowedByPermission = Helper::able($allowedPerm->code);
                else
                    $allowedByPermission = false;
            }

            if (!$isAuthenticated) {
                $redirectUrl .= '?redirect=' . $_SERVER['REQUEST_URI'];
            }

            if (!$allowedByRole || !$allowedByPermission) {
                return Redirect::guest($redirectUrl);
            }
        }
    }
}
