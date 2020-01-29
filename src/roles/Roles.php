<?php

namespace ThallesDella\GateKeeper\Roles;

use ArrayObject;
use ThallesDella\GateKeeper\UserInfo;

/**
 * Gate Keeper | Class Roles [ ROLE SYSTEM ]
 *
 * @category GateKeeper\Roles
 * @package  ThallesDella\GateKeeper\Roles
 * @author   Thalles D. koester <thallesdella@gmail.com>
 * @license  https://choosealicense.com/licenses/mit/ MIT
 * @link     https://github.com/thallesdella/gate-keeper
 */
class Roles
{
    /**
     * Array Object containing instances of group object
     *
     * @var ArrayObject
     */
    private $_groups;
    
    /**
     * Array Object containing instances of role object
     *
     * @var ArrayObject
     */
    private $_roles;
    
    /**
     * Roles constructor.
     */
    public function __construct()
    {
        $this->_groups = new ArrayObject();
        $this->_roles = new ArrayObject();
    }
    
    /**
     * @param string      $groupName Name of the group to add
     * @param string|null $authUrl   Auth page of the group
     *
     * @return Roles
     */
    public function addGroup(string $groupName, ?string $authUrl = null): Roles
    {
        $group = new Group($groupName, $this->_groups->count());
        $group->setAuthUrl($authUrl);
        
        $this->_groups->append([$groupName => $group]);
        return $this;
    }
    
    /**
     * @param string     $roleName    Name of role to add
     * @param array|null $permissions Permissions of the role
     * @param string     $groupName   Group of the role
     *
     * @return Roles
     */
    public function addRole(string $roleName, ?array $permissions, string $groupName): Roles
    {
        $roleInfo = new Role($roleName, $this->_roles->count());
        $roleInfo->setPermissions($permissions);
        $roleInfo->setGroup($groupName);
        
        $this->_roles->append([$roleName => $roleInfo]);
        return $this;
    }
    
    /**
     * @param string     $roleName    Name of the role to edit
     * @param array|null $permissions New permissions of the role
     *
     * @return Roles
     */
    public function editPermissions(string $roleName, ?array $permissions): Roles
    {
        /**
         * Role object
         *
         * @var Role $roleInfo
         */
        $roleInfo = $this->_roles[$roleName];
        $roleInfo->setPermissions($permissions);
        return $this;
    }
    
    /**
     * @param string $roleName  Name of the role to edit
     * @param string $groupName New group
     *
     * @return Roles
     */
    public function editRoleGroup(string $roleName, string $groupName): Roles
    {
        /**
         * Role object
         *
         * @var Role $roleInfo
         */
        $roleInfo = $this->_roles[$roleName];
        $roleInfo->setGroup($groupName);
        return $this;
    }
    
    /**
     * @param string      $groupName Name of the group to edit
     * @param string|null $url       New auth url
     *
     * @return Roles
     */
    public function editAuthUrl(string $groupName, ?string $url): Roles
    {
        /**
         * Group object
         *
         * @var Group $groupInfo
         */
        $groupInfo = $this->_groups[$groupName];
        $groupInfo->setAuthUrl($url);
        return $this;
    }
    
    /**
     * @param string      $roleName  Name of the role to be moved
     * @param string|null $otherRole Pivot role
     *
     * @return Roles
     */
    public function moveRoleAfter(string $roleName, ?string $otherRole): Roles
    {
        foreach ($this->_roles->getIterator() as $key => $value) {
            if ($value->level > $this->_roles[$roleName]->level) {
                $this->_roles[$key]->level -= 1;
            }
        }
        
        foreach ($this->_roles->getIterator() as $key => $value) {
            if (empty($otherRole)
                || $value->level > $this->_roles[$otherRole]->level
            ) {
                $this->_roles[$key]->level += 1;
            }
        }
        
        $this->_roles[$roleName]->level = $this->_roles[$otherRole]->level + 1;
        
        return $this;
    }
    
    /**
     * @param string $roleName Name of the role to be deleted
     *
     * @return Roles
     */
    public function deleteRole(string $roleName): Roles
    {
        foreach ($this->_roles->getIterator() as $key => $value) {
            if ($value->level > $this->_roles[$roleName]->level) {
                $this->_roles[$key]->level -= 1;
            }
        }
        
        $this->_roles->offsetUnset($roleName);
        return $this;
    }
    
    /**
     * @param UserInfo $user
     *
     * @return array
     */
    public function getPermissions(UserInfo $user): array
    {
        if (!isset($this->_roles[$user->role])
            || empty($this->_roles[$user->role])
        ) {
            return [];
        }
        
        $permissions = new ArrayObject();
        $userRole = $this->_roles[$user->role];
        foreach ($this->_roles as $key => $value) {
            if ($value->level <= $userRole->level) {
                $permissions->append($value->permissions);
            }
        }
        return $permissions->getArrayCopy();
    }
    
    /**
     * @return ArrayObject
     */
    public function getRoles(): ArrayObject
    {
        return $this->_roles;
    }
    
    /**
     * @param string $roleName Name of the role
     *
     * @return Role
     */
    public function getRole(string $roleName): Role
    {
        return $this->_roles[$roleName];
    }
    
    /**
     * @param string $groupName
     *
     * @return Group|null
     */
    public function getGroup(string $groupName): ?Group
    {
        
        return ($this->_groups[$groupName] ?? null);
    }
    
    /**
     * @param string $roleName
     *
     * @return string
     */
    public function getRoleAuthUrl(string $roleName): string
    {
        $roleInfo = $this->_roles[$roleName];
        $group = $this->getGroup($roleInfo->group);
        return $group->auth_url;
    }
}