## Requirements
- [RainLab.User](http://octobercms.com/plugin/rainlab-user) plugin

#Backend
Firstly create roles and permissions. Go to Users > Roles Hierarchy and add some roles. Create hierarchy by choosing parent for each role except the root role.

Then create some permissions. After that you will be able to set rights for the roles to execute permissions as displayed on screenshot.

#Frontend

You can use twig helpers or PHP static functions to allow or restrict access.

### Check if user got specific role
    isRole(role_code)
    Role::isRole(role_code)
Checks if current user has specific role or a role with higher privileges (roles ancestors). For example if user is admin isRole('admin') and isRole('superadmin') will return true.

###Check if user got specific permission
    able(permission_code)
    Role::able(permission_code)
Checks if current user's role has right to execute a permission. Permission should be assigned to the user's role or to a role with lower privileges (roles successors).