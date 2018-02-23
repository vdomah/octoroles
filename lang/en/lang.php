<?php return [
    'plugin' => [
        'name' => 'Hierarchic Roles',
        'description' => 'Allows to manage access rights based on roles hierarchy',
        'description_settings' => 'Choose front-end auth plugin to integrate',
    ],
    'fields' => [
        'name' => 'Name',
        'parent' => 'Parent',
        'role' => 'Role',
        'permission' => 'Permission',
        'code' => 'Code',
        'empty' => ' - None - ',
        'anonymous_only' => 'Anonymous only',
        'logged_only' => 'Authorized users only',
        'user_plugin' => 'User Plugin',
        'user_plugin_comment' => 'RainLab.User and Lovata.Buddies are available if present in the system',
    ],
    'role' => [
        'label' => 'Role',
    ],
    'toolbar' => [
        'comment' => 'To edit a role click the role title in the table header',
    ],
    'menu' => [
        'users' => 'Users',
        'roles_h' => 'Roles Hierarchy',
        'permissions_h' => 'Permissions Hierarchy',
    ],
    'access' => [
        'label' => 'Access',
        'desc' => 'Manage user access to pages by roles',
        'redirect_title' => 'Redirect for anonymous',
        'redirect_desc' => 'Page to redirect unauthorized users trying open a page allowed only for authorized',
        'redirect_auth_title' => 'Redirect for authorized users',
        'redirect_auth_desc' => 'Page to redirect authorized users trying open a page allowed only for unauthorized',
    ],
    'list' => [
        'roles' => 'Roles list',
        'roles_assign' => 'Permissions to Roles assignment',
    ],
    'comments' => [
        'anonymous_only' => 'Allowed only for unauthorized users',
    ],
    'editor' => [
        'access' => 'Access',
    ],
];