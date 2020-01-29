<?php

namespace ThallesDella\GateKeeper\GateKeeper;

use stdClass;

/**
 * Gate Keeper | Class Actions [ GATE KEEPER ]
 *
 * @category GateKeeper
 * @package  ThallesDella\GateKeeper
 * @author   Thalles D. koester <thallesdella@gmail.com>
 * @license  https://choosealicense.com/licenses/mit/ MIT
 * @link     https://github.com/thallesdella/gate-keeper
 */
class Actions
{
    const SUCCESS_ACTION = 0;
    
    const FAILURE_ACTION = 1;
    
    /**
     * @var stdClass
     */
    private $_success;
    
    /**
     * @var stdClass
     */
    private $_failure;
    
    /**
     * @var mixed
     */
    private $_return;
    
    /**
     * Actions constructor.
     */
    public function __construct()
    {
        $buf = new stdClass();
        $buf->action = null;
        $buf->action_type = null;
        
        $this->_failure = $buf;
        $this->_success = $buf;
    }
    
    /**
     * @param string $func
     * @param int    $type
     *
     * @return void
     */
    public function registerFunction(string $func, int $type): void
    {
        if ($type == self::SUCCESS_ACTION) {
            $this->_success->action = $func;
            $this->_success->action_type = 'func';
            return;
        }
        $this->_failure->action = $func;
        $this->_failure->action_type = 'func';
    }
    
    /**
     * @param mixed  $class
     * @param string $method
     * @param int    $type
     *
     * @return void
     */
    public function registerClass($class, string $method, int $type): void
    {
        $arr = [$class, $method];
        if ($type == self::SUCCESS_ACTION) {
            $this->_success->action = $arr;
            $this->_success->action_type = 'class';
            return;
        }
        $this->_failure->action = $arr;
        $this->_failure->action_type = 'class';
    }
    
    /**
     * @return bool
     */
    public function callSuccess(): bool
    {
        $this->_return = call_user_func($this->_success->action);
        return $this->_verifyResult();
    }
    
    /**
     * @return bool
     */
    public function callFailure(): bool
    {
        $this->_return = call_user_func($this->_failure->action);
        return $this->_verifyResult();
    }
    
    /**
     * @return bool
     */
    private function _verifyResult(): bool
    {
        return (!$this->_return ? false : true);
    }
}