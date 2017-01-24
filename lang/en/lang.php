<?php return [
    'plugin' => [
        'name' => 'Hierarchic Roles',
        'description' => '',
    ],
    'fields' => [
        'name' => 'Name',
        'parent' => 'Parent',
        'role' => 'Role',
        'permission' => 'Permission',
        'code' => 'Code',
        'empty' => ' - None - ',
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
    ],
    'editor' => [
        'access' => 'Access',
    ],
];