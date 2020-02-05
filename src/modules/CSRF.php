<?php

namespace ThallesDella\GateKeeper\Modules;

use ThallesDella\SimpleSession\Session;

/**
 * Gate Keeper | Class CSRF [ MODULE ]
 *
 * @category GateKeeper\Modules
 * @package  ThallesDella\GateKeeper\Modules
 * @author   Thalles D. koester <thallesdella@gmail.com>
 * @license  https://choosealicense.com/licenses/mit/ MIT
 * @link     https://github.com/thallesdella/gate-keeper
 */
class CSRF
{
    /**
     * @var Session
     */
    private $_session;
    
    /**
     * @var string
     */
    private $_token;
    
    /**
     * CSRF constructor.
     */
    public function __construct()
    {
        $this->_session = new Session();
        $this->_createToken();
    }
    
    /**
     * @return string
     */
    public function getToken()
    {
        return $this->_token;
    }
    
    /**
     * @return string
     */
    public function generateInput(): string
    {
        return "<input type=\"hidden\" name=\"_token\" value=\"{$this->_token }\"/>";
    }
    
    /**
     * @param array $request
     *
     * @return bool
     */
    public function verifyForm(array $request): bool
    {
        $token = filter_var($request['_token'], FILTER_DEFAULT);
        
        if (!$this->_session->has('_token')
            || empty($token)
            || $token != $this->_session->_token
        ) {
            return false;
        }
        
        $this->_updateToken();
        return true;
    }
    
    /**
     * @return void
     */
    private function _createToken(): void
    {
        if (!$this->_session->has('_token')) {
            $this->_session->_token = $this->_generateToken();
        }
        $this->_token = $this->_session->_token;
    }
    
    /**
     * @return void
     */
    private function _updateToken(): void
    {
        $this->_session->_token = $this->_generateToken();
        $this->_token = $this->_session->_token;
    }
    
    /**
     * @return string
     */
    private function _generateToken(): string
    {
        return sha1(uniqid(rand(), true));
    }
    
}