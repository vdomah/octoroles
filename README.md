Allows to manage access rights based on roles hierarchy.

## Features
- create your roles and permissions
- group roles into parent - children relations
- manage access to a CMS page
- use Twig helpers to allow or restrict access in views

## Last updates (1.2.0)
- [RainLab.User](http://octobercms.com/plugin/rainlab-user) plugin is no longer required because now it's possible to use [Lovata.Buddies](https://github.com/lovata/oc-buddies-plugin) as front-end auth system
- This may cause issue if you install User or Buddies plugin after Roles is installed. In that case just reinstall the Roles.

# Backend
### Quick start
First, create some roles and/or permissions in Users > Roles Hierarchy. 

Optionaly to use the power of roles hierarchy create roles system by assigning parent - child relations between them.
Then you can create some permissions and assign them to roles depending on hierarchy

### CMS Pages access
After at least one role is created you can choose it in CMS Page settings to allow access only for users of that role or it's parents.
Another option is to manage Page access by choosing permission.
Or check "Only anonymous" checkbox to restrict access for any logged user.

### Settings
If you got both RainLab.User and Lovata.Buddies installed you can choose in settings which to use with this plugin.

# Frontend

You can use twig helpers or PHP static functions to allow or restrict access.

### Check if user got specific role
    Twig: isRole(role_code, user = null)
    PHP: Helper::isRole(role_code, user = null)
Checks if current user has specific role or a role with higher privileges (roles ancestors). 
Example: 
if user is got admin role: isRole('admin') and isRole('superadmin') will return true.

### Check if user got specific permission
    Twig: able(permission_code, user = null)
    PHP: Helper::able(permission_code, user = null) 
Checks if current user's role has right to execute a permission. Permission should be assigned to the user's role or to a role with lower privileges (roles successors).

Pass user object as second parameter to check it rather then authenticated user.