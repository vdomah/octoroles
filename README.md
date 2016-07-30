# Hierarchic Roles OctoberCMS plugin
This plugin allows to manage access rights based on roles hierarchy.

## Requirements
- [RainLab.User](http://octobercms.com/plugin/rainlab-user) plugin

#Backend
Firstly create roles and permissions. Go to Users > Roles Hierarchy and add some roles. Create hierarchy by choosing parent for each role except the root role.

Then create some permissions. After that you will be able to set rights for the roles to execute permissions as displayed on screenshot.

#Frontend

You can use twig helpers to allow or restrict access.

###isRole(role_code)
Checks if current user has specific role or a role with higher privileges (roles ancestors). For example if user is admin isRole('admin') and isRole('superadmin') will return true.

###able(permission_code)
Checks if current user's role has right to execute a permission. Permission should be assigned to the user's role or to a role with lower privileges (roles successors).