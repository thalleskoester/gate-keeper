<?php

namespace ThallesDella\GateKeeper\Roles;

use ArrayObject;

/**
 * Gate Keeper | Class Role [ ROLE SYSTEM ]
 *
 * @category GateKeeper\Roles
 * @package  ThallesDella\GateKeeper\Roles
 * @author   Thalles D. koester <thallesdella@gmail.com>
 * @license  https://choosealicense.com/licenses/mit/ MIT
 * @link     https://github.com/thallesdella/gate-keeper
 */
class Role
{
    /**
     * Role name
     *
     * @var string
     */
    public $role;
    
    /**
     * Role level
     *
     * @var int
     */
    public $level;
    
    /**
     * Role permissions
     *
     * @var ArrayObject
     */
    public $permissions;
    
    /**
     * Role group name
     *
     * @var string
     */
    public $group;
    
    /**
     * Role constructor.
     *
     * @param string $role  Role name
     * @param int    $level Level of the role
     */
    public function __construct(string $role, int $level)
    {
        $this->role = $role;
        $this->level = $level;
        $this->permissions = new ArrayObject();
        $this->group = null;
    }
    
    /**
     * Set role permissions
     *
     * @param array $permissions New permissions
     *
     * @return void
     */
    public function setPermissions(array $permissions): void
    {
        $this->permissions->exchangeArray($permissions);
    }
    
    /**
     * Set role group
     *
     * @param string $groupName New group to be associated with the role
     *
     * @return void
     */
    public function setGroup(string $groupName): void
    {
        $this->group = $groupName;
    }
    
}