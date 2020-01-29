<?php

namespace ThallesDella\GateKeeper;

use ThallesDella\GateKeeper\Roles\Roles;

/**
 * Gate Keeper | Class UserInfo [ GATE KEEPER ]
 *
 * @category GateKeeper
 * @package  ThallesDella\GateKeeper
 * @author   Thalles D. koester <thallesdella@gmail.com>
 * @license  https://choosealicense.com/licenses/mit/ MIT
 * @link     https://github.com/thallesdella/gate-keeper
 */
class UserInfo
{
    /**
     * User role name
     *
     * @var string
     */
    public $role;
    
    /**
     * User permissions
     *
     * @var array
     */
    public $permissions;
    
    /**
     * Object roles
     *
     * @var Roles
     */
    private $_roles;
    
    /**
     * UserInfo constructor.
     *
     * @param string|null $userRole Role of the user
     * @param Roles       $roles    Roles object
     */
    public function __construct(?string $userRole, Roles $roles)
    {
        $this->role = (!empty($userRole) ? $userRole : 'guest');
        $this->_roles = $roles;
        $this->permissions = $this->_getPermissions();
    }
    
    /**
     * Get permissions por this user
     *
     * @return array
     */
    private function _getPermissions(): array
    {
        if ($this->role == 'guest') {
            return [];
        }
        
        return $this->_roles->getPermissions($this);
    }
}