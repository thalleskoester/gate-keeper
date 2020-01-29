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
    private $_user_info;
    
    /**
     * CSRF instance
     *
     * @var CSRF
     */
    private $_csrf;
    
    /**
     * Requests instance
     *
     * @var Request
     */
    private $_requests;
    
    /**
     * Roles instance
     *
     * @var Roles
     */
    private $_roles;
    
    /**
     * GateKeeper constructor.
     *
     * @param string|null $userRole Role of the login user or null for guest
     */
    public function __construct(?string $userRole)
    {
        $this->_roles = new Roles();// Should always be on top
        
        $this->_generateUserInfo($userRole);
        
        $this->_csrf = new CSRF();
        $this->_requests = new Request();
    }
    
    /**
     * Get CSRF
     *
     * @return CSRF Csrf property
     */
    public function csrf(): CSRF
    {
        return $this->_csrf;
    }
    
    /**
     * Get Requests
     *
     * @return Request Requests property
     */
    public function requests(): Request
    {
        return $this->_requests;
    }
    
    /**
     * Get Roles
     *
     * @return Roles Roles property
     */
    public function roles(): Roles
    {
        return $this->_roles;
    }
    
    /**
     * Get User Info
     *
     * @return UserInfo UserInfo property
     */
    public function userInfo(): UserInfo
    {
        return $this->_user_info;
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
        
        if ($this->_verifyGroup($groupName)) {
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
        return in_array($permission, $this->_user_info->permissions);
    }
    
    /**
     * Get registered roles
     *
     * @return array Array of the registered roles
     */
    public function getRoles(): array
    {
        return $this->_roles->getRoles()->getArrayCopy();
    }
    
    /**
     * Generate instance of the class userInfo
     *
     * @param string|null $userRole Role of the login user or null for guest
     *
     * @return void
     */
    private function _generateUserInfo(?string $userRole = null): void
    {
        if (isset($this->_user_info->role) && empty($userRole)) {
            $this->_user_info = new UserInfo($this->_user_info->role, $this->_roles);
            return;
        }
        $this->_user_info = new UserInfo($userRole, $this->_roles);
    }
    
    /**
     * Verify if user is in group
     *
     * @param string $groupName Name of the group to restrict access
     *
     * @return bool
     */
    private function _verifyGroup(string $groupName): bool
    {
        $group_user = $this->_roles->getGroup($this->_user_info->role);
        $group_access = $this->_roles->getGroup($groupName);
        
        if ($group_user->id >= $group_access->id) {
            return true;
        }
        return false;
    }
}