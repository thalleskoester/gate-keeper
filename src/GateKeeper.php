<?php

namespace ThallesDella\GateKeeper;

use ThallesDella\GateKeeper\Modules\CSRF;
use ThallesDella\GateKeeper\Modules\Request;
use ThallesDella\GateKeeper\Roles\Roles;

/**
 * Gate Keeper | Class GateKeeper [ GATE KEEPER ]
 *
 * @category GateKeeper
 * @package  ThallesDella\GateKeeper
 * @author   Thalles D. koester <thallesdella@gmail.com>
 * @license  https://choosealicense.com/licenses/mit/ MIT
 * @link     https://github.com/thallesdella/gate-keeper
 */
class GateKeeper
{
    /**
     * UserInfo instance
     *
     * @var UserInfo
     */
    private $user_info;
    
    /**
     * Roles instance
     *
     * @var Roles
     */
    private $roles;
    
    /**
     * GateKeeper constructor.
     *
     * @param string|null $userRole Role of the login user or null for guest
     */
    public function __construct(?string $userRole)
    {
        $this->roles = new Roles(); // Should always be on top
        $this->generateUserInfo($userRole);
    }
    
    /**
     * Get CSRF
     *
     * @return CSRF Csrf property
     */
    public static function csrf(): CSRF
    {
        return new CSRF();
    }
    
    /**
     * Get Requests
     *
     * @return Request Requests property
     */
    public static function requests(): Request
    {
        return new Request();
    }
    
    /**
     * Get Roles
     *
     * @return Roles Roles property
     */
    public function roles(): Roles
    {
        return $this->roles;
    }
    
    /**
     * Get User Info
     *
     * @return UserInfo UserInfo property
     */
    public function userInfo(): UserInfo
    {
        return $this->user_info;
    }
    
    /**
     * Verify if visitor is in group
     *
     * @param string $groupName Group to be verified
     *
     * @return bool
     */
    public function visitorInGroup(string $groupName = 'guest'): bool
    {
        if ($groupName == 'guest') {
            return true;
        }
    
        if ($this->verifyGroup($groupName)) {
            return true;
        }
        return false;
    }
    
    /**
     * Check is user have permission
     *
     * @param string $permission Permission to be checked
     *
     * @return bool
     */
    public function checkPermission(string $permission): bool
    {
        return in_array($permission, $this->user_info->permissions);
    }
    
    /**
     * Get registered roles
     *
     * @return array Array of the registered roles
     */
    public function getRoles(): array
    {
        return $this->roles->getRoles()->getArrayCopy();
    }
    
    /**
     * Generate instance of the class userInfo
     *
     * @param string|null $userRole Role of the login user or null for guest
     *
     * @return void
     */
    private function generateUserInfo(?string $userRole = null): void
    {
        if (isset($this->user_info->role) && empty($userRole)) {
            $this->user_info = new UserInfo($this->user_info->role, $this->roles);
            return;
        }
        $this->user_info = new UserInfo($userRole, $this->roles);
    }
    
    /**
     * Verify if user is in group
     *
     * @param string $groupName Name of the group to restrict access
     *
     * @return bool
     */
    private function verifyGroup(string $groupName): bool
    {
        $groupUser = $this->roles->getGroup($this->user_info->role);
        $groupAccess = $this->roles->getGroup($groupName);
        
        if ($groupUser->id >= $groupAccess->id) {
            return true;
        }
        return false;
    }
}