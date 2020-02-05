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
    private $roles;
    
    /**
     * UserInfo constructor.
     *
     * @param string|null $userRole Role of the user
     * @param Roles       $roles    Roles object
     */
    public function __construct(?string $userRole, Roles $roles)
    {
        $this->role = (!empty($userRole) ? $userRole : 'guest');
        $this->roles = $roles;
        $this->permissions = $this->getPermissions();
    }
    
    /**
     * Get permissions por this user
     *
     * @return array
     */
    private function getPermissions(): array
    {
        if ($this->role == 'guest') {
            return [];
        }
        
        return $this->roles->getPermissions($this);
    }
}