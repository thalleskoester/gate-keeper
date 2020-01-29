<?php

namespace ThallesDella\GateKeeper\Roles;

/**
 * Gate Keeper | Class Group [ ROLE SYSTEM ]
 *
 * @category GateKeeper\Roles
 * @package  ThallesDella\GateKeeper\Roles
 * @author   Thalles D. koester <thallesdella@gmail.com>
 * @license  https://choosealicense.com/licenses/mit/ MIT
 * @link     https://github.com/thallesdella/gate-keeper
 */
class Group
{
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var int
     */
    public $id;
    
    /**
     * @var string|null
     */
    public $auth_url;
    
    /**
     * Group constructor.
     *
     * @param string $groupName
     * @param int    $groupId
     */
    public function __construct(string $groupName, int $groupId)
    {
        $this->name = $groupName;
        $this->id = $groupId;
        $this->auth_url = null;
    }
    
    /**
     * @param string|null $auth_url
     */
    public function setAuthUrl(?string $auth_url = null): void
    {
        $this->auth_url = $auth_url;
    }
}